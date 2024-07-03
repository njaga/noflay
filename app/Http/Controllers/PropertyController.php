<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Landlord;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Property::class);

        $query = Property::with('company', 'landlord');

        if (Auth::user()->hasRole('admin_entreprise')) {
            $query->where('company_id', Auth::user()->company_id);
        } elseif (!Auth::user()->hasRole('super_admin')) {
            $query->where('company_id', Auth::user()->company_id);
        }

        $properties = $query->get();

        $landlords = Landlord::all(); // Ou filtrez selon les permissions de l'utilisateur si nécessaire

        return Inertia::render('Properties/Index', [
            'properties' => $properties,
            'landlords' => $landlords,
            'auth' => [
                'user' => Auth::user(),
                'roles' => Auth::user()->roles->pluck('name')
            ]
        ]);
    }


    public function create()
    {
        Gate::authorize('create', Property::class);

        $companies = Auth::user()->hasRole('super_admin')
            ? Company::all()
            : Company::where('id', Auth::user()->company_id)->get();
        $landlords = Auth::user()->hasRole('super_admin')
            ? Landlord::all()
            : Landlord::where('company_id', Auth::user()->company_id)->get();

        return Inertia::render('Properties/Create', [
            'companies' => $companies,
            'landlords' => $landlords,
            'auth' => [
                'user' => Auth::user(),
                'isSuperAdmin' => Auth::user()->hasRole('super_admin')
            ],
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Property::class);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'property_type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string|max:255',
            'price' => 'required|numeric',
            'available_count' => 'required|integer|min:0',
            'landlord_id' => 'required|integer|exists:landlords,id',
            'company_id' => 'required|integer|exists:companies,id',
            'photos.*' => 'nullable|image|max:10240',
        ]);

        if (!Auth::user()->hasRole('super_admin')) {
            $validatedData['company_id'] = Auth::user()->company_id;
        }

        $landlord = Landlord::findOrFail($validatedData['landlord_id']);
        $company = Company::findOrFail($validatedData['company_id']);

        $directory = "properties/{$company->name}/{$landlord->first_name}_{$landlord->last_name}/{$validatedData['name']}";
        Storage::makeDirectory($directory);

        if ($request->hasFile('photos')) {
            $photos = [];
            foreach ($request->file('photos') as $file) {
                $path = $file->store($directory, 'public');
                $photos[] = $path;
            }
            $validatedData['photos'] = json_encode($photos);
        }

        Property::create($validatedData);

        return redirect()->route('properties.index')->with('success', 'Propriété créée avec succès.');
    }

    public function show(Property $property)
    {
        Gate::authorize('view', $property);

        return Inertia::render('Properties/Show', [
            'property' => $property->load('company', 'landlord'),
        ]);
    }

    public function edit(Property $property)
    {
        Gate::authorize('update', $property);

        $companies = Auth::user()->hasRole('super_admin')
            ? Company::all()
            : Company::where('id', Auth::user()->company_id)->get();
        $landlords = Auth::user()->hasRole('super_admin')
            ? Landlord::all()
            : Landlord::where('company_id', Auth::user()->company_id)->get();

        return Inertia::render('Properties/Edit', [
            'property' => $property->load('company', 'landlord'),
            'companies' => $companies,
            'landlords' => $landlords,
            'auth' => [
                'user' => Auth::user(),
                'isSuperAdmin' => Auth::user()->hasRole('super_admin')
            ],
        ]);
    }

    public function update(Request $request, Property $property)
    {
        Gate::authorize('update', $property);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'property_type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string|max:255',
            'price' => 'required|numeric',
            'available_count' => 'required|integer|min:0',
            'landlord_id' => 'required|integer|exists:landlords,id',
            'company_id' => 'required|integer|exists:companies,id',
            'photos.*' => 'nullable|image|max:10240',
        ]);

        if (!Auth::user()->hasRole('super_admin')) {
            $validatedData['company_id'] = Auth::user()->company_id;
        }

        $landlord = Landlord::findOrFail($validatedData['landlord_id']);
        $company = Company::findOrFail($validatedData['company_id']);

        $directory = "public/properties/{$company->name}/{$landlord->first_name}_{$landlord->last_name}/" . str_replace(' ', '_', $validatedData['name']);
        Storage::makeDirectory($directory);

        $photos = json_decode($property->photos) ?? [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                $path = $file->store($directory);
                $photos[] = $path;
            }
            $validatedData['photos'] = json_encode($photos);
        }

        $property->update($validatedData);

        return redirect()->route('properties.index')->with('success', 'Propriété mise à jour avec succès.');
    }

    public function destroy(Property $property)
    {
        Gate::authorize('delete', $property);

        $property->delete();

        return redirect()->route('properties.index')->with('success', 'Propriété supprimée avec succès.');
    }

    public function export()
    {
        Gate::authorize('viewAny', Property::class);

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="properties.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Nom', 'Type', 'Description', 'Adresse', 'Disponible', 'Bailleur', 'Entreprise']);

            $query = Property::with('company', 'landlord');

            if (!Auth::user()->hasRole('super_admin')) {
                $query->where('company_id', Auth::user()->company_id);
            }

            $properties = $query->get();

            foreach ($properties as $property) {
                fputcsv($file, [
                    $property->id,
                    $property->name,
                    $property->property_type,
                    $property->description,
                    $property->address,
                    $property->available_count,
                    $property->landlord->first_name . ' ' . $property->landlord->last_name,
                    $property->company->name
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

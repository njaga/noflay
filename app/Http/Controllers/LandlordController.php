<?php

namespace App\Http\Controllers;

use App\Models\Landlord;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class LandlordController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Landlord::class);

        $query = Landlord::with('company');

        if (Auth::user()->hasRole('admin_entreprise')) {
            $query->where('company_id', Auth::user()->company_id);
        } elseif (!Auth::user()->hasRole('super_admin')) {
            $query->where('company_id', Auth::user()->company_id);
        }

        $landlords = $query->get();

        return Inertia::render('Landlords/Index', [
            'landlords' => $landlords,
            'auth' => [
                'user' => Auth::user(),
                'roles' => Auth::user()->roles->pluck('name')
            ]
        ]);
    }

    public function create()
    {
        Gate::authorize('create', Landlord::class);

        $companies = Auth::user()->hasRole('super_admin')
            ? Company::all()
            : Company::where('id', Auth::user()->company_id)->get();

        return Inertia::render('Landlords/Create', [
            'companies' => $companies,
            'auth' => [
                'user' => Auth::user(),
                'isSuperAdmin' => Auth::user()->hasRole('super_admin')
            ],
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Landlord::class);

        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|string|email|max:255|unique:landlords',
            'identity_number' => 'required|string|max:255',
            'identity_expiry_date' => 'required|date',
            'agency_percentage' => 'required|numeric|min:0|max:100',
            'contract_duration' => 'required|integer|min:1',
            'company_id' => 'required|integer|exists:companies,id',
            'attachments.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('attachments')) {
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $attachments[] = $file->store('attachments');
            }
            $validatedData['attachments'] = json_encode($attachments);
        }

        if (!Auth::user()->hasRole('super_admin')) {
            $validatedData['company_id'] = Auth::user()->company_id;
        }

        Landlord::create($validatedData);

        return redirect()->route('landlords.index')->with('success', 'Bailleur créé avec succès.');
    }

    public function show(Landlord $landlord)
    {
        Gate::authorize('view', $landlord);

        return Inertia::render('Landlords/Show', [
            'landlord' => $landlord->load('company'),
        ]);
    }

    public function edit(Landlord $landlord)
    {
        Gate::authorize('update', $landlord);

        $companies = Auth::user()->hasRole('super_admin')
            ? Company::all()
            : Company::where('id', Auth::user()->company_id)->get();

        return Inertia::render('Landlords/Edit', [
            'landlord' => $landlord->load('company'),
            'companies' => $companies,
            'auth' => [
                'user' => Auth::user(),
                'isSuperAdmin' => Auth::user()->hasRole('super_admin')
            ],
        ]);
    }

    public function update(Request $request, Landlord $landlord)
    {
        Gate::authorize('update', $landlord);

        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|string|email|max:255|unique:landlords,email,' . $landlord->id,
            'identity_number' => 'required|string|max:255',
            'identity_expiry_date' => 'required|date',
            'agency_percentage' => 'required|numeric|min:0|max:100',
            'contract_duration' => 'required|integer|min:1',
            'company_id' => 'required|integer|exists:companies,id',
            'attachments.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('attachments')) {
            $attachments = json_decode($landlord->attachments) ?? [];
            foreach ($request->file('attachments') as $file) {
                $attachments[] = $file->store('attachments');
            }
            $validatedData['attachments'] = json_encode($attachments);
        }

        if (!Auth::user()->hasRole('super_admin')) {
            $validatedData['company_id'] = Auth::user()->company_id;
        }

        $landlord->update($validatedData);

        return redirect()->route('landlords.index')->with('success', 'Bailleur mis à jour avec succès.');
    }

    public function destroy(Landlord $landlord)
    {
        Gate::authorize('delete', $landlord);

        $landlord->delete();

        return redirect()->route('landlords.index')->with('success', 'Bailleur supprimé avec succès.');
    }

    public function export()
    {
        Gate::authorize('viewAny', Landlord::class);

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="landlords.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'First Name', 'Last Name', 'Email', 'Phone', 'Company']);

            $query = Landlord::with('company');

            if (!Auth::user()->hasRole('super_admin')) {
                $query->where('company_id', Auth::user()->company_id);
            }

            $landlords = $query->get();

            foreach ($landlords as $landlord) {
                fputcsv($file, [
                    $landlord->id,
                    $landlord->first_name,
                    $landlord->last_name,
                    $landlord->email,
                    $landlord->phone,
                    $landlord->company->name
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

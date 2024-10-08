<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Landlord;
use App\Models\Company;
use App\Models\Payment;
use App\Models\Contract;
use App\Models\Expense;
use App\Models\LandlordTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
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
        $propertyTypes = DB::table('properties')->distinct()->pluck('property_type');

        return Inertia::render('Properties/Index', [
            'properties' => $properties,
            'landlords' => $landlords,
            'propertyTypes' => $propertyTypes,
            'auth' => [
                'user' => Auth::user(),
                'roles' => Auth::user()->roles->pluck('name')
            ]
        ]);
    }

    public function update(Request $request, Property $property)
    {
        Log::info('Received data:', $request->all());
        Log::info('Files:', $request->allFiles());

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
            'existing_photos' => 'nullable|string',
        ]);

        // Gérer les photos existantes et nouvelles
        $photos = [];
        if ($request->has('existing_photos')) {
            $photos = json_decode($request->input('existing_photos'), true) ?: [];
        }

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('properties/' . $property->id, 'public');
                $photos[] = $path;
            }
        }

        $validatedData['photos'] = json_encode($photos);

        $property->update($validatedData);

        return response()->json(['message' => 'Propriété mise à jour avec succès']);
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

        $property->load(['company', 'landlord', 'contracts.tenant' => function ($query) {
            $query->withDefault(); // This will return an empty model instead of null if the tenant doesn't exist
        }]);

        $rentAmount = $property->contracts->first() ? $property->contracts->first()->rent_amount : 0;
        $commissionCautions = $this->getCommissionCautions($property->id);
        $commissionLoyers = $this->getCommissionLoyers($property->id);
        $totalRentAmount = $this->getTotalRentAmount($property->id);

        return Inertia::render('Properties/Show', [
            'property' => $property,
            'rentAmount' => $rentAmount,
            'commissionCautions' => $commissionCautions,
            'commissionLoyers' => $commissionLoyers,
            'totalRentAmount' => $totalRentAmount,
        ]);
    }

    private function getCommissionCautions($propertyId)
    {
        return Contract::where('property_id', $propertyId)->sum('caution_amount');
    }

    private function getCommissionLoyers($propertyId)
    {
        return Contract::where('property_id', $propertyId)->sum('commission_amount');
    }

    private function getTotalRentAmount($propertyId)
    {
        return Contract::where('property_id', $propertyId)->sum('rent_amount');
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
        $propertyTypes = DB::table('properties')->distinct()->pluck('property_type');

        return Inertia::render('Properties/Edit', [
            'property' => $property->load('company', 'landlord'),
            'companies' => $companies,
            'landlords' => $landlords,
            'propertyTypes' => $propertyTypes,
            'auth' => [
                'user' => Auth::user(),
                'isSuperAdmin' => Auth::user()->hasRole('super_admin')
            ],
        ]);
    }

    public function destroy(Property $property)
    {
        Gate::authorize('delete', $property);

        $property->delete();

        return redirect()->route('properties.index')->with('flash', [
            'message' => 'Propriété supprimée avec succès',
            'type' => 'success'
        ]);
    }

    private function getFilteredProperties()
    {
        $query = Property::with('company', 'landlord');

        if (Auth::user()->hasRole('admin_entreprise') || Auth::user()->hasRole('user_entreprise') || Auth::user()->hasRole('bailleur')) {
            $query->where('company_id', Auth::user()->company_id);
        } elseif (!Auth::user()->hasRole('super_admin')) {
            $query->where('company_id', Auth::user()->company_id);
        }

        return $query->get();
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

    public function report($id)
    {
        $property = Property::with(['contracts.tenant', 'landlord', 'company'])->findOrFail($id);

        $cautionAmounts = Contract::where('property_id', $id)->sum('caution_amount');
        $rentAmounts = Contract::where('property_id', $id)->sum('rent_amount');
        $totalPayments = Payment::whereHas('contract', function ($query) use ($id) {
            $query->where('property_id', $id);
        })->sum('amount');
        $commissionAmounts = Contract::where('property_id', $id)->sum('commission_amount');

        // Revenus mensuels pour l'année en cours
        $monthlyRevenues = Payment::whereHas('contract', function ($query) use ($id) {
            $query->where('property_id', $id);
        })
            ->whereYear('payment_date', date('Y'))
            ->selectRaw('MONTH(payment_date) as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        // Remplir les mois manquants avec des zéros
        $revenueData = array_replace(array_fill(1, 12, 0), $monthlyRevenues);

        // Récupérer les paiements
        $payments = Payment::whereHas('contract', function ($query) use ($id) {
            $query->where('property_id', $id);
        })
            ->with('tenant:id,first_name,last_name')
            ->orderBy('payment_date', 'desc')
            ->get();

        // Récupérer les versements (payouts)
        $payouts = LandlordTransaction::whereHas('landlord', function ($query) use ($property) {
            $query->where('id', $property->landlord_id);
        })
            ->where('type', 'payout')
            ->orderBy('transaction_date', 'desc')
            ->get();

        // Récupérer les dépenses
        $expenses = Expense::where('property_id', $id)
            ->orderBy('expense_date', 'desc')
            ->get();

        return Inertia::render('Properties/Report', [
            'property' => $property,
            'cautionAmounts' => $cautionAmounts,
            'rentAmounts' => $rentAmounts,
            'totalPayments' => $totalPayments,
            'commissionAmounts' => $commissionAmounts,
            'revenueData' => array_values($revenueData),
            'payments' => $payments,
            'payouts' => $payouts,
            'expenses' => $expenses,
        ]);
    }

    public function showArchived($id)
    {
        $property = Property::withTrashed()->with(['landlord', 'tenants', 'payments'])->findOrFail($id);
        return Inertia::render('Properties/ShowArchived', ['property' => $property]);
    }

    public function archives()
    {
        $company_id = Auth::user()->company_id;
        $query = Property::onlyTrashed();

        if (!Auth::user()->hasRole('super_admin')) {
            $query->where('company_id', $company_id);
        }

        $properties = $query->with(['landlord', 'tenants', 'payments'])->get();
        return Inertia::render('Properties/Archives', ['archivedProperties' => $properties]);
    }

    public function restore($id)
    {
        $property = Property::withTrashed()->findOrFail($id);
        $property->restore();

        return redirect()->route('properties.archives')->with('success', 'Propriété restaurée avec succès.');
    }

    public function forceDelete($id)
    {
        try {
            $property = Property::withTrashed()->findOrFail($id);
            $property->forceDelete();

            return redirect()->route('properties.archives')->with('success', 'Propriété supprimée définitivement.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de la propriété : ' . $e->getMessage());
            return redirect()->route('properties.archives')->with('error', 'Une erreur est survenue lors de la suppression de la propriété.');
        }
    }
}

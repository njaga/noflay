<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Property;
use App\Models\Tenant;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class ContractController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Contract::class);

        $query = Contract::with(['property', 'tenant'])->withTrashed();

        if (!Auth::user()->hasRole('super_admin')) {
            $query->whereHas('tenant', function ($q) {
                $q->where('company_id', Auth::user()->company_id);
            });
        }

        $contracts = $query->paginate(10);

        return Inertia::render('Contracts/Index', [
            'contracts' => $contracts,
            'tenants' => Tenant::all(),
            'properties' => Property::all(),
            'auth' => [
                'user' => auth()->user(),
                'roles' => auth()->user()->roles->pluck('name'),
            ],
        ]);
    }

    public function create()
    {
        Gate::authorize('create', Contract::class);

        $companies = Auth::user()->hasRole('super_admin') ? Company::all() : Company::where('id', Auth::user()->company_id)->get();
        $properties = Property::with('landlord')->get(); // Include landlord relation

        return Inertia::render('Contracts/Create', [
            'tenants' => Tenant::all(),
            'properties' => $properties,
            'companies' => $companies,
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Contract::class);

        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'property_id' => 'required|exists:properties,id',
            'contract_type' => 'required|string|in:habitation,commercial',
            'start_date' => 'required|date',
            'duration' => 'required|integer|min:1',
            'file_number' => 'required|string|max:191',
            'caution_amount' => 'nullable|numeric|min:0',
            'company_id' => 'nullable|exists:companies,id',
            'landlord_id' => 'required|exists:landlords,id', // Ensure landlord_id is validated
        ]);

        // Récupérer le prix de la propriété
        $property = Property::findOrFail($validated['property_id']);
        $validated['rent_amount'] = $property->price;

        // Calculer la commission de l'agence immobilière
        $landlord = $property->landlord;
        $commissionRent = $validated['rent_amount'] * ($landlord->agency_percentage / 100);
        $commissionCaution = 0;

        if (isset($validated['caution_amount']) && $validated['caution_amount'] > 0) {
            $commissionCaution = $validated['caution_amount'] * ($landlord->agency_percentage / 100);
        }

        $validated['commission_amount'] = $commissionRent + $commissionCaution;

        // Calculer la date de fin
        $validated['end_date'] = date('Y-m-d', strtotime("+{$validated['duration']} months", strtotime($validated['start_date'])));

        if (!Auth::user()->hasRole('super_admin')) {
            $validated['company_id'] = Auth::user()->company_id;
        }

        // Créer le contrat
        Contract::create($validated);

        // Mettre à jour le solde du bailleur
        $landlord->balance += $validated['caution_amount'] - $commissionCaution;
        $landlord->save();

        // Diminuer le nombre disponible de la propriété
        $property->decrementAvailableCount();

        return redirect()->route('contracts.index')->with('success', 'Contrat créé avec succès.');
    }

    public function show(Contract $contract)
    {
        Gate::authorize('view', $contract);

        // Charger les relations nécessaires
        $contract->load(['property.landlord', 'tenant']);

        // Calcul de la commission sur les loyers
        $rentCommission = $contract->rent_amount * ($contract->property->landlord->agency_percentage / 100);

        // Calcul de la commission sur la caution
        $cautionCommission = $contract->caution_amount * ($contract->property->landlord->agency_percentage / 100);

        // Calcul de la commission totale
        $totalCommission = $rentCommission + $cautionCommission;

        return Inertia::render('Contracts/Show', [
            'contract' => $contract,
            'totalCommission' => $totalCommission,
        ]);
    }

    public function edit(Contract $contract)
    {
        Gate::authorize('update', $contract);

        $companies = Auth::user()->hasRole('super_admin') ? Company::all() : Company::where('id', Auth::user()->company_id)->get();

        return Inertia::render('Contracts/Edit', [
            'contract' => $contract,
            'tenants' => Tenant::all(),
            'properties' => Property::all(),
            'companies' => $companies,
        ]);
    }

    public function update(Request $request, Contract $contract)
    {
        Gate::authorize('update', $contract);

        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'property_id' => 'required|exists:properties,id',
            'contract_type' => 'required|string|in:habitation,commercial',
            'start_date' => 'required|date',
            'duration' => 'required|integer|min:1',
            'file_number' => 'required|string|max:191',
            'caution_amount' => 'nullable|numeric|min:0',
            'company_id' => 'nullable|exists:companies,id',
            'landlord_id' => 'required|exists:landlords,id', // Ensure landlord_id is validated
        ]);

        // Récupérer le prix de la propriété
        $property = Property::findOrFail($validated['property_id']);
        $validated['rent_amount'] = $property->price;

        // Calculer la commission de l'agence immobilière
        $landlord = $property->landlord;
        $commissionRent = $validated['rent_amount'] * ($landlord->agency_percentage / 100);
        $commissionCaution = 0;

        if (isset($validated['caution_amount']) && $validated['caution_amount'] > 0) {
            $commissionCaution = $validated['caution_amount'] * ($landlord->agency_percentage / 100);
        }

        $validated['commission_amount'] = $commissionRent + $commissionCaution;

        // Calculer la date de fin
        $validated['end_date'] = date('Y-m-d', strtotime("+{$validated['duration']} months", strtotime($validated['start_date'])));

        if (!Auth::user()->hasRole('super_admin')) {
            $validated['company_id'] = Auth::user()->company_id;
        }

        // Mettre à jour le contrat
        $contract->update($validated);

        // Mettre à jour le solde du bailleur
        $landlord->balance += $validated['caution_amount'] - $commissionCaution;
        $landlord->save();

        return redirect()->route('contracts.index')->with('success', 'Contrat mis à jour avec succès.');
    }

    public function destroy(Contract $contract)
    {
        Gate::authorize('delete', $contract);

        $contract->delete();

        return redirect()->route('contracts.index')->with('success', 'Contrat supprimé avec succès.');
    }

    public function restore($id)
    {
        $contract = Contract::withTrashed()->findOrFail($id);
        Gate::authorize('restore', $contract);

        $contract->restore();

        return redirect()->route('contracts.index')->with('success', 'Contrat restauré avec succès.');
    }
}

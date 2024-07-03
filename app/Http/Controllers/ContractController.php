<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Contract;
use App\Models\Property;
use App\Models\Tenant;
use App\Models\RentalApplication;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class ContractController extends Controller
{
    // Méthode index pour lister les contrats
    public function index()
    {
        $contracts = Contract::with('tenant', 'property')->get();

        return Inertia::render('Contracts/Index', [
            'contracts' => $contracts,
        ]);
    }

    public function create($tenantId)
    {
        $tenant = Tenant::findOrFail($tenantId);
        $properties = Property::where('company_id', $tenant->company_id)->get();

        return Inertia::render('Contracts/Create', [
            'tenant' => $tenant,
            'properties' => $properties,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'property_id' => 'required|exists:properties,id',
            'contract_type' => 'required|in:habitation,commercial',
            'start_date' => 'required|date',
            'file_number' => 'required|string|max:255',
            'caution_amount' => 'required|numeric',
            'company_name' => 'nullable|string|max:255',
            'representative_name' => 'nullable|string|max:255',
            'trade_register' => 'nullable|string|max:255',
            'duration' => 'required|integer',
            'deposit_amount' => 'required|numeric',
        ]);

        // Ajouter l'ID de l'entreprise pour les utilisateurs non super_admin
        if (!auth()->user()->hasRole('super_admin')) {
            $validated['company_id'] = auth()->user()->company_id;
        }

        // Calculer la date de fin
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = $startDate->copy()->addMonths($validated['duration']);

        // Créez le contrat
        $contract = Contract::create($validated);

        // Enregistrer les informations dans la table rental_applications
        RentalApplication::create([
            'tenant_id' => $validated['tenant_id'],
            'property_id' => $validated['property_id'],
            'contract_type' => $validated['contract_type'],
            'start_date' => $validated['start_date'],
            'end_date' => $endDate,
            'duration' => $validated['duration'],
            'deposit_amount' => $validated['deposit_amount'],
        ]);

        return redirect()->route('contracts.index')->with('success', 'Contrat créé avec succès.');
    }

    public function show(Contract $contract)
    {
        $contract->load('tenant', 'property', 'property.landlord');
        $companies = Company::all();
        $rentalApplication = RentalApplication::where('tenant_id', $contract->tenant_id)
            ->where('property_id', $contract->property_id)
            ->first();

        return Inertia::render('Contracts/Show', [
            'contract' => $contract,
            'rentalApplication' => $rentalApplication,
        ]);
    }

    public function edit(Contract $contract)
    {
        $tenant = $contract->tenant;
        $properties = Property::where('company_id', $tenant->company_id)->get();
        $companies = Company::all();

        return Inertia::render('Contracts/Edit', [
            'contract' => $contract->load('tenant', 'property'),
            'properties' => $properties,
            'companies' => $companies,
        ]);
    }

    public function update(Request $request, Contract $contract)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'contract_type' => 'required|in:habitation,commercial',
            'start_date' => 'required|date',
            'file_number' => 'required|string|max:255',
            'caution_amount' => 'required|numeric',
            'company_name' => 'nullable|string|max:255',
            'representative_name' => 'nullable|string|max:255',
            'trade_register' => 'nullable|string|max:255',
            'duration' => 'required|integer',
            'deposit_amount' => 'required|numeric',
        ]);

        // Calculer la date de fin
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = $startDate->copy()->addMonths($validated['duration']);

        $contract->update($validated);

        // Mettre à jour les informations dans la table rental_applications
        $rentalApplication = RentalApplication::where('tenant_id', $contract->tenant_id)
            ->where('property_id', $contract->property_id)
            ->first();

        if ($rentalApplication) {
            $rentalApplication->update([
                'contract_type' => $validated['contract_type'],
                'start_date' => $validated['start_date'],
                'end_date' => $endDate,
                'duration' => $validated['duration'],
                'deposit_amount' => $validated['deposit_amount'],
            ]);
        }

        return redirect()->route('contracts.index')->with('success', 'Contrat mis à jour avec succès.');
    }

    public function destroy(Contract $contract)
    {
        $contract->delete();

        return redirect()->route('contracts.index')->with('success', 'Contrat supprimé avec succès.');
    }
}

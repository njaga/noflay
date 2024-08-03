<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Property;
use App\Models\Tenant;
use App\Models\Company;
use App\Models\Transaction;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ContractController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        Gate::authorize('viewAny', Contract::class);

        $query = Contract::with(['property', 'tenant']);

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

    public function create(Request $request)
    {
        Gate::authorize('create', Contract::class);

        $companies = Auth::user()->hasRole('super_admin') ? Company::all() : Company::where('id', Auth::user()->company_id)->get();
        $properties = Property::with('landlord')
            ->where('company_id', Auth::user()->company_id)
            ->get();

        return Inertia::render('Contracts/Create', [
            'tenants' => Tenant::all(),
            'properties' => $properties,
            'companies' => $companies,
            'tenant' => null,
        ]);
    }

    public function createWithTenant($tenantId)
    {
        Gate::authorize('create', Contract::class);

        $tenant = Tenant::find($tenantId);
        if (!$tenant) {
            return redirect()->route('contracts.create')->withErrors('Tenant not found.');
        }

        $companies = Auth::user()->hasRole('super_admin') ? Company::all() : Company::where('id', Auth::user()->company_id)->get();
        $properties = Property::with('landlord')
            ->where('company_id', Auth::user()->company_id)
            ->get();

        return Inertia::render('Contracts/Create', [
            'tenants' => Tenant::all(),
            'properties' => $properties,
            'companies' => $companies,
            'tenant' => $tenant,
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
            'landlord_id' => 'required|exists:landlords,id',
        ]);

        $property = Property::findOrFail($validated['property_id']);
        $validated['rent_amount'] = $property->price;

        $landlord = $property->landlord;
        $commissionRent = $validated['rent_amount'] * ($landlord->agency_percentage / 100);
        $commissionCaution = 0;

        if (isset($validated['caution_amount']) && $validated['caution_amount'] > 0) {
            $commissionCaution = $validated['caution_amount'] * ($landlord->agency_percentage / 100);
        }

        $validated['commission_amount'] = $commissionRent + $commissionCaution;
        $validated['end_date'] = date('Y-m-d', strtotime("+{$validated['duration']} months", strtotime($validated['start_date'])));

        if (!Auth::user()->hasRole('super_admin')) {
            $validated['company_id'] = Auth::user()->company_id;
        }

        $contract = Contract::create($validated);

        $landlord->balance += $validated['caution_amount'] - $commissionCaution;
        $landlord->save();

        $property->decrementAvailableCount();

        // Créer une transaction pour la caution
        if (isset($validated['caution_amount']) && $validated['caution_amount'] > 0) {
            Transaction::create([
                'date' => $contract->start_date,
                'amount' => $validated['caution_amount'],
                'type' => 'DEPOSIT',
                'description' => 'Caution pour le contrat',
                'property_id' => $contract->property_id,
                'landlord_id' => $contract->property->landlord_id,
                'tenant_id' => $contract->tenant_id,
                'contract_id' => $contract->id,
            ]);
        }

        return redirect()->route('contracts.index')->with('success', 'Contrat créé avec succès.');
    }

    public function show(Contract $contract)
    {
        Gate::authorize('view', $contract);

        $contract->load(['property.landlord', 'tenant']);

        $rentCommission = $contract->rent_amount * ($contract->property->landlord->agency_percentage / 100);
        $cautionCommission = $contract->caution_amount * ($contract->property->landlord->agency_percentage / 100);
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
            'landlord_id' => 'required|exists:landlords,id',
        ]);

        $property = Property::findOrFail($validated['property_id']);
        $validated['rent_amount'] = $property->price;

        $landlord = $property->landlord;
        $commissionRent = $validated['rent_amount'] * ($landlord->agency_percentage / 100);
        $commissionCaution = 0;

        if (isset($validated['caution_amount']) && $validated['caution_amount'] > 0) {
            $commissionCaution = $validated['caution_amount'] * ($landlord->agency_percentage / 100);
        }

        $validated['commission_amount'] = $commissionRent + $commissionCaution;
        $validated['end_date'] = date('Y-m-d', strtotime("+{$validated['duration']} months", strtotime($validated['start_date'])));

        if (!Auth::user()->hasRole('super_admin')) {
            $validated['company_id'] = Auth::user()->company_id;
        }

        $contract->update($validated);

        $landlord->balance += $validated['caution_amount'] - $commissionCaution;
        $landlord->save();

        return redirect()->route('contracts.index')->with('success', 'Contrat mis à jour avec succès.');
    }

    public function archives()
    {
        $this->authorize('viewAny', Contract::class);

        $query = Contract::onlyTrashed()->with(['property', 'tenant']);

        if (!Auth::user()->hasRole('super_admin')) {
            $query->whereHas('tenant', function ($q) {
                $q->where('company_id', Auth::user()->company_id);
            });
        }

        $contracts = $query->paginate(10);

        return Inertia::render('Contracts/Archives', [
            'contracts' => $contracts,
        ]);
    }

    public function destroy($id)
    {
        $contract = Contract::withTrashed()->findOrFail($id);
        $this->authorize('delete', $contract);

        $contract->delete();

        return redirect()->route('contracts.index')->with('success', 'Contrat supprimé avec succès.');
    }

    public function restore($id)
    {
        $contract = Contract::withTrashed()->findOrFail($id);
        $this->authorize('restore', $contract);

        $contract->restore();

        return redirect()->route('contracts.archives')->with('success', 'Contrat restauré avec succès.');
    }

    public function forceDelete($id)
    {
        $contract = Contract::withTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $contract);

        $contract->forceDelete();

        return redirect()->route('contracts.archives')->with('success', 'Contrat supprimé définitivement avec succès.');
    }

    public function showArchived($id)
    {
        $contract = Contract::withTrashed()->with(['property.landlord', 'tenant'])->findOrFail($id);

        if (!Auth::user()->hasRole('super_admin') && Auth::user()->company_id !== $contract->tenant->company_id) {
            abort(403, 'This action is unauthorized.');
        }

        // Récupérer le pourcentage d'agence, même si la propriété ou le bailleur ont été supprimés
        $agencyPercentage = $contract->property?->landlord?->agency_percentage ?? 0;

        $rentCommission = $contract->rent_amount * ($agencyPercentage / 100);
        $cautionCommission = $contract->caution_amount * ($agencyPercentage / 100);
        $totalCommission = $rentCommission + $cautionCommission;

        return Inertia::render('Contracts/ShowArchived', [
            'contract' => $contract,
            'rentCommission' => $rentCommission,
            'cautionCommission' => $cautionCommission,
            'totalCommission' => $totalCommission,
            'propertyName' => $contract->property?->name ?? 'Propriété supprimée',
            'propertyAddress' => $contract->property?->address ?? 'N/A',
            'landlordName' => $contract->property?->landlord?->name ?? 'Bailleur supprimé',
        ]);
    }

    public function uploadDocument(Request $request, Contract $contract)
    {
        $request->validate([
            'document_type' => 'required|string|in:contract_signed,insurance,inventory,other_documents',
            'file' => 'required|file|max:10240', // 10MB max
        ]);

        $path = $request->file('file')->store('contracts/' . $contract->id, 'public');

        $column = $request->document_type . '_path';
        $contract->$column = $path;
        $contract->save();

        return response()->json(['message' => 'Document uploaded successfully']);
    }

    public function downloadDocument(Contract $contract, $documentType)
    {
        $column = $documentType . '_path';
        $path = $contract->$column;

        if (!$path) {
            return response()->json(['error' => 'Document not found'], 404);
        }

        return response()->file(storage_path('app/public/' . $path));
    }

    public function deleteDocument(Contract $contract, $documentType)
    {
        $column = $documentType . '_path';
        $path = $contract->$column;

        if (!$path) {
            return response()->json(['error' => 'Document not found'], 404);
        }

        // Delete the file from storage
        Storage::disk('public')->delete($path);

        // Clear the path in the database
        $contract->$column = null;
        $contract->save();

        return response()->json(['message' => 'Document deleted successfully']);
    }

}

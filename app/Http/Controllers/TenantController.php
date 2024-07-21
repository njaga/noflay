<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Company;
use App\Models\Property;
use App\Models\Contract;
use App\Models\Landlord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Inertia\Inertia;


class TenantController extends Controller
{

    public function index()
    {
        Gate::authorize('viewAny', Tenant::class);

        // Charger les locataires avec leurs contrats, propriétés et bailleurs
        $tenants = Tenant::with(['contracts.property.landlord'])->get();

        // Filtrer les locataires actifs et inactifs
        $activeTenants = $tenants->filter(function ($tenant) {
            return $tenant->contracts->where('end_date', '>', now())->isNotEmpty();
        });

        $inactiveTenants = $tenants->filter(function ($tenant) {
            return $tenant->contracts->where('end_date', '<=', now())->isNotEmpty();
        });

        // Charger les bailleurs
        $landlords = Landlord::all();

        return Inertia::render('Tenants/Index', [
            'activeTenants' => $activeTenants->values(),
            'inactiveTenants' => $inactiveTenants->values(),
            'landlords' => $landlords,
            'auth' => [
                'user' => auth()->user(),
                'roles' => auth()->user()->roles->pluck('name'),
            ],
        ]);
    }



    private function formatDate($date)
    {
        if (!$date) {
            return 'N/A';
        }

        try {
            return (new \DateTime($date))->format('Y-m-d');
        } catch (\Exception $e) {
            return 'Invalid Date';
        }
    }


    public function create()
    {
        Gate::authorize('create', Tenant::class);

        $companies = Auth::user()->hasRole('super_admin') ? Company::all() : Company::where('id', Auth::user()->company_id)->get();

        return Inertia::render('Tenants/Create', [
            'companies' => $companies,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'id_card_number' => 'required|string|max:255',
            'id_card_expiration_date' => 'required|date',
        ]);

        $tenant = new Tenant($request->all());
        if (!Auth::user()->hasRole('super_admin')) {
            $tenant->company_id = Auth::user()->company_id;
        } else {
            $tenant->company_id = $request->company_id;
        }
        $tenant->save();

        return redirect()->route('contracts.create', $tenant->id)->with('success', 'Locataire créé avec succès. Veuillez créer un contrat.');
    }


    public function show(Tenant $tenant)
    {
        Gate::authorize('view', $tenant);

        $tenant->load('contracts.property.landlord');

        return Inertia::render('Tenants/Show', [
            'tenant' => $tenant,
        ]);
    }



    public function edit(Tenant $tenant)
    {
        Gate::authorize('update', $tenant);

        return Inertia::render('Tenants/Edit', [
            'tenant' => $tenant,
        ]);
    }

    public function update(Request $request, Tenant $tenant)
    {
        Gate::authorize('update', $tenant);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'id_card_number' => 'required|string|max:255',
            'id_card_expiration_date' => 'required|date',
        ]);

        $tenant->update($validated);

        return redirect()->route('tenants.index')->with('success', 'Tenant updated successfully.');
    }

    public function destroy(Tenant $tenant)
    {
        Gate::authorize('delete', $tenant);

        $tenant->delete();

        return redirect()->route('tenants.index')->with('success', 'Tenant deleted successfully.');
    }

    public function createContract(Request $request, Tenant $tenant)
    {
        Gate::authorize('create', Contract::class);

        $properties = Property::where('company_id', $tenant->company_id)->get();

        return Inertia::render('Contracts/Create', [
            'tenant' => $tenant,
            'properties' => $properties,
        ]);
    }

    public function storeContract(Request $request, Tenant $tenant)
    {
        Gate::authorize('create', Contract::class);

        $validated = $request->validate([
            'contract_type' => 'required|string|in:habitation,commercial',
            'start_date' => 'required|date',
            'property_id' => 'required|integer|exists:properties,id',
        ]);

        $contractData = [
            'tenant_id' => $tenant->id,
            'contract_type' => $validated['contract_type'],
            'start_date' => $validated['start_date'],
            'property_id' => $validated['property_id'],
        ];

        if ($validated['contract_type'] === 'habitation') {
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'phone_number' => 'required|string|max:20',
                'id_card_number' => 'required|string|max:255',
                'deposit_amount' => 'required|numeric|min:0',
            ]);
            $contractData['first_name'] = $request->first_name;
            $contractData['last_name'] = $request->last_name;
            $contractData['phone_number'] = $request->phone_number;
            $contractData['id_card_number'] = $request->id_card_number;
            $contractData['deposit_amount'] = $request->deposit_amount;
        } else if ($validated['contract_type'] === 'commercial') {
            $request->validate([
                'company_name' => 'required|string|max:255',
                'representative' => 'required|string|max:255',
                'trade_register' => 'required|string|max:255',
                'deposit_amount' => 'required|numeric|min:0',
            ]);
            $contractData['company_name'] = $request->company_name;
            $contractData['representative'] = $request->representative;
            $contractData['trade_register'] = $request->trade_register;
            $contractData['deposit_amount'] = $request->deposit_amount;
        }

        Contract::create($contractData);

        return redirect()->route('tenants.index')->with('success', 'Contract created successfully.');
    }
}

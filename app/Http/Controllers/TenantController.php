<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Company;
use App\Models\Property;
use App\Models\Contract;
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

        $query = Tenant::with(['property', 'rentalApplications.property']);

        if (!Auth::user()->hasRole('super_admin')) {
            $query->where('company_id', Auth::user()->company_id);
        }

        $tenants = $query->get()->map(function ($tenant) {
            $rentalApplication = $tenant->rentalApplications->first();  

            $start_date = $rentalApplication ? $this->formatDate($rentalApplication->start_date) : 'N/A';
            $end_date = $rentalApplication ? $this->formatDate($rentalApplication->end_date) : 'N/A';
            $status = $rentalApplication && $rentalApplication->end_date && new \DateTime($rentalApplication->end_date) > new \DateTime() ? 'active' : 'inactive';

            return [
                'id' => $tenant->id,
                'first_name' => $tenant->first_name,
                'last_name' => $tenant->last_name,
                'email' => $tenant->email,
                'property' => $rentalApplication && $rentalApplication->property ? $rentalApplication->property->name : 'N/A',
                'property_address' => $rentalApplication && $rentalApplication->property ? $rentalApplication->property->address : 'N/A',
                'status' => $status,
                'start_date' => $start_date,
                'end_date' => $end_date,
            ];
        });

        $propertiesQuery = Property::query();
        if (!Auth::user()->hasRole('super_admin')) {
            $propertiesQuery->where('company_id', Auth::user()->company_id);
        }

        $properties = $propertiesQuery->get()->map(function ($property) {
            return [
                'id' => $property->id,
                'name' => $property->name,
            ];
        });

        return Inertia::render('Tenants/Index', [
            'tenants' => $tenants,
            'properties' => $properties,
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

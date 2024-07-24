<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Company;
use App\Models\Property;
use App\Models\Contract;
use App\Models\Landlord;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class TenantController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Tenant::class);

        // Charger les locataires actifs avec leurs contrats, propriétés et bailleurs
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

    public function archives()
    {
        try {
            Log::info('Starting archives method');

            $user = auth()->user();

            // Récupérer les locataires supprimés en fonction du rôle de l'utilisateur
            if ($user->hasRole('super_admin')) {
                $tenants = Tenant::onlyTrashed()->with(['contracts.property.landlord'])->get();
            } elseif ($user->hasRole('admin_entreprise') || $user->hasRole('user_entreprise')) {
                $tenants = Tenant::onlyTrashed()
                    ->where('company_id', $user->company_id)
                    ->with(['contracts.property.landlord'])
                    ->get();
            } else {
                abort(403);
            }

            Log::info('Retrieved archived tenants', ['count' => $tenants->count()]);

            return Inertia::render('Tenants/Archives', [
                'tenants' => $tenants,
                'auth' => [
                    'user' => $user,
                    'roles' => $user->roles->pluck('name'),
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error in archives method', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return Inertia::render('Error', [
                'status' => 500,
                'message' => 'Une erreur est survenue lors du chargement des archives.'
            ]);
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

    public function restore($id)
    {
        $tenant = Tenant::withTrashed()->findOrFail($id);
        Gate::authorize('restore', $tenant);

        $tenant->restore();

        return redirect()->route('tenants.archives')->with('success', 'Tenant restored successfully.');
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

    public function forceDelete($id)
    {
        $tenant = Tenant::withTrashed()->findOrFail($id);
        Gate::authorize('forceDelete', $tenant);

        $tenant->forceDelete();

        return redirect()->route('tenants.archives')->with('success', 'Locataire supprimé définitivement.');
    }

    public function showArchived($id)
    {
        $tenant = Tenant::withTrashed()->find($id);

        if (!$tenant) {
            // Handle the case where the tenant is not found
            return Inertia::render('Tenants/ShowArchived', [
                'tenant' => null,
                'properties' => [],
                'payments' => [],
                'auth' => [
                    'user' => Auth::user(),
                    'roles' => Auth::user()->roles->pluck('name')
                ]
            ]);
        }

        Gate::authorize('view', $tenant);

        $tenant->load([
            'contracts' => function($query) {
                $query->withTrashed();
            },
            'contracts.property' => function($query) {
                $query->withTrashed();
            },
            'contracts.property.landlord' => function($query) {
                $query->withTrashed();
            },
            'company'
        ]);

        // Récupérer les propriétés associées
        $properties = Property::with(['contracts' => function($query) use ($tenant) {
            $query->where('tenant_id', $tenant->id)->withTrashed();
        }, 'landlord'])->whereHas('contracts', function ($query) use ($tenant) {
            $query->where('tenant_id', $tenant->id);
        })->withTrashed()->get();

        // Récupérer les paiements associés
        $payments = Payment::with(['contract' => function($query) {
            $query->withTrashed();
        }, 'contract.property' => function($query) {
            $query->withTrashed();
        }])->whereHas('contract', function ($query) use ($tenant) {
            $query->where('tenant_id', $tenant->id);
        })->withTrashed()->orderBy('payment_date', 'desc')->get();

        return Inertia::render('Tenants/ShowArchived', [
            'tenant' => $tenant,
            'properties' => $properties,
            'payments' => $payments,
            'auth' => [
                'user' => Auth::user(),
                'roles' => Auth::user()->roles->pluck('name')
            ]
        ]);
    }

}

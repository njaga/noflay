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
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

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
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:tenants',
                'phone_number' => 'required|string|max:20',
                'address' => 'required|string|max:255',
                'id_card_number' => 'required|string|max:255',
                'id_card_expiration_date' => 'required|date',
                'attachments' => 'nullable|array|max:5',
                'attachments.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120', // 5MB max
            ]);

            $attachments = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $fileName = $this->generateUniqueFileName($file);
                    $path = $file->storeAs('tenant-attachments', $fileName, 'public');
                    $attachments[] = [
                        'name' => $file->getClientOriginalName(),
                        'path' => $path,
                    ];
                }
            }

            $tenant = new Tenant($validated);
            $tenant->attachments = json_encode($attachments);
            $tenant->company_id = $request->user()->company_id;
            $tenant->save();

            return redirect()->route('contracts.createWithTenant', ['tenantId' => $tenant->id])
                ->with('success', 'Locataire créé avec succès. Vous pouvez maintenant créer un contrat pour ce locataire.');
        } catch (\Exception $e) {
            Log::error('Tenant creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return back()->withErrors(['error' => "Une erreur s'est produite lors de la création du locataire."])->withInput();
        }
    }

    private function generateUniqueFileName($file)
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();

        // Tronquer le nom du fichier s'il est trop long
        $truncatedName = Str::limit($originalName, 100, '');

        // Générer un nom de fichier unique
        $fileName = $truncatedName . '_' . time() . '_' . Str::random(10) . '.' . $extension;

        return $fileName;
    }

    public function show(Tenant $tenant)
    {
        $tenant->load('contracts.property.landlord');

        // Récupérer et formater les pièces jointes
        $attachments = [];
        if ($tenant->attachments) {
            // Vérifier si $tenant->attachments est déjà un tableau
            $attachmentsData = is_array($tenant->attachments)
                ? $tenant->attachments
                : json_decode($tenant->attachments, true);

            // Vérifier si le décodage a réussi
            if (is_array($attachmentsData)) {
                foreach ($attachmentsData as $attachment) {
                    $attachments[] = [
                        'name' => $attachment['name'],
                        'url' => Storage::url($attachment['path']),
                    ];
                }
            }
        }

        return Inertia::render('Tenants/Show', [
            'tenant' => array_merge($tenant->toArray(), ['formatted_attachments' => $attachments])
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
        Log::info('Raw request data:', $request->all());
        Log::info('Files:', $request->allFiles());

        Gate::authorize('update', $tenant);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:tenants,email,' . $tenant->id,
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'id_card_number' => 'required|string|max:255',
            'id_card_expiration_date' => 'required|date',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpeg,png,jpg,pdf,doc,docx|max:2048',
            'removed_attachments' => 'nullable|array',
            'removed_attachments.*' => 'string',
        ]);

        // Mise à jour des informations de base du locataire
        $tenant->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'address' => $validated['address'],
            'id_card_number' => $validated['id_card_number'],
            'id_card_expiration_date' => $validated['id_card_expiration_date'],
        ]);

        // Gestion des pièces jointes
        $currentAttachments = json_decode($tenant->attachments, true) ?? [];

        // Suppression des pièces jointes
        if (isset($validated['removed_attachments'])) {
            foreach ($validated['removed_attachments'] as $removedAttachment) {
                $key = array_search($removedAttachment, array_column($currentAttachments, 'name'));
                if ($key !== false) {
                    Storage::delete($currentAttachments[$key]['path']);
                    unset($currentAttachments[$key]);
                }
            }
            $currentAttachments = array_values($currentAttachments); // Réindexer le tableau
        }

        // Ajout de nouvelles pièces jointes
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('tenant-attachments', 'public');
                $currentAttachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                ];
            }
        }

        // Mise à jour des pièces jointes dans la base de données
        $tenant->attachments = json_encode($currentAttachments);
        $tenant->save();

        return redirect()->route('tenants.index')->with('success', 'Locataire mis à jour avec succès.');
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
            'contracts' => function ($query) {
                $query->withTrashed();
            },
            'contracts.property' => function ($query) {
                $query->withTrashed();
            },
            'contracts.property.landlord' => function ($query) {
                $query->withTrashed();
            },
            'company'
        ]);

        // Récupérer les propriétés associées
        $properties = Property::with(['contracts' => function ($query) use ($tenant) {
            $query->where('tenant_id', $tenant->id)->withTrashed();
        }, 'landlord'])->whereHas('contracts', function ($query) use ($tenant) {
            $query->where('tenant_id', $tenant->id);
        })->withTrashed()->get();

        // Récupérer les paiements associés
        $payments = Payment::with(['contract' => function ($query) {
            $query->withTrashed();
        }, 'contract.property' => function ($query) {
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

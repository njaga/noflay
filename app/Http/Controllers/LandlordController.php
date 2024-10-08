<?php

namespace App\Http\Controllers;

use App\Mail\LandlordAccountCreated;
use App\Models\Landlord;
use App\Models\Company;
use App\Models\Property;
use App\Models\Contract;
use App\Models\Payment;
use App\Models\Expense;
use App\Models\LandlordTransaction;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Carbon\Carbon;
use App\Models\Attachment;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LandlordController extends Controller
{
    const TVA_RATE = 0.18; // Taux de TVA à 18%

    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('super_admin')) {
            $landlords = Landlord::with('company')->get();
        } elseif ($user->hasRole('admin_entreprise')) {
            $landlords = Landlord::where('company_id', $user->company_id)->with('company')->get();
        } else {
            abort(403, 'THIS ACTION IS UNAUTHORIZED.');
        }

        return Inertia::render('Landlords/Index', [
            'landlords' => $landlords,
            'auth' => [
                'user' => $user,
                'roles' => $user->roles->pluck('name')
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

    public function archives()
    {
        try {
            Log::info('Starting archives method');

            $user = auth()->user();

            // Récupérer les bailleurs supprimés en fonction du rôle de l'utilisateur
            if ($user->hasRole('super_admin')) {
                $landlords = Landlord::onlyTrashed()->with('company')->get();
            } elseif ($user->hasRole('admin_entreprise') || $user->hasRole('user_entreprise')) {
                $landlords = Landlord::onlyTrashed()
                    ->where('company_id', $user->company_id)
                    ->with('company')
                    ->get();
            } else {
                abort(403);
            }

            Log::info('Retrieved archived landlords', ['count' => $landlords->count()]);

            return Inertia::render('Landlords/Archives', [
                'landlords' => $landlords,
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

    public function restore($id)
    {
        $landlord = Landlord::withTrashed()->findOrFail($id);
        Gate::authorize('restore', $landlord);

        $landlord->restore();

        return redirect()->route('landlords.archives')->with('success', 'Bailleur restauré avec succès.');
    }

    public function store(Request $request)
    {
        try {
            Gate::authorize('create', Landlord::class);

            Log::info('Received request data:', $request->all());

            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'phone' => 'required|string|max:25',
                'email' => 'required|string|email|max:255|unique:landlords',
                'identity_number' => 'required|string|max:255',
                'identity_expiry_date' => 'required|date|after:today',
                'agency_percentage' => 'required|numeric|min:0|max:100',
                'rental_percentage' => 'required|numeric|min:0|max:100',
                'contract_duration' => 'required|integer|min:1',
                'company_id' => 'required|integer|exists:companies,id',
                'attachments' => 'nullable|array|max:5',
                'attachments.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
            ]);

            if ($validator->fails()) {
                return Inertia::render('Landlords/Create', [
                    'errors' => $validator->errors(),
                ])->withViewData(['error' => 'Validation failed']);
            }

            $validatedData = $validator->validated();

            if (!Auth::user()->hasRole('super_admin')) {
                $validatedData['company_id'] = Auth::user()->company_id;
            }

            $attachments = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    try {
                        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
                        $path = $file->storeAs('attachments', $filename, 'public');
                        $attachments[] = $path;
                    } catch (\Exception $e) {
                        Log::error("Error storing file: " . $e->getMessage());
                        return Inertia::render('Landlords/Create', [
                            'errors' => ['attachments' => 'Une erreur est survenue lors du téléchargement des pièces jointes.'],
                        ])->withViewData(['error' => 'File upload failed']);
                    }
                }
            }
            $validatedData['attachments'] = json_encode($attachments);

            $landlord = Landlord::create($validatedData);

            Log::info('Landlord created successfully:', $landlord->toArray());

            return Inertia::render('Landlords/Index', [
                'message' => 'Bailleur créé avec succès.',
            ]);
        } catch (\Exception $e) {
            Log::error('Error in LandlordController@store: ' . $e->getMessage());
            return Inertia::render('Landlords/Create', [
                'errors' => ['general' => 'Une erreur est survenue lors de la création du bailleur.'],
            ])->withViewData(['error' => 'Creation failed']);
        }
    }


    public function show($id)
    {
        $landlord = Landlord::with(['properties.contracts.tenant', 'company'])->findOrFail($id);
        Gate::authorize('view', $landlord);

        $currentDate = Carbon::now();

        // Récupérer toutes les transactions récentes
        $recentTransactions = $this->getRecentTransactions($landlord, $currentDate);

        // Calculer les informations financières
        $financialInfo = $this->calculateFinancialInfo($landlord, $currentDate);

        // Assurez-vous que toutes les valeurs sont des nombres
        $financialInfo = array_map(function ($value) {
            return is_numeric($value) ? (float)$value : $value;
        }, $financialInfo);

        return Inertia::render('Landlords/Show', [
            'landlord' => $landlord,
            'transactions' => $recentTransactions,
            'financialInfo' => $financialInfo,
            'company' => $landlord->company,
            'auth' => [
                'user' => Auth::user(),
                'roles' => Auth::user()->roles->pluck('name')
            ]
        ]);
    }

    private function getRecentTransactions($landlord, $date)
    {
        $transactions = collect();

        // Transactions du bailleur
        $transactions = $transactions->concat(
            LandlordTransaction::where('landlord_id', $landlord->id)
                ->orderBy('transaction_date', 'desc')
                ->take(10)
                ->get()
                ->map(function ($item) {
                    $item->type = 'transaction';
                    $item->transaction_date = $item->transaction_date;
                    return $item;
                })
        );

        // Dépenses
        $transactions = $transactions->concat(
            Expense::whereHas('property', function ($query) use ($landlord) {
                $query->where('landlord_id', $landlord->id);
            })
                ->orderBy('expense_date', 'desc')
                ->take(10)
                ->get()
                ->map(function ($item) {
                    $item->type = 'expense';
                    $item->transaction_date = $item->expense_date;
                    return $item;
                })
        );

        // Contrats
        $transactions = $transactions->concat(
            Contract::whereHas('property', function ($query) use ($landlord) {
                $query->where('landlord_id', $landlord->id);
            })
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get()
                ->map(function ($item) {
                    $item->type = 'contract';
                    if ($item->commission_amount) {
                        $item->sub_type = 'contract_commission';
                        $item->amount = $item->commission_amount;
                    } elseif ($item->caution_amount) {
                        $item->sub_type = 'contract_caution';
                        $item->amount = $item->caution_amount;
                    }
                    $item->transaction_date = $item->created_at;
                    return $item;
                })
        );

        // Paiements
        $transactions = $transactions->concat(
            Payment::whereHas('contract.property', function ($query) use ($landlord) {
                $query->where('landlord_id', $landlord->id);
            })
                ->orderBy('payment_date', 'desc')
                ->take(10)
                ->get()
                ->map(function ($item) {
                    $item->type = 'payment';
                    $item->transaction_date = $item->payment_date;
                    return $item;
                })
        );

        return $transactions->sortByDesc('transaction_date')->take(10)->values();
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

        // Validation des données du formulaire, y compris rental_percentage et la date d'expiration
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|string|email|max:255|unique:landlords,email,' . $landlord->id,
            'identity_number' => 'required|string|max:255',
            'identity_expiry_date' => 'required|date|after:today', // Validation de la date d'expiration
            'agency_percentage' => 'required|numeric|min:0|max:100',
            'rental_percentage' => 'required|numeric|min:0|max:100',
            'contract_duration' => 'required|integer|min:1',
            'company_id' => 'required|integer|exists:companies,id',
            'attachments.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5120 KB = 5 MB
        ]);

        // Gestion des pièces jointes si nécessaire
        if ($request->hasFile('attachments')) {
            $attachments = json_decode($landlord->attachments) ?? [];
            foreach ($request->file('attachments') as $file) {
                $attachments[] = $file->store('attachments');
            }
            $validatedData['attachments'] = json_encode($attachments);
        }

        // Si l'utilisateur n'est pas un super admin, forcer l'id de la compagnie à celui de l'utilisateur authentifié
        if (!Auth::user()->hasRole('super_admin')) {
            $validatedData['company_id'] = Auth::user()->company_id;
        }

        // Mise à jour des informations du bailleur
        $landlord->update($validatedData);

        return redirect()->route('landlords.index')->with('success', 'Bailleur mis à jour avec succès.');
    }


    public function destroy(Landlord $landlord)
    {
        Gate::authorize('delete', $landlord);

        $landlord->delete();

        return redirect()->route('landlords.index')->with('success', 'Landlord deleted successfully.');
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

    private function calculateFinancialInfo(Landlord $landlord, Carbon $date)
    {
        $monthlyRent = Payment::whereHas('contract.property', function ($query) use ($landlord) {
            $query->where('landlord_id', $landlord->id);
        })->whereYear('payment_date', $date->year)
            ->whereMonth('payment_date', $date->month)
            ->where('is_reversed', false)
            ->sum('amount');

        $monthlyExpenses = Expense::whereHas('property', function ($query) use ($landlord) {
            $query->where('landlord_id', $landlord->id);
        })->where('is_repay', false)
            ->whereYear('expense_date', $date->year)
            ->whereMonth('expense_date', $date->month)
            ->sum('amount');

        $totalCautionAmount = Contract::whereHas('property', function ($query) use ($landlord) {
            $query->where('landlord_id', $landlord->id);
        })->where('is_reversed', false)
            ->sum('caution_amount');

        // Calcul des commissions de gérance
        $agencyPercentage = $landlord->agency_percentage / 100;
        $rentCommission = $monthlyRent * $agencyPercentage;
        $cautionCommission = $totalCautionAmount * $agencyPercentage;

        // Calcul des commissions locatives
        $rentalPercentage = $landlord->rental_percentage / 100;
        $rentalCommission = $monthlyRent * $rentalPercentage;

        $totalCommissions = $rentCommission + $cautionCommission + $rentalCommission;

        $totalTva = ($monthlyRent + $totalCautionAmount) * self::TVA_RATE;

        $netBalance = ($monthlyRent + $totalCautionAmount - $monthlyExpenses - $totalCommissions - $totalTva);

        return [
            'monthlyRent' => $monthlyRent,
            'totalCautionAmount' => $totalCautionAmount,
            'monthlyExpenses' => $monthlyExpenses,
            'totalCommissions' => $totalCommissions,
            'netBalance' => $netBalance,
            'totalTva' => $totalTva,
            'monthlyRentDetails' => Payment::whereHas('contract.property', function ($query) use ($landlord) {
                $query->where('landlord_id', $landlord->id);
            })->whereYear('payment_date', $date->year)
                ->whereMonth('payment_date', $date->month)
                ->where('is_reversed', false)
                ->get(['tenant_id', 'contract_id', 'amount'])
                ->map(function ($payment) {
                    return [
                        'tenant' => $payment->contract?->tenant?->first_name . ' ' . $payment->contract?->tenant?->last_name ?? 'Locataire non spécifié',
                        'property' => $payment->contract->property->name,
                        'amount' => round($payment->amount, 2)
                    ];
                }),
            'cautionDetails' => Contract::whereHas('property', function ($query) use ($landlord) {
                $query->where('landlord_id', $landlord->id);
            })->where('is_reversed', false)
                ->get(['tenant_id', 'property_id', 'caution_amount'])
                ->map(function ($contract) {
                    return [
                        'tenant' => $contract->tenant ? $contract->tenant->first_name . ' ' . $contract->tenant->last_name : 'Locataire non spécifié',
                        'property' => $contract->property->name,
                        'amount' => round($contract->caution_amount, 2)
                    ];
                }),
            'expenseDetails' => Expense::whereHas('property', function ($query) use ($landlord) {
                $query->where('landlord_id', $landlord->id);
            })->where('is_repay', false)
                ->whereYear('expense_date', $date->year)
                ->whereMonth('expense_date', $date->month)
                ->get(['type', 'description', 'amount'])
                ->map(function ($expense) {
                    return [
                        'type' => $expense->type,
                        'description' => $expense->description,
                        'amount' => round($expense->amount, 2)
                    ];
                }),
            'commissionDetails' => [
                [
                    'type' => 'Caution Commission',
                    'amount' => round($cautionCommission, 2)
                ],
                [
                    'type' => 'Rent Commission',
                    'amount' => round($rentCommission, 2)
                ],
                [
                    'type' => 'Rental Commission',
                    'amount' => round($rentalCommission, 2)
                ]
            ],
            'payoutDetails' => LandlordTransaction::where('landlord_id', $landlord->id)
                ->where('type', 'payout')
                ->whereYear('transaction_date', $date->year)
                ->whereMonth('transaction_date', $date->month)
                ->get(['amount', 'transaction_date'])
                ->map(function ($transaction) {
                    return [
                        'amount' => round($transaction->amount, 2),
                        'date' => Carbon::parse($transaction->transaction_date)->format('Y-m-d')
                    ];
                })
        ];
    }


    public function createAccount($id)
    {
        try {
            $landlord = Landlord::findOrFail($id);

            // Vérifier si le bailleur a déjà un compte
            if ($landlord->user_id) {
                return response()->json(['error' => 'Ce bailleur a déjà un compte'], 400);
            }

            // Générer le mot de passe
            $password = Str::random(8);

            // Créer l'utilisateur
            $user = User::create([
                'name' => $landlord->first_name . ' ' . $landlord->last_name,
                'email' => $landlord->email,
                'password' => Hash::make($password),
                'company_id' => auth()->user()->company_id, // Associer l'utilisateur à l'entreprise de l'utilisateur actuel
            ]);

            // Assigner le rôle "bailleur"
            $role = Role::where('name', 'bailleur')->first();
            if ($role) {
                $user->assignRole($role);
            } else {
                Log::error('Erreur: le rôle "bailleur" n\'existe pas.');
                return response()->json(['error' => 'Echec lors de la création du compte'], 500);
            }

            // Associer l'utilisateur au bailleur
            $landlord->user_id = $user->id;
            $landlord->save();

            // Envoyer un email avec les détails du compte
            Mail::to($landlord->email)->send(new LandlordAccountCreated($user, $password));

            return response()->json([
                'email' => $user->email,
                'password' => $password
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du compte du bailleur: ' . $e->getMessage());
            return response()->json(['error' => 'Echec lors de la création du compte'], 500);
        }
    }

    public function forceDelete($id)
    {
        $landlord = Landlord::withTrashed()->findOrFail($id);
        Gate::authorize('forceDelete', $landlord);

        $landlord->forceDelete();

        return redirect()->route('landlords.archives')->with('success', 'Bailleur supprimé définitivement.');
    }


    public function showArchived($id)
    {
        $landlord = Landlord::withTrashed()->with(['properties.contracts.tenant', 'company', 'properties.tenant'])->findOrFail($id);
        Gate::authorize('view', $landlord);

        $properties = Property::with(['contracts.tenant', 'expenses'])
            ->where('landlord_id', $id)
            ->withTrashed()
            ->get();

        $transactions = LandlordTransaction::where('landlord_id', $id)
            ->withTrashed()
            ->orderBy('transaction_date', 'desc')
            ->get();

        return Inertia::render('Landlords/ShowArchived', [
            'landlord' => $landlord,
            'properties' => $properties,
            'transactions' => $transactions,
            'auth' => [
                'user' => Auth::user(),
                'roles' => Auth::user()->roles->pluck('name')
            ]
        ]);
    }
}

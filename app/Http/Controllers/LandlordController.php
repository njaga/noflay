<?php

namespace App\Http\Controllers;

use App\Models\Landlord;
use App\Models\Company;
use App\Models\Property;
use App\Models\Contract;
use App\Models\Payment;
use App\Models\Expense;
use App\Models\LandlordTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Carbon\Carbon;

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

    public function show($id)
    {
        $landlord = Landlord::with(['properties.contracts.tenant', 'company'])->findOrFail($id);
        Gate::authorize('view', $landlord);

        // Récupérer les transactions récentes
        $transactions = LandlordTransaction::where('landlord_id', $id)
            ->orderBy('transaction_date', 'desc')
            ->take(10)
            ->get();

        // Récupérer les dépenses récentes
        $expenses = Expense::whereHas('property', function ($query) use ($landlord) {
            $query->where('landlord_id', $landlord->id);
        })
        ->orderBy('expense_date', 'desc')
        ->take(10)
        ->get();

        // Récupérer les contrats récents
        $contracts = Contract::whereHas('property', function ($query) use ($landlord) {
            $query->where('landlord_id', $landlord->id);
        })
        ->orderBy('created_at', 'desc')
        ->take(10)
        ->get()
        ->map(function ($contract) {
            $contract->type = 'contract';
            if ($contract->commission_amount) {
                $contract->sub_type = 'contract_commission';
                $contract->amount = $contract->commission_amount;
            } elseif ($contract->caution_amount) {
                $contract->sub_type = 'contract_caution';
                $contract->amount = $contract->caution_amount;
            }
            $contract->transaction_date = $contract->created_at;
            return $contract;
        });

        // Récupérer les paiements récents
        $payments = Payment::whereHas('contract.property', function ($query) use ($landlord) {
            $query->where('landlord_id', $landlord->id);
        })
        ->orderBy('payment_date', 'desc')
        ->take(10)
        ->get();

        // Fusionner toutes les transactions dans un seul tableau
        $recentTransactions = $transactions->concat($expenses)->concat($contracts)->concat($payments)->sortByDesc(function ($item) {
            return $item->transaction_date ?? $item->expense_date ?? $item->created_at ?? $item->payment_date;
        })->take(10);

        // Ajouter un type par défaut si absent
        $recentTransactions->each(function ($item) {
            if (!isset($item->type)) {
                if ($item instanceof LandlordTransaction) {
                    $item->type = 'transaction';
                } elseif ($item instanceof Expense) {
                    $item->type = 'expense';
                    $item->transaction_date = $item->expense_date;
                } elseif ($item instanceof Contract) {
                    $item->type = 'contract';
                } elseif ($item instanceof Payment) {
                    $item->type = 'payment';
                    $item->transaction_date = $item->payment_date;
                } else {
                    $item->type = 'unknown';
                }
            }
        });

        $financialInfo = $this->calculateFinancialInfo($landlord, Carbon::now());

        return Inertia::render('Landlords/Show', [
            'landlord' => $landlord,
            'transactions' => $recentTransactions->values(),
            'financialInfo' => $financialInfo,
            'company' => $landlord->company,
            'auth' => [
                'user' => Auth::user(),
                'roles' => Auth::user()->roles->pluck('name')
            ]
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

    public function destroy($id)
    {
        $transaction = LandlordTransaction::findOrFail($id);
        Gate::authorize('delete', $transaction);
        $transaction->delete();

        return back()->with('success', 'Transaction deleted successfully.');
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

        $agencyPercentage = $landlord->agency_percentage / 100;
        $rentCommission = $monthlyRent * $agencyPercentage;
        $cautionCommission = $totalCautionAmount * $agencyPercentage;

        $totalCommissions = $rentCommission + $cautionCommission;

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
                        'tenant' => $payment->contract->tenant->first_name . ' ' . $payment->contract->tenant->last_name,
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
                        'tenant' => $contract->tenant->first_name . ' ' . $contract->tenant->last_name,
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
}

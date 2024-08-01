<?php

namespace App\Http\Controllers;

use App\Models\Landlord;
use App\Models\Property;
use App\Models\Transaction;
use App\Models\LandlordTransaction;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Support\Facades\Gate;

class TransactionController extends Controller
{
    public function grandLivre(Request $request)
    {
        Gate::authorize('viewAny', Transaction::class);

        $user = $request->user();
        $query = Transaction::with(['property', 'landlord', 'tenant']);
        $landlordQuery = LandlordTransaction::with('landlord');

        if (!$user->hasRole('super_admin')) {
            if ($user->hasRole(['admin_entreprise', 'user_entreprise'])) {
                $companyId = $user->company_id;
                $query->whereHas('property', function ($q) use ($companyId) {
                    $q->where('company_id', $companyId);
                });
                $landlordQuery->whereHas('landlord', function ($q) use ($companyId) {
                    $q->where('company_id', $companyId);
                });
            } elseif ($user->hasRole('bailleur')) {
                $landlordId = $user->landlord_id;
                $query->where('landlord_id', $landlordId);
                $landlordQuery->where('landlord_id', $landlordId);
            }
        }

        // Appliquer les filtres
        if ($request->filled('type')) {
            if ($request->type === 'LANDLORD_PAYOUT') {
                $query->whereNull('id');
                $landlordQuery->where('type', 'payout');
            } else {
                $query->where('type', $request->type);
                $landlordQuery->whereNull('id');
            }
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
            $landlordQuery->whereDate('transaction_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
            $landlordQuery->whereDate('transaction_date', '<=', $request->date_to);
        }

        if ($request->filled('landlord_id')) {
            $query->where('landlord_id', $request->landlord_id);
            $landlordQuery->where('landlord_id', $request->landlord_id);
        }
        if ($request->filled('tenant_id')) {
            $query->where('tenant_id', $request->tenant_id);
        }
        if ($request->filled('property_id')) {
            $query->where('property_id', $request->property_id);
        }

        $transactions = $query->get();
        $landlordTransactions = $landlordQuery->get();

        $allTransactions = $transactions->concat($landlordTransactions)
            ->sortByDesc(function ($transaction) {
                return $transaction instanceof Transaction ? $transaction->date : $transaction->transaction_date;
            })
            ->values();

        $perPage = 30;
        $page = $request->input('page', 1);
        $paginatedTransactions = new \Illuminate\Pagination\LengthAwarePaginator(
            $allTransactions->forPage($page, $perPage),
            $allTransactions->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return Inertia::render('Transactions/GrandLivre', [
            'transactions' => $paginatedTransactions,
            'filters' => $request->all(['type', 'date_from', 'date_to', 'landlord_id', 'tenant_id', 'property_id']),
            'landlords' => $this->getLandlordsForUser($user),
            'tenants' => $this->getTenantsForUser($user),
            'properties' => $this->getPropertiesForUser($user),
        ]);
    }

    public function ventilation(Request $request)
    {
        Gate::authorize('viewAny', Transaction::class);

        $user = $request->user();
        $query = Transaction::query();
        $landlordQuery = LandlordTransaction::where('type', 'payout');
        $landlordScope = Landlord::query();
        $propertyScope = Property::query();

        if (!$user->hasRole('super_admin')) {
            if ($user->hasRole(['admin_entreprise', 'user_entreprise'])) {
                $companyId = $user->company_id;
                $query->whereHas('property', function ($q) use ($companyId) {
                    $q->where('company_id', $companyId);
                });
                $landlordQuery->whereHas('landlord', function ($q) use ($companyId) {
                    $q->where('company_id', $companyId);
                });
                $landlordScope->where('company_id', $companyId);
                $propertyScope->where('company_id', $companyId);
            } elseif ($user->hasRole('bailleur')) {
                $landlordId = $user->landlord_id;
                $query->where('landlord_id', $landlordId);
                $landlordQuery->where('landlord_id', $landlordId);
                $landlordScope->where('id', $landlordId);
                $propertyScope->where('landlord_id', $landlordId);
            }
        }

        // Appliquer les filtres de date
        $dateRange = $request->input('dateRange', 'all');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        if ($dateRange !== 'all' && $startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
            $landlordQuery->whereBetween('transaction_date', [$startDate, $endDate]);
        }

        $ventilationByLandlord = $landlordScope
            ->withSum(['landlordTransactions as transactions_sum_amount' => function ($query) use ($startDate, $endDate) {
                $query->where('type', 'payout');
                if ($startDate && $endDate) {
                    $query->whereBetween('transaction_date', [$startDate, $endDate]);
                }
            }], 'total_amount')
            ->withCount(['landlordTransactions as transactions_count' => function ($query) use ($startDate, $endDate) {
                $query->where('type', 'payout');
                if ($startDate && $endDate) {
                    $query->whereBetween('transaction_date', [$startDate, $endDate]);
                }
            }])
            ->paginate(10, ['*'], 'landlord_page');

        $ventilationByProperty = $propertyScope
            ->withSum(['transactions as transactions_sum_amount' => function ($query) use ($startDate, $endDate) {
                if ($startDate && $endDate) {
                    $query->whereBetween('date', [$startDate, $endDate]);
                }
            }], 'amount')
            ->withCount(['transactions' => function ($query) use ($startDate, $endDate) {
                if ($startDate && $endDate) {
                    $query->whereBetween('date', [$startDate, $endDate]);
                }
            }])
            ->paginate(10, ['*'], 'property_page');

        $ventilationByType = $query
            ->select('type', DB::raw('SUM(amount) as total_amount'), DB::raw('COUNT(*) as transaction_count'))
            ->groupBy('type')
            ->get();

        $landlordPayouts = $landlordQuery
            ->select(DB::raw("'LANDLORD_PAYOUT' as type"), DB::raw('SUM(total_amount) as total_amount'), DB::raw('COUNT(*) as transaction_count'))
            ->groupBy('type')
            ->get();

        $ventilationByType = $ventilationByType->concat($landlordPayouts);

        // Évolution mensuelle
        $monthlyData = $this->getMonthlyData($query, $landlordQuery, $startDate, $endDate);

        return Inertia::render('Transactions/Ventilation', [
            'ventilationByLandlord' => $ventilationByLandlord,
            'ventilationByProperty' => $ventilationByProperty,
            'ventilationByType' => $ventilationByType,
            'monthlyEvolution' => $monthlyData,
            'filters' => [
                'dateRange' => $dateRange,
                'startDate' => $startDate,
                'endDate' => $endDate,
            ],
        ]);
    }

    private function getLandlordsForUser($user)
    {
        if ($user->hasRole('super_admin')) {
            return Landlord::all();
        } elseif ($user->hasRole(['admin_entreprise', 'user_entreprise'])) {
            return Landlord::where('company_id', $user->company_id)->get();
        } elseif ($user->hasRole('bailleur')) {
            return Landlord::where('id', $user->landlord_id)->get();
        }
        return collect();
    }

    private function getTenantsForUser($user)
    {
        if ($user->hasRole('super_admin')) {
            return Tenant::all();
        } elseif ($user->hasRole(['admin_entreprise', 'user_entreprise'])) {
            return Tenant::whereHas('property', function ($query) use ($user) {
                $query->where('company_id', $user->company_id);
            })->get();
        } elseif ($user->hasRole('bailleur')) {
            return Tenant::whereHas('property', function ($query) use ($user) {
                $query->where('landlord_id', $user->landlord_id);
            })->get();
        }
        return collect();
    }

    private function getPropertiesForUser($user)
    {
        if ($user->hasRole('super_admin')) {
            return Property::all();
        } elseif ($user->hasRole(['admin_entreprise', 'user_entreprise'])) {
            return Property::where('company_id', $user->company_id)->get();
        } elseif ($user->hasRole('bailleur')) {
            return Property::where('landlord_id', $user->landlord_id)->get();
        }
        return collect();
    }

    private function getMonthlyData($transactionQuery, $landlordPayoutQuery, $startDate, $endDate)
    {
        $startDate = $startDate ? Carbon::parse($startDate) : Carbon::now()->startOfYear();
        $endDate = $endDate ? Carbon::parse($endDate) : Carbon::now();

        $months = [];
        $current = $startDate->copy()->startOfMonth();
        while ($current <= $endDate) {
            $months[] = $current->format('Y-m');
            $current->addMonth();
        }

        $monthlyTransactions = $transactionQuery->selectRaw('YEAR(date) as year, MONTH(date) as month, SUM(CASE WHEN amount >= 0 THEN amount ELSE 0 END) as revenue, SUM(CASE WHEN amount < 0 THEN ABS(amount) ELSE 0 END) as expense')
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy(DB::raw('YEAR(date)'), DB::raw('MONTH(date)'))
            ->get()
            ->keyBy(function ($item) {
                return Carbon::createFromDate($item->year, $item->month)->format('Y-m');
            });

        $landlordPayouts = $landlordPayoutQuery->selectRaw('YEAR(transaction_date) as year, MONTH(transaction_date) as month, SUM(total_amount) as payout')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->groupBy(DB::raw('YEAR(transaction_date)'), DB::raw('MONTH(transaction_date)'))
            ->get()
            ->keyBy(function ($item) {
                return Carbon::createFromDate($item->year, $item->month)->format('Y-m');
            });

        $monthlyData = [];
        foreach ($months as $month) {
            $transaction = $monthlyTransactions->get($month, null);
            $payout = $landlordPayouts->get($month, null);
            $monthlyData[] = [
                'date' => $month,
                'revenue' => $transaction ? $transaction->revenue : 0,
                'expense' => ($transaction ? $transaction->expense : 0) + ($payout ? $payout->payout : 0),
            ];
        }

        return $monthlyData;
    }

    public function export(Request $request)
    {
        Log::info('Début de la méthode export', ['request' => $request->all()]);

        try {
            $query = Transaction::with(['property', 'landlord', 'tenant']);
            $landlordQuery = LandlordTransaction::with('landlord');

            if ($request->filled('type')) {
                if ($request->type === 'LANDLORD_PAYOUT') {
                    $query->whereNull('id');
                    $landlordQuery->where('type', 'payout');
                } else {
                    $query->where('type', $request->type);
                    $landlordQuery->whereNull('id');
                }
            }

            if ($request->filled('date_from')) {
                $query->whereDate('date', '>=', $request->date_from);
                $landlordQuery->whereDate('transaction_date', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->whereDate('date', '<=', $request->date_to);
                $landlordQuery->whereDate('transaction_date', '<=', $request->date_to);
            }

            if ($request->filled('landlord_id')) {
                $query->where('landlord_id', $request->landlord_id);
                $landlordQuery->where('landlord_id', $request->landlord_id);
            }
            if ($request->filled('tenant_id')) {
                $query->where('tenant_id', $request->tenant_id);
            }
            if ($request->filled('property_id')) {
                $query->where('property_id', $request->property_id);
            }

            $transactions = $query->get();
            $landlordTransactions = $landlordQuery->get();

            $allTransactions = $transactions->concat($landlordTransactions)
                ->sortByDesc(function ($transaction) {
                    return $transaction instanceof Transaction ? $transaction->date : $transaction->transaction_date;
                })
                ->values();

            $exportData = $allTransactions->map(function ($transaction) {
                return [
                    'Date' => $transaction instanceof Transaction ? $transaction->date : $transaction->transaction_date,
                    'Type' => $transaction instanceof Transaction ? $transaction->type : 'LANDLORD_PAYOUT',
                    'Description' => $transaction instanceof Transaction ? $transaction->description : 'Paiement au bailleur',
                    'Montant' => $transaction instanceof Transaction ? $transaction->amount : -$transaction->total_amount,
                    'Bailleur' => $transaction->landlord ? $transaction->landlord->first_name . ' ' . $transaction->landlord->last_name : '-',
                    'Locataire' => $transaction instanceof Transaction && $transaction->tenant ? $transaction->tenant->first_name . ' ' . $transaction->tenant->last_name : '-',
                    'Propriété' => $transaction instanceof Transaction && $transaction->property ? $transaction->property->name : '-',
                ];
            });

            Log::info('Données d\'export générées', ['count' => $exportData->count(), 'sample' => $exportData->take(5)]);

            return response()->json($exportData);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'export', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Une erreur est survenue lors de l\'export des données'], 500);
        }
    }

}

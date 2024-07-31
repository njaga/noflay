<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\LandlordTransaction;
use App\Models\Landlord;
use App\Models\Property;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::query();

        // Filtrage
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        if ($request->has('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        // Tri
        $sortField = $request->input('sort_by', 'date');
        $sortDirection = $request->input('sort_direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $transactions = $query->paginate(15);

        return Inertia::render('Transactions/Index', [
            'transactions' => $transactions,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'type' => 'required|in:EXPENSE,PAYMENT,DEPOSIT,REFUND,LANDLORD_PAYOUT',
            'description' => 'nullable|string',
            'property_id' => 'nullable|exists:properties,id',
            'landlord_id' => 'nullable|exists:landlords,id',
            'tenant_id' => 'nullable|exists:tenants,id',
            'contract_id' => 'nullable|exists:contracts,id',
            'refund_reason' => 'required_if:type,REFUND|in:' . implode(',', array_keys(Transaction::REFUND_REASONS)),
        ]);

        $transaction = Transaction::create($validatedData);
        return response()->json($transaction, 201);
    }

    public function show(Transaction $transaction)
    {
        return Inertia::render('Transactions/Show', [
            'transaction' => $transaction->load(['property', 'landlord', 'tenant', 'contract']),
        ]);
    }

    public function update(Request $request, Transaction $transaction)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'type' => 'required|in:EXPENSE,PAYMENT,DEPOSIT,REFUND',
            'description' => 'nullable|string',
            'property_id' => 'nullable|exists:properties,id',
            'landlord_id' => 'nullable|exists:landlords,id',
            'tenant_id' => 'nullable|exists:tenants,id',
            'contract_id' => 'nullable|exists:contracts,id',
        ]);

        $transaction->update($validatedData);
        return response()->json($transaction);
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return response()->json(['message' => 'Transaction supprimée avec succès.']);
    }

    public function grandLivre(Request $request)
    {
        Log::info('Grand Livre request', $request->all());

        $query = Transaction::with(['property', 'landlord', 'tenant']);
        $landlordQuery = LandlordTransaction::with('landlord');

        if ($request->filled('type')) {
            if ($request->type === 'LANDLORD_PAYOUT') {
                $query->whereNull('id'); // Ne retourne aucune transaction normale
                $landlordQuery->where('type', 'payout');
            } else {
                $query->where('type', $request->type);
                $landlordQuery->whereNull('id'); // Ne retourne aucune transaction de bailleur
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

        $formattedTransactions = $paginatedTransactions->through(function ($transaction) {
            return [
                'id' => $transaction->id,
                'date' => $transaction instanceof Transaction ? $transaction->date : $transaction->transaction_date,
                'type' => $transaction instanceof Transaction ? $transaction->type : 'LANDLORD_PAYOUT',
                'description' => $transaction instanceof Transaction ? $transaction->description : 'Paiement au bailleur',
                'amount' => $transaction instanceof Transaction ? $transaction->amount : -$transaction->total_amount,
                'property' => $transaction instanceof Transaction ? $transaction->property : null,
                'landlord' => $transaction instanceof Transaction ? $transaction->landlord : $transaction->landlord,
                'tenant' => $transaction instanceof Transaction ? $transaction->tenant : null,
                'refund_reason' => $transaction instanceof Transaction ? $transaction->refund_reason : null,
            ];
        });

        Log::info('Formatted transactions', ['count' => $formattedTransactions->count()]);

        return Inertia::render('Transactions/GrandLivre', [
            'transactions' => $formattedTransactions,
            'filters' => $request->all(['type', 'date_from', 'date_to', 'landlord_id', 'tenant_id', 'property_id']),
            'landlords' => Landlord::all(),
            'tenants' => Tenant::all(),
            'properties' => Property::all(),
        ]);
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


    public function ventilation(Request $request)
    {
        $ventilationByLandlord = Landlord::withSum('transactions', 'amount')
            ->withCount('transactions')
            ->paginate(10, ['*'], 'landlord_page');

        $ventilationByProperty = Property::withSum('transactions', 'amount')
            ->withCount('transactions')
            ->paginate(10, ['*'], 'property_page');

        $ventilationByType = Transaction::select('type', DB::raw('SUM(amount) as total_amount'), DB::raw('COUNT(*) as transaction_count'))
            ->groupBy('type')
            ->get();

        $landlordPayouts = LandlordTransaction::where('type', 'payout')
            ->select(DB::raw("'LANDLORD_PAYOUT' as type"), DB::raw('SUM(total_amount) as total_amount'), DB::raw('COUNT(*) as transaction_count'))
            ->groupBy('type')
            ->get();

        $ventilationByType = $ventilationByType->concat($landlordPayouts);

        // Ajout de logs pour le débogage
        Log::info('Ventilation data:', [
            'landlords' => $ventilationByLandlord->toArray(),
            'properties' => $ventilationByProperty->toArray(),
            'types' => $ventilationByType->toArray()
        ]);

        return Inertia::render('Transactions/Ventilation', [
            'ventilationByLandlord' => $ventilationByLandlord,
            'ventilationByProperty' => $ventilationByProperty,
            'ventilationByType' => $ventilationByType,
        ]);
    }
}

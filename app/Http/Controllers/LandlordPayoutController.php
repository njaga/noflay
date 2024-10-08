<?php

namespace App\Http\Controllers;

use App\Models\Landlord;
use App\Models\LandlordTransaction;
use App\Models\Contract;
use App\Models\Expense;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Carbon\Carbon;
use PDF;


class LandlordPayoutController extends Controller
{
    const TVA_RATE = 0.18; // 18% TVA

    public function index()
    {
        $payouts = LandlordTransaction::with('landlord')->get();
        $landlords = Landlord::all();

        $totalAmount = $payouts->sum('amount');
        $thisMonthAmount = $payouts->filter(function ($payout) {
            return Carbon::parse($payout->transaction_date)->isCurrentMonth();
        })->sum('amount');
        $totalPayouts = $payouts->count();

        return Inertia::render('LandlordPayouts/Index', [
            'payouts' => $payouts,
            'landlords' => $landlords,
            'summaryStats' => [
                'totalAmount' => $totalAmount,
                'thisMonthAmount' => $thisMonthAmount,
                'totalPayouts' => $totalPayouts,
            ],
        ]);
    }

    public function create()
    {
        $landlords = Landlord::all();

        return Inertia::render('LandlordPayouts/Create', [
            'landlords' => $landlords
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'landlord_id' => 'required|exists:landlords,id',
                'payment_type' => 'required|in:selected_month,full_balance',
                'month' => 'required|date_format:Y-m',
                'payment_method' => 'required|in:bank,cash,cheque_cash',
                'cheque_number' => 'required_if:payment_method,bank,cheque_cash',
                'cheque_amount' => 'required_if:payment_method,cheque_cash|numeric|min:0',
                'cash_amount' => 'required_if:payment_method,cheque_cash|numeric|min:0',
                'payout_date' => 'required|date',
            ]);

            $landlord = Landlord::findOrFail($validatedData['landlord_id']);
            $selectedDate = Carbon::createFromFormat('Y-m', $validatedData['month']);

            $financialInfo = $this->calculateFinancialInfo($landlord, $selectedDate, $validatedData['payment_type']);

            $amount = $validatedData['payment_type'] === 'full_balance'
                ? $financialInfo['netBalance']
                : $financialInfo['monthlyRent'];

            // Calculer la TVA sur le montant total des loyers et des cautions
            $tva = ($financialInfo['monthlyRent']) * self::TVA_RATE;
            $amountWithTVA = $amount + $tva;

            if ($validatedData['payment_method'] === 'cheque_cash') {
                $totalAmount = $validatedData['cheque_amount'] + $validatedData['cash_amount'];
                if ($totalAmount !== $amountWithTVA) {
                    return back()->withErrors(['amount' => 'Le montant total du chèque et de la caisse doit être égal au montant du versement avec TVA.']);
                }
            }

            // Créer la transaction de versement
            LandlordTransaction::create([
                'landlord_id' => $landlord->id,
                'type' => 'payout',
                'amount' => $amount,
                'tva_amount' => $tva,
                'total_amount' => $amountWithTVA,
                'commission_amount' => $financialInfo['totalCommissions'],
                'net_amount' => $amountWithTVA - $financialInfo['totalCommissions'],
                'transaction_date' => now(),
                'payout_date' => $validatedData['payout_date'],
                'payment_method' => $validatedData['payment_method'],
                'cheque_number' => $validatedData['cheque_number'] ?? null,
                'cheque_amount' => $validatedData['cheque_amount'] ?? null,
                'cash_amount' => $validatedData['cash_amount'] ?? null,
                'status' => 'Completed',
                'month' => $validatedData['month'],
            ]);

            // Mettre à jour le solde du bailleur
            $landlord->balance -= $amountWithTVA;
            $landlord->save();

            // Mettre à jour les paiements et les contrats pour indiquer qu'ils ont été reversés
            if ($validatedData['payment_type'] === 'full_balance') {
                Payment::whereHas('contract.property', function ($query) use ($landlord) {
                    $query->where('landlord_id', $landlord->id);
                })->where('is_reversed', false)
                    ->update(['is_reversed' => true]);

                Contract::where('landlord_id', $landlord->id)
                    ->where('is_reversed', false)
                    ->update(['is_reversed' => true]);
            } else {
                Payment::whereHas('contract.property', function ($query) use ($landlord) {
                    $query->where('landlord_id', $landlord->id);
                })->where('is_reversed', false)
                    ->whereYear('payment_date', $selectedDate->year)
                    ->whereMonth('payment_date', $selectedDate->month)
                    ->update(['is_reversed' => true]);
            }

            // Mettre à jour les dépenses pour indiquer qu'elles ont été remboursées
            Expense::whereHas('property', function ($query) use ($landlord) {
                $query->where('landlord_id', $landlord->id);
            })->where('is_repay', false)
                ->whereYear('expense_date', $selectedDate->year)
                ->whereMonth('expense_date', $selectedDate->month)
                ->update(['is_repay' => true]);

            // Mettre à jour les cautions pour indiquer qu'elles ont été reversées uniquement si c'est "full_balance"
            if ($validatedData['payment_type'] === 'full_balance') {
                Contract::whereHas('property', function ($query) use ($landlord) {
                    $query->where('landlord_id', $landlord->id);
                })->where('is_reversed', false)
                    ->update(['is_reversed' => true]);
            }

            return redirect()->route('landlord-payouts.index')->with('success', 'Versement créé avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du versement: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Une erreur est survenue lors de la création du versement.'])->withInput();
        }
    }

    public function show($id)
    {
        $payout = LandlordTransaction::with('landlord')->findOrFail($id);

        return Inertia::render('LandlordPayouts/Show', [
            'payout' => $payout
        ]);
    }

    public function edit($id)
    {
        $payout = LandlordTransaction::findOrFail($id);
        $landlords = Landlord::all();

        return Inertia::render('LandlordPayouts/Edit', [
            'payout' => $payout,
            'landlords' => $landlords
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'landlord_id' => 'required|exists:landlords,id',
                'payment_type' => 'required|in:selected_month,full_balance',
                'month' => 'required|date_format:Y-m',
                'payment_method' => 'required|in:bank,cash,cheque_cash',
                'cheque_number' => 'required_if:payment_method,bank,cheque_cash',
                'cheque_amount' => 'required_if:payment_method,cheque_cash|numeric|min:0',
                'cash_amount' => 'required_if:payment_method,cheque_cash|numeric|min:0',
                'payout_date' => 'required|date',
            ]);

            $landlord = Landlord::findOrFail($validatedData['landlord_id']);
            $selectedDate = Carbon::createFromFormat('Y-m', $validatedData['month']);

            $financialInfo = $this->calculateFinancialInfo($landlord, $selectedDate, $validatedData['payment_type']);

            $amount = $validatedData['payment_type'] === 'full_balance'
                ? $financialInfo['netBalance']
                : $financialInfo['monthlyRent'];

            // Calculer la TVA sur le montant total des loyers et des cautions
            $tva = ($financialInfo['monthlyRent']) * self::TVA_RATE;
            $amountWithTVA = $amount + $tva;

            if ($validatedData['payment_method'] === 'cheque_cash') {
                $totalAmount = $validatedData['cheque_amount'] + $validatedData['cash_amount'];
                if ($totalAmount !== $amountWithTVA) {
                    return back()->withErrors(['amount' => 'Le montant total du chèque et de la caisse doit être égal au montant du versement avec TVA.']);
                }
            }

            // Mettre à jour la transaction de versement
            $payout = LandlordTransaction::findOrFail($id);
            $oldAmountWithTVA = $payout->total_amount; // Garder l'ancien montant pour ajuster le solde
            $payout->update([
                'landlord_id' => $landlord->id,
                'amount' => $amount,
                'tva_amount' => $tva,
                'total_amount' => $amountWithTVA,
                'commission_amount' => $financialInfo['totalCommissions'],
                'net_amount' => $amountWithTVA - $financialInfo['totalCommissions'],
                'transaction_date' => now(),
                'payout_date' => $validatedData['payout_date'],
                'payment_method' => $validatedData['payment_method'],
                'cheque_number' => $validatedData['cheque_number'] ?? null,
                'cheque_amount' => $validatedData['cheque_amount'] ?? null,
                'cash_amount' => $validatedData['cash_amount'] ?? null,
                'status' => 'Completed',
                'month' => $validatedData['month'],
            ]);

            // Ajuster le solde du bailleur
            $landlord->balance += $oldAmountWithTVA; // Remboursement de l'ancien montant
            $landlord->balance -= $amountWithTVA; // Déduction du nouveau montant
            $landlord->save();

            return redirect()->route('landlord-payouts.index')->with('success', 'Versement mis à jour avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du versement: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Une erreur est survenue lors de la mise à jour du versement.'])->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $payout = LandlordTransaction::findOrFail($id);
            // Remboursement du montant au bailleur
            $landlord = Landlord::findOrFail($payout->landlord_id);
            $landlord->balance += $payout->total_amount;
            $landlord->save();

            // Remboursement des paiements et des contrats concernés
            if ($payout->payment_type === 'full_balance') {
                // Mise à jour des paiements pour indiquer qu'ils ne sont plus reversés
                Payment::whereHas('contract.property', function ($query) use ($landlord) {
                    $query->where('landlord_id', $landlord->id);
                })->update(['is_reversed' => false]);

                // Mise à jour des contrats pour indiquer que la caution n'est plus reversée
                Contract::whereHas('property', function ($query) use ($landlord) {
                    $query->where('landlord_id', $landlord->id);
                })->update(['is_reversed' => false]);
            } else {
                $selectedDate = Carbon::createFromFormat('Y-m', $payout->month);
                // Mise à jour des paiements pour indiquer qu'ils ne sont plus reversés
                Payment::whereHas('contract.property', function ($query) use ($landlord) {
                    $query->where('landlord_id', $landlord->id);
                })->whereYear('payment_date', $selectedDate->year)
                  ->whereMonth('payment_date', $selectedDate->month)
                  ->update(['is_reversed' => false]);

                // Mise à jour des contrats pour le mois spécifique
                Contract::whereHas('property', function ($query) use ($landlord) {
                    $query->where('landlord_id', $landlord->id);
                })->whereYear('start_date', '<=', $selectedDate->year)
                  ->whereMonth('start_date', '<=', $selectedDate->month)
                  ->whereYear('end_date', '>=', $selectedDate->year)
                  ->whereMonth('end_date', '>=', $selectedDate->month)
                  ->update(['is_reversed' => false]);
            }

            // Mise à jour des dépenses pour indiquer qu'elles ne sont plus remboursées
            Expense::whereHas('property', function ($query) use ($landlord) {
                $query->where('landlord_id', $landlord->id);
            })->update(['is_repay' => false]);

            // Supprimer le versement
            $payout->delete();
            return redirect()->route('landlord-payouts.index')->with('success', 'Versement supprimé avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du versement: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Une erreur est survenue lors de la suppression du versement.']);
        }
    }

    public function getLandlordDetails($id)
    {
        $landlord = Landlord::findOrFail($id);
        $currentDate = Carbon::now();

        return response()->json($this->calculateFinancialInfo($landlord, $currentDate, 'full_balance'));
    }

    private function calculateFinancialInfo(Landlord $landlord, Carbon $date, $paymentType)
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

        $commissions = [
            'selected_month' => $rentCommission,
            'full_balance' => $rentCommission + $cautionCommission
        ];

        $tvaRates = [
            'selected_month' => $monthlyRent * self::TVA_RATE,
            'full_balance' => ($monthlyRent + $commissions['full_balance']) * self::TVA_RATE
        ];

        $totalTva = $tvaRates[$paymentType];
        $totalCommissions = $commissions[$paymentType];

        $netBalance = $paymentType === 'full_balance'
            ? ($monthlyRent + $totalCautionAmount - $monthlyExpenses - $totalCommissions - $totalTva)
            : ($monthlyRent - $monthlyExpenses - $rentCommission - $totalTva);

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
        ];
    }
    
    public function generateReceipt($id)
    {
        try {
            $transaction = LandlordTransaction::with('landlord.company')->findOrFail($id);

            $data = [
                'transaction_id' => $transaction->id,
                'payout_date' => $transaction->payout_date ? Carbon::parse($transaction->payout_date)->format('d/m/Y') : 'N/A',
                'landlord_name' => $transaction->landlord ? $transaction->landlord->first_name . ' ' . $transaction->landlord->last_name : 'N/A',
                'landlord_address' => $transaction->landlord ? $transaction->landlord->address : 'N/A',
                'amount' => number_format($transaction->amount, 2, ',', ' '),
                'tva_amount' => number_format($transaction->tva_amount, 2, ',', ' '),
                'commission_amount' => number_format($transaction->commission_amount, 2, ',', ' '),
                'net_amount' => number_format($transaction->net_amount, 2, ',', ' '),
                'payment_method' => $transaction->payment_method ?? 'N/A',
                'cheque_number' => $transaction->cheque_number ?? 'N/A',
                'cheque_amount' => $transaction->cheque_amount ? number_format($transaction->cheque_amount, 2, ',', ' ') : 'N/A',
                'cash_amount' => $transaction->cash_amount ? number_format($transaction->cash_amount, 2, ',', ' ') : 'N/A',
                'company_name' => $transaction->landlord && $transaction->landlord->company ? $transaction->landlord->company->name : 'N/A',
                'company_address' => $transaction->landlord && $transaction->landlord->company ? $transaction->landlord->company->address : 'N/A',
            ];

            $pdf = PDF::loadView('receipts.landlord-payout', $data);

            return $pdf->download('recu_versement_' . $transaction->id . '.pdf');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la génération du reçu', [
                'transaction_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['error' => 'Une erreur est survenue lors de la génération du reçu'], 500);
        }
    }

}

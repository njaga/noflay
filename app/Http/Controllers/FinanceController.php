<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Payment;
use App\Models\Expense;
use App\Models\Property;
use App\Models\Tenant;
use App\Models\Company;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        $company_id = Auth::user()->company_id;

        $contracts = Contract::where('company_id', $company_id)->with('tenant', 'property')->get();
        $payments = Payment::whereHas('contract', function ($query) use ($company_id) {
            $query->where('company_id', $company_id);
        })->with('contract.tenant', 'contract.property')->get();
        $expenses = Expense::where('company_id', $company_id)->with('property')->get();

        $totalCautions = $contracts->sum('caution_amount');
        $totalCommissions = $contracts->sum('commission_amount');
        $totalRent = $payments->sum('amount');
        $totalExpenses = $expenses->sum('amount');

        $currentMonthPayments = $payments->whereBetween('payment_date', [now()->startOfMonth(), now()->endOfMonth()])->sum('amount');
        $previousMonthPayments = $payments->whereBetween('payment_date', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])->sum('amount');
        $paymentsDifference = $currentMonthPayments - $previousMonthPayments;

        // Récupération des données pour le Grand Livre Caisse
        $cashes = Transaction::where('company_id', $company_id)
            ->when($request->date_from, function ($query, $date) {
                return $query->where('date', '>=', $date);
            })
            ->when($request->date_to, function ($query, $date) {
                return $query->where('date', '<=', $date);
            })
            ->orderBy('date', 'desc')
            ->get();

        // Récupération des données pour la Ventilation Caisse
        $ventilations = Transaction::where('company_id', $company_id)
            ->when($request->date_from, function ($query, $date) {
                return $query->where('date', '>=', $date);
            })
            ->when($request->date_to, function ($query, $date) {
                return $query->where('date', '<=', $date);
            })
            ->selectRaw('type, SUM(amount) as total_amount, COUNT(*) as transaction_count')
            ->groupBy('type')
            ->get();


        return Inertia::render('Finance/Index', [
            'contracts' => $contracts,
            'payments' => $payments,
            'expenses' => $expenses,
            'tenants' => Tenant::where('company_id', $company_id)->get(),
            'properties' => Property::where('company_id', $company_id)->get(),
            'totalCautions' => $totalCautions,
            'totalCommissions' => $totalCommissions,
            'totalRent' => $totalRent,
            'totalPayments' => $totalRent, // To be consistent with variable name in the Vue component
            'totalExpenses' => $totalExpenses,
            'currentMonthPayments' => $currentMonthPayments,
            'previousMonthPayments' => $previousMonthPayments,
            'paymentsDifference' => $paymentsDifference,
            'cashes' => $cashes,
            'ventilations' => $ventilations,
        ]);
    }
}

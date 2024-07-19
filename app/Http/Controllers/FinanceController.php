<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Payment;
use App\Models\Expense;
use App\Models\Property;
use App\Models\Tenant;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class FinanceController extends Controller
{
    public function index()
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
        ]);
    }
}

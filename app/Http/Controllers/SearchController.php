<?php

namespace App\Http\Controllers;

use App\Models\Landlord;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Property;
use App\Models\Expense;
use App\Models\LandlordPayout;
use App\Models\LandlordTransaction;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');
        $companyId = Auth::user()->company_id;

        // Recherche sur plusieurs modèles
        $users = User::where('company_id', $companyId)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%");
            })
            ->get();

        $tenants = Tenant::where('company_id', $companyId)
            ->where(function ($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                    ->orWhere('last_name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%")
                    ->orWhere('phone_number', 'like', "%{$query}%");
            })
            ->get();

        $landlords = Landlord::where('company_id', $companyId)
            ->where(function ($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                    ->orWhere('last_name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%")
                    ->orWhere('phone', 'like', "%{$query}%");
            })
            ->get();

        $properties = Property::where('company_id', $companyId)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('address', 'like', "%{$query}%");
            })
            ->get();


            $transactions = LandlordTransaction::where(function($q) use ($query) {
                $q->where('amount', 'like', "%{$query}%")
                  ->orWhere('transaction_date', 'like', "%{$query}%");
            })
            ->get();

        $expenses = Expense::where('company_id', $companyId)
            ->where(function ($q) use ($query) {
                $q->where('description', 'like', "%{$query}%")
                    ->orWhere('expense_date', 'like', "%{$query}%")
                    ->orWhere('amount', 'like', "%{$query}%");
            })
            ->get();

        $payments = Payment::where('company_id', $companyId)
            ->where(function ($q) use ($query) {
                $q->where('amount', 'like', "%{$query}%")
                    ->orWhere('payment_date', 'like', "%{$query}%");
            })
            ->get();


        $contracts = Contract::where('company_id', $companyId)
            ->where(function ($q) use ($query) {
                $q->where('rent_amount', 'like', "%{$query}%")
                    ->orwhere('caution_amount', 'like', "%{$query}%")
                    ->orWhere('commission_amount', 'like', "%{$query}%")
                    ->orWhere('file_number', 'like', "%{$query}%")
                    ->orWhere('start_date', 'like', "%{$query}%")
                    ->orWhere('end_date', 'like', "%{$query}%");
            })
            ->get();

        return Inertia::render('SearchResults', [
            'query' => $query,
            'users' => $users,
            'tenants' => $tenants,
            'landlords' => $landlords,
            'properties' => $properties,
            'expenses' => $expenses,
            'payments' => $payments,
            'contracts' => $contracts,
        ]);
    }
}

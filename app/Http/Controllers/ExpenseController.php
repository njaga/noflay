<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Property;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with('property')->get();

        return Inertia::render('Expenses/Index', [
            'expenses' => $expenses,
            'properties' => Property::all(),
        ]);
    }

    public function create()
    {
        $properties = Property::where('company_id', Auth::user()->company_id)->get();
        return Inertia::render('Expenses/Create', [
            'properties' => $properties,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'type' => 'required|string|in:maintenance,frais judiciaires,taxes,Assurance,other',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
        ]);

        $expense = Expense::create([
            'company_id' => Auth::user()->company_id,
            'property_id' => $request->property_id,
            'type' => $request->type,
            'description' => $request->description,
            'amount' => $request->amount,
            'expense_date' => $request->expense_date,
        ]);

        // Créer une transaction correspondante
        Transaction::create([
            'company_id' => Auth::user()->company_id,
            'expense_id' => $expense->id,
            'date' => $expense->expense_date,
            'amount' => -$expense->amount, // Montant négatif pour une dépense
            'type' => 'EXPENSE',
            'description' => $expense->description,
            'property_id' => $expense->property_id,
            'landlord_id' => $expense->property->landlord_id,
        ]);

        return redirect()->route('expenses.index')->with('success', 'Dépense créée avec succès.');
    }

    public function show(Expense $expense)
    {
        Gate :: authorize('view', $expense);
        $expense->load('property');
        return Inertia::render('Expenses/Show', [
            'expense' => $expense,
        ]);
    }

    public function edit(Expense $expense)
    {
        Gate :: authorize('update', $expense);
        $properties = Property::where('company_id', Auth::user()->company_id)->get();
        return Inertia::render('Expenses/Edit', [
            'expense' => $expense,
            'properties' => $properties,
        ]);
    }

    public function update(Request $request, Expense $expense)
    {
        Gate :: authorize('update', $expense);

        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'type' => 'required|string|in:maintenance,frais judiciaires,taxes,Assurance,other',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
        ]);

        $expense->update([
            'property_id' => $request->property_id,
            'type' => $request->type,
            'description' => $request->description,
            'amount' => $request->amount,
            'expense_date' => $request->expense_date,
        ]);

        return redirect()->route('expenses.index')->with('success', 'Dépense mise à jour avec succès.');
    }


    public function destroy(Expense $expense)
    {
        Gate :: authorize('delete', $expense);
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Dépense supprimée avec succès.');
    }
}

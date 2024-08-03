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
        Gate::authorize('viewAny', Expense::class);

        $expenses = Expense::with('property')->get();

        return Inertia::render('Expenses/Index', [
            'expenses' => $expenses,
            'properties' => Property::all(),
            'expenseTypes' => Expense::VALID_TYPES,
        ]);
    }

    public function create()
    {
        Gate::authorize('create', Expense::class);

        $properties = Property::where('company_id', Auth::user()->company_id)->get();
        return Inertia::render('Expenses/Create', [
            'properties' => $properties,
            'expenseTypes' => Expense::VALID_TYPES,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'type' => 'required|string|in:' . implode(',', Expense::VALID_TYPES),
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
        ]);

        $property = Property::findOrFail($validatedData['property_id']);

        Gate::authorize('create', [Expense::class, $property]);

        $expense = Expense::create([
            'company_id' => Auth::user()->company_id,
            'property_id' => $validatedData['property_id'],
            'type' => $validatedData['type'],
            'description' => $validatedData['description'],
            'amount' => $validatedData['amount'],
            'expense_date' => $validatedData['expense_date'],
        ]);

        Transaction::create([
            'company_id' => Auth::user()->company_id,
            'expense_id' => $expense->id,
            'date' => $expense->expense_date,
            'amount' => -$expense->amount,
            'type' => 'EXPENSE',
            'description' => $expense->description,
            'property_id' => $expense->property_id,
            'landlord_id' => $property->landlord_id,
        ]);

        return redirect()->back()->with('success', 'Dépense créée avec succès.');
    }

    public function show(Expense $expense)
    {
        Gate::authorize('view', $expense);

        $expense->load('property');
        return Inertia::render('Expenses/Show', [
            'expense' => $expense,
            'expenseTypes' => Expense::VALID_TYPES,
        ]);
    }

    public function edit(Expense $expense)
    {
        Gate::authorize('update', $expense);

        $properties = Property::where('company_id', Auth::user()->company_id)->get();
        return Inertia::render('Expenses/Edit', [
            'expense' => $expense,
            'properties' => $properties,
            'expenseTypes' => Expense::VALID_TYPES,
        ]);
    }

    public function update(Request $request, Expense $expense)
    {
        Gate::authorize('update', $expense);

        $validatedData = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'type' => 'required|string|in:' . implode(',', Expense::VALID_TYPES),
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
        ]);

        $expense->update($validatedData);

        // Mettre à jour la transaction correspondante
        $transaction = Transaction::where('expense_id', $expense->id)->first();
        if ($transaction) {
            $transaction->update([
                'date' => $expense->expense_date,
                'amount' => -$expense->amount,
                'description' => $expense->description,
                'property_id' => $expense->property_id,
                'landlord_id' => $expense->property->landlord_id,
            ]);
        }

        return redirect()->route('expenses.index')->with('success', 'Dépense mise à jour avec succès.');
    }

    public function destroy(Expense $expense)
    {
        Gate::authorize('delete', $expense);

        // Supprimer la transaction correspondante
        Transaction::where('expense_id', $expense->id)->delete();

        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Dépense supprimée avec succès.');
    }
}

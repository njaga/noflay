<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
        ]);

        Expense::create([
            'company_id' => Auth::user()->company_id,
            'property_id' => $request->property_id,
            'description' => $request->description,
            'amount' => $request->amount,
            'expense_date' => $request->expense_date,
        ]);

        return redirect()->route('expenses.index')->with('success', 'Dépense créée avec succès.');
    }

    public function show(Expense $expense)
    {
        $this->authorize('view', $expense);
        $expense->load('property');
        return Inertia::render('Expenses/Show', [
            'expense' => $expense,
        ]);
    }

    public function edit(Expense $expense)
    {
        $this->authorize('update', $expense);
        $properties = Property::where('company_id', Auth::user()->company_id)->get();
        return Inertia::render('Expenses/Edit', [
            'expense' => $expense,
            'properties' => $properties,
        ]);
    }

    public function update(Request $request, Expense $expense)
    {
        $this->authorize('update', $expense);

        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
        ]);

        $expense->update([
            'property_id' => $request->property_id,
            'description' => $request->description,
            'amount' => $request->amount,
            'expense_date' => $request->expense_date,
        ]);

        return redirect()->route('expenses.index')->with('success', 'Dépense mise à jour avec succès.');
    }

    public function destroy(Expense $expense)
    {
        $this->authorize('delete', $expense);
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Dépense supprimée avec succès.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class CompanyController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Company::class);

        $companies = Company::all();

        return Inertia::render('Companies/Index', [
            'companies' => $companies,
        ]);
    }

    public function create()
    {
        Gate::authorize('create', Company::class);
        return Inertia::render(
            'Companies/Create',
        );
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Company::class);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:companies',
            'address' => 'required|string|max:255',
        ]);

        Company::create([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
        ]);

        return redirect()->route('companies.index')->with('success', 'Company created successfully.');
    }

    public function show(Company $company)
    {
        Gate::authorize('view', $company);

        return Inertia::render('Companies/Show', [
            'company' => $company,
        ]);
    }

    public function edit(Company $company)
    {
        Gate::authorize('update', $company);

        return Inertia::render('Companies/Edit', [
            'company' => $company,
        ]);
    }

    public function update(Request $request, Company $company)
    {
        Gate::authorize('update', $company);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:companies,email,' . $company->id,
            'address' => 'required|string|max:255',
        ]);

        $company->update([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
        ]);

        return redirect()->route('companies.index')->with('success', 'Company updated successfully.');
    }

    public function destroy(Company $company)
    {
        Gate::authorize('delete', $company);

        $company->delete();

        return redirect()->route('companies.index')->with('success', 'Company deleted successfully.');
    }

    public function toggleStatus(Company $company)
    {
        $company->is_active = !$company->is_active;
        $company->save();

        return redirect()->route('companies.index')->with('success', 'Company status updated successfully.');
    }
}

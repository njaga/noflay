<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

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
        $users = User::all();
        return Inertia::render('Companies/Create', [
            'users' => $users,
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Company::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:companies',
            'address' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|max:1024', // max 1MB
            'NINEA' => 'nullable|string|max:255',
            'RCCM' => 'nullable|string|max:255',
            'representant_id' => 'nullable|exists:users,id',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('company_logos', 'public');
            $validated['logo'] = $path;
        }

        $company = Company::create($validated);

        return redirect()->route('companies.index')->with('flash', [
            'success' => true,
            'message' => 'Entreprise créée avec succès.',
            'company' => $company,
        ]);
    }

    public function show(Company $company)
    {
        $company->load('users.roles', 'landlords', 'tenants', 'properties', 'representant');

        return Inertia::render('Companies/Show', [
            'company' => $company,
        ]);
    }

    public function edit(Company $company)
    {
        Gate::authorize('update', $company);
        $users = User::all();

        return Inertia::render('Companies/Edit', [
            'company' => $company,
            'users' => $users,
        ]);
    }

    public function update(Request $request, Company $company)
    {
        Gate::authorize('update', $company);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:companies,email,' . $company->id,
            'address' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|max:1024', // max 1MB
            'NINEA' => 'nullable|string|max:255',
            'RCCM' => 'nullable|string|max:255',
            'representant_id' => 'nullable|exists:users,id',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($company->logo) {
                Storage::disk('public')->delete($company->logo);
            }
            $path = $request->file('logo')->store('company_logos', 'public');
            $validated['logo'] = $path;
        }

        $company->update($validated);

        return redirect()->route('companies.index')->with('success', 'Entreprise mise à jour avec succès.');
    }

    public function destroy(Company $company)
    {
        Gate::authorize('delete', $company);

        // Delete logo
        if ($company->logo) {
            Storage::disk('public')->delete($company->logo);
        }

        $company->delete();

        return redirect()->route('companies.index')->with('success', 'Entreprise supprimée avec succès.');
    }

    public function toggleStatus(Company $company)
    {
        $company->is_active = !$company->is_active;
        $company->save();

        return redirect()->route('companies.index')->with('success', 'Statut de l\'entreprise mis à jour avec succès.');
    }

    public function getUsers()
    {
        // This method will be used to fetch users for the dropdown
        $users = User::where('id_company', auth()->user()->id_company)
            ->select('id', 'name')
            ->get();

        return response()->json($users);
    }
}

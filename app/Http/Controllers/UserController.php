<?php

// UserController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', User::class);

        $query = User::with('roles', 'company');

        if (Gate::denies('viewSuperAdmin', Auth::user())) {
            $query->whereDoesntHave('roles', function ($q) {
                $q->where('name', 'super_admin');
            });
        }

        if (Gate::denies('isSuperAdmin', Auth::user())) {
            if (Gate::allows('isAdminEntreprise', Auth::user()) || Gate::allows('isUserEntreprise', Auth::user())) {
                $query->where('company_id', Auth::user()->company_id);
            }
        }

        $users = $query->get();

        return Inertia::render('Users/Index', [
            'users' => $users,
            'auth' => [
                'user' => Auth::user(),
                'roles' => Auth::user()->roles->pluck('name')
            ],
        ]);
    }

    public function create()
    {
        Gate::authorize('create', User::class);

        $companies = Gate::allows('viewAny', Company::class) ? Company::all() : [Auth::user()->company];

        return Inertia::render('Users/Create', [
            'companies' => $companies,
            'roles' => Role::all()->pluck('name'),
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('create', User::class);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'company_id' => 'required|integer|exists:companies,id',
            'role' => 'required|string|exists:roles,name',
        ]);

        // Ensure admin_entreprise cannot create super_admin
        if (Gate::denies('isSuperAdmin', Auth::user()) && $validatedData['role'] === 'super_admin') {
            return redirect()->route('users.index')->with('error', 'Unauthorized to create super admin.');
        }

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'company_id' => Auth::user()->hasRole('super_admin') ? $validatedData['company_id'] : Auth::user()->company_id,
            'is_active' => true,
        ]);

        // Manually insert the role with model_type
        DB::table('model_has_roles')->insert([
            'role_id' => Role::where('name', $validatedData['role'])->first()->id,
            'model_type' => User::class,
            'model_id' => $user->id,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        Gate::authorize('view', $user);

        return Inertia::render('Users/Show', [
            'user' => $user->load('company', 'roles'),
            'roles' => Role::all()->pluck('name'),
        ]);
    }

    public function edit(User $user)
    {
        Gate::authorize('update', $user);

        $companies = Gate::allows('viewAny', Company::class) ? Company::all() : [Auth::user()->company];

        return Inertia::render('Users/Edit', [
            'user' => $user->load('company', 'roles'),
            'companies' => $companies,
            'roles' => Role::all()->pluck('name'),
        ]);
    }

    public function update(Request $request, User $user)
    {
        Gate::authorize('update', $user);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'company_id' => 'required|integer|exists:companies,id',
            'role' => 'required|string|exists:roles,name',
        ]);

        $user->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'company_id' => Auth::user()->hasRole('super_admin') ? $validatedData['company_id'] : Auth::user()->company_id,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($validatedData['password'])]);
        }

        // Manually update the role with model_type
        DB::table('model_has_roles')->where('model_id', $user->id)->delete();

        DB::table('model_has_roles')->insert([
            'role_id' => Role::where('name', $validatedData['role'])->first()->id,
            'model_type' => User::class,
            'model_id' => $user->id,
        ]);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        Gate::authorize('delete', $user);

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    public function toggleStatus(User $user)
    {
        Gate::authorize('toggleStatus', $user);

        $user->is_active = !$user->is_active;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Statut de l\'utilisateur mis à jour avec succès.',
            'user' => $user->load('roles')
        ]);
    }
}

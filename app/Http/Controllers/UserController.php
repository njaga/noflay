<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', User::class);

        $users = $this->getUsersBasedOnRole(Auth::user());

        return Inertia::render('Users/Index', [
            'users' => $users,
        ]);
    }

    public function create()
    {
        Gate::authorize('create', User::class);

        return Inertia::render('Users/Create', [
            'companies' => $this->getCompaniesBasedOnRole(Auth::user()),
            'roles' => Role::all()->pluck('name'),
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('create', User::class);

        $validatedData = $this->validateUserData($request);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'company_id' => $validatedData['company_id'],
            'is_active' => true,
        ]);

        $user->assignRole($validatedData['role']);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        Gate::authorize('view', $user);

        return Inertia::render('Users/Show', [
            'user' => $user->load('company', 'roles'),
        ]);
    }

    public function edit(User $user)
    {
        Gate::authorize('update', $user);

        return Inertia::render('Users/Edit', [
            'user' => $user,
            'companies' => $this->getCompaniesBasedOnRole(Auth::user()),
            'roles' => Role::all()->pluck('name'),
        ]);
    }

    public function update(Request $request, User $user)
    {
        Gate::authorize('update', $user);

        $validatedData = $this->validateUserData($request, $user->id);

        $user->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'company_id' => $validatedData['company_id'],
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($validatedData['password'])]);
        }

        $user->syncRoles($validatedData['role']);

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
        Gate::authorize('update', $user);

        $user->is_active = !$user->is_active;
        $user->save();

        return redirect()->route('users.index')->with('success', 'User status updated successfully.');
    }

    private function getUsersBasedOnRole($user)
    {
        if ($user->hasRole('super_admin')) {
            return User::with('company')->get();
        } elseif ($user->hasRole('admin_entreprise')) {
            return User::where('company_id', $user->company_id)
                ->whereHas('roles', function ($query) {
                    $query->where('name', '!=', 'super_admin');
                })
                ->with('company')
                ->get();
        }
        return [];
    }

    private function getCompaniesBasedOnRole($user)
    {
        if ($user->hasRole('super_admin')) {
            return Company::all();
        } elseif ($user->hasRole('admin_entreprise')) {
            return [$user->company];
        }
        return [];
    }

    private function validateUserData(Request $request, $userId = null)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $userId,
            'role' => 'required|string|exists:roles,name',
            'company_id' => 'required_if:role,admin_entreprise,user_entreprise,bailleur,locataire',
        ];

        if (!$userId || $request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        return $request->validate($rules);
    }
}

<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Tenant;
use Illuminate\Auth\Access\HandlesAuthorization;

class TenantPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true; // Tous les utilisateurs peuvent voir la liste des locataires
    }

    public function view(User $user, Tenant $tenant)
    {
        return $user->hasRole('super_admin') || $user->company_id === $tenant->company_id;
    }

    public function create(User $user)
    {
        return true; // Tous les utilisateurs peuvent créer des locataires
    }

    public function update(User $user, Tenant $tenant)
    {
        return $user->hasRole('super_admin') || $user->company_id === $tenant->company_id;
    }

    public function delete(User $user, Tenant $tenant)
    {
        return $user->hasRole('super_admin') || $user->hasRole('admin_entreprise');
    }

    public function restore(User $user, Tenant $tenant)
    {
        return $user->hasRole('super_admin') || $user->hasRole('admin_entreprise');
    }

    public function forceDelete(User $user, Tenant $tenant)
    {
        return $user->hasRole('super_admin') || $user->hasRole('admin_entreprise');
    }
}

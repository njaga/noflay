<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Contract;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContractPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasRole('super_admin') || $user->hasRole('admin_entreprise') || $user->hasRole('user_entreprise');
    }

    public function view(User $user, Contract $contract)
    {
        return $user->hasRole('super_admin') || $user->company_id === $contract->company_id;
    }

    public function create(User $user)
    {
        return $user->hasRole('super_admin') || $user->hasRole('admin_entreprise') || $user->hasRole('user_entreprise');
    }

    public function update(User $user, Contract $contract)
    {
        return $user->hasRole('super_admin') || $user->company_id === $contract->company_id;
    }

    public function delete(User $user, Contract $contract)
    {
        return $user->hasRole('super_admin') || $user->company_id === $contract->company_id;
    }

    public function restore(User $user, Contract $contract)
    {
        return $user->hasRole('super_admin') || ($user->company_id === $contract->tenant->company_id);
    }

    public function forceDelete(User $user, Contract $contract)
    {
        return $user->hasRole('super_admin') || ($user->company_id === $contract->tenant->company_id);
    }
}

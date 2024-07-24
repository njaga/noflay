<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Landlord;
use Illuminate\Auth\Access\HandlesAuthorization;

class LandlordPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasRole('super_admin') || $user->hasRole('admin_entreprise') || $user->hasRole('user_entreprise');
    }

    public function view(User $user, Landlord $landlord)
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }
        return $user->company_id === $landlord->company_id &&
            ($user->hasRole('admin_entreprise') || $user->hasRole('user_entreprise'));
    }

    public function create(User $user)
    {
        return $user->hasRole('super_admin') || $user->hasRole('admin_entreprise') || $user->hasRole('user_entreprise');
    }

    public function update(User $user, Landlord $landlord)
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }
        return $user->hasRole('admin_entreprise') && $user->company_id === $landlord->company_id;
    }

    public function delete(User $user, Landlord $landlord)
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }
        return $user->hasRole('admin_entreprise') && $user->company_id === $landlord->company_id;
    }

    public function viewLandlordName(User $user, Landlord $landlord)
    {
        return $user->hasRole('tenant') && $user->company_id === $landlord->company_id;
    }

    public function restore(User $user, Landlord $landlord)
    {
        return $user->hasRole('super_admin') || $user->hasRole('admin_entreprise');
    }

    public function forceDelete(User $user, Landlord $landlord)
    {
        return $user->hasRole('super_admin') || $user->hasRole('admin_entreprise');
    }
}

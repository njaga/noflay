<?php

namespace App\Policies;

use App\Models\User;
use App\Models\LandlordTransaction;
use Illuminate\Auth\Access\HandlesAuthorization;

class LandlordTransactionPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasAnyRole(['super_admin', 'admin_entreprise', 'user_entreprise', 'bailleur']);
    }

    public function view(User $user, LandlordTransaction $landlordTransaction)
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }

        if ($user->hasRole(['admin_entreprise', 'user_entreprise'])) {
            return $landlordTransaction->landlord->company_id === $user->company_id;
        }

        if ($user->hasRole('bailleur')) {
            return $landlordTransaction->landlord_id === $user->landlord_id;
        }

        return false;
    }
}

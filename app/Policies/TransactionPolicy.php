<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransactionPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasAnyRole(['super_admin', 'admin_entreprise', 'user_entreprise', 'bailleur']);
    }

    public function view(User $user, Transaction $transaction)
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }

        if ($user->hasRole(['admin_entreprise', 'user_entreprise'])) {
            return $transaction->property->company_id === $user->company_id;
        }

        if ($user->hasRole('bailleur')) {
            return $transaction->landlord_id === $user->landlord_id;
        }

        return false;
    }
}

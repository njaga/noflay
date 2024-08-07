<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Expense;
use App\Models\Property;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExpensePolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }
    }

    public function viewAny(User $user)
    {
        return $user->hasAnyRole(['admin_entreprise', 'user_entreprise', 'bailleur', 'locataire']);
    }

    public function view(User $user, Expense $expense)
    {
        if ($user->hasRole('admin_entreprise') || $user->hasRole('user_entreprise')) {
            return $user->company_id === $expense->company_id;
        }

        if ($user->hasRole('bailleur')) {
            return $user->landlord_id === $expense->property->landlord_id;
        }

        if ($user->hasRole('locataire')) {
            return $user->tenant->properties->contains($expense->property_id);
        }

        return false;
    }

    public function create(User $user, ?Property $property = null)
    {
        if ($user->hasRole('admin_entreprise') || $user->hasRole('user_entreprise')) {
            if ($property) {
                return $user->company_id === $property->company_id;
            }
            return true; // Allow creation if no specific property is provided
        }

        return false;
    }

    public function update(User $user, Expense $expense)
    {
        return $user->hasRole('admin_entreprise') && $user->company_id === $expense->company_id;
    }

    public function delete(User $user, Expense $expense)
    {
        return $user->hasRole('admin_entreprise') && $user->company_id === $expense->company_id;
    }
}

<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Landlord;
use Illuminate\Auth\Access\HandlesAuthorization;

class LandlordPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any landlords.
     */
    public function viewAny(User $user)
    {
        // Exemple : tous les utilisateurs authentifiés peuvent voir les bailleurs
        return $user->isAuthenticated();
    }

    /**
     * Determine whether the user can view the landlord.
     */
    public function view(User $user, Landlord $landlord)
    {
        // Exemple : peut voir un bailleur si c'est dans la même entreprise
        return $user->company_id === $landlord->company_id;
    }

    /**
     * Determine whether the user can create landlords.
     */
    public function create(User $user)
    {
        // Seul un admin entreprise ou un super_admin peut créer des bailleurs
        return in_array($user->role, ['super_admin', 'admin_entreprise']);
    }

    /**
     * Determine whether the user can update the landlord.
     */
    public function update(User $user, Landlord $landlord)
    {
        // Peut mettre à jour si dans la même entreprise et pas un simple utilisateur
        return $user->company_id === $landlord->company_id && $user->role !== 'user_entreprise';
    }

    /**
     * Determine whether the user can delete the landlord.
     */
    public function delete(User $user, Landlord $landlord)
    {
        // Seul un super_admin peut supprimer des bailleurs
        return $user->role === 'super_admin';
    }
}

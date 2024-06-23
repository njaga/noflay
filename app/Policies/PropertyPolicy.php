<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Property;
use Illuminate\Auth\Access\HandlesAuthorization;

class PropertyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any properties.
     */
    public function viewAny(User $user)
    {
        // Tous les utilisateurs authentifiés peuvent voir les propriétés
        return $user->isAuthenticated();
    }

    /**
     * Determine whether the user can view the property.
     */
    public function view(User $user, Property $property)
    {
        // Peut voir si dans la même entreprise
        return $user->company_id === $property->company_id;
    }

    /**
     * Determine whether the user can create properties.
     */
    public function create(User $user)
    {
        // Seuls les utilisateurs avec rôle admin ou super_admin peuvent créer des propriétés
        return in_array($user->role, ['super_admin', 'admin_entreprise']);
    }

    /**
     * Determine whether the user can update the property.
     */
    public function update(User $user, Property $property)
    {
        // Peut mettre à jour si admin et dans la même entreprise
        return $user->role !== 'user_entreprise' && $user->company_id === $property->company_id;
    }

    /**
     * Determine whether the user can delete the property.
     */
    public function delete(User $user, Property $property)
    {
        // Seul un super_admin peut supprimer des propriétés
        return $user->role === 'super_admin';
    }
}

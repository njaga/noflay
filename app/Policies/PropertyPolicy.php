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
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasRole('super_admin') || $user->hasRole('admin_entreprise') || $user->hasRole('user_entreprise');
    }

    /**
     * Determine whether the user can view the property.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Property  $property
     * @return mixed
     */
    public function view(User $user, Property $property)
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->company_id === $property->company_id;
    }

    /**
     * Determine whether the user can create properties.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasRole('super_admin') || $user->hasRole('admin_entreprise') || $user->hasRole('user_entreprise');
    }

    /**
     * Determine whether the user can update the property.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Property  $property
     * @return mixed
     */
    public function update(User $user, Property $property)
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->hasRole('admin_entreprise') && $user->company_id === $property->company_id;
    }

    /**
     * Determine whether the user can delete the property.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Property  $property
     * @return mixed
     */
    public function delete(User $user, Property $property)
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->hasRole('admin_entreprise') && $user->company_id === $property->company_id;
    }

    /**
     * Determine whether the user can restore the property.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Property  $property
     * @return mixed
     */
    public function restore(User $user, Property $property)
    {
        return $this->delete($user, $property);
    }

    /**
     * Determine whether the user can permanently delete the property.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Property  $property
     * @return mixed
     */
    public function forceDelete(User $user, Property $property)
    {
        return $this->delete($user, $property);
    }
}

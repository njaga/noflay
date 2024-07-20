<?php

namespace App\Policies;

use App\Models\LandlordPayout;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LandlordPayoutPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any landlord payouts.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasRole('admin_entreprise') || $user->hasRole('super_admin');
    }

    /**
     * Determine whether the user can view the landlord payout.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LandlordPayout  $landlordPayout
     * @return mixed
     */
    public function view(User $user, LandlordPayout $landlordPayout)
    {
        return $user->hasRole('admin_entreprise') || $user->hasRole('super_admin');
    }

    /**
     * Determine whether the user can create landlord payouts.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasRole('admin_entreprise') || $user->hasRole('super_admin');
    }

    /**
     * Determine whether the user can update the landlord payout.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LandlordPayout  $landlordPayout
     * @return mixed
     */
    public function update(User $user, LandlordPayout $landlordPayout)
    {
        return $user->hasRole('admin_entreprise') || $user->hasRole('super_admin');
    }

    /**
     * Determine whether the user can delete the landlord payout.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LandlordPayout  $landlordPayout
     * @return mixed
     */
    public function delete(User $user, LandlordPayout $landlordPayout)
    {
        return $user->hasRole('admin_entreprise') || $user->hasRole('super_admin');
    }

    /**
     * Determine whether the user can restore the landlord payout.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LandlordPayout  $landlordPayout
     * @return mixed
     */
    public function restore(User $user, LandlordPayout $landlordPayout)
    {
        return $user->hasRole('admin_entreprise') || $user->hasRole('super_admin');
    }

    /**
     * Determine whether the user can permanently delete the landlord payout.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LandlordPayout  $landlordPayout
     * @return mixed
     */
    public function forceDelete(User $user, LandlordPayout $landlordPayout)
    {
        return $user->hasRole('admin_entreprise') || $user->hasRole('super_admin');
    }
}

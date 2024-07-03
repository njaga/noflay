<?php

// UserPolicy.php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $this->isSuperAdmin($user) || $this->isAdminEntreprise($user);
    }

    public function view(User $user, User $model)
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        return $this->isAdminEntreprise($user) && $user->company_id === $model->company_id;
    }

    public function create(User $user)
    {
        return $this->isSuperAdmin($user) || ($this->isAdminEntreprise($user) && !$this->isSuperAdmin($user));
    }

    public function update(User $user, User $model)
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        return $this->isAdminEntreprise($user) && $user->company_id === $model->company_id && !$this->isSuperAdmin($model);
    }

    public function delete(User $user, User $model)
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        return $this->isAdminEntreprise($user) && $user->company_id === $model->company_id && !$this->isSuperAdmin($model);
    }

    public function toggleStatus(User $user, User $model)
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        return $this->isAdminEntreprise($user) && $user->company_id === $model->company_id && !$this->isSuperAdmin($model);
    }

    public function isSuperAdmin(User $user)
    {
        return $user->roles->contains('name', 'super_admin');
    }

    public function isAdminEntreprise(User $user)
    {
        return $user->roles->contains('name', 'admin_entreprise');
    }

    public function isUserEntreprise(User $user)
    {
        return $user->roles->contains('name', 'user_entreprise');
    }
}

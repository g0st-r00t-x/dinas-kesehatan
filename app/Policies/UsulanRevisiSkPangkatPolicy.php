<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UsulanRevisiSkPangkat;
use Illuminate\Auth\Access\HandlesAuthorization;

class UsulanRevisiSkPangkatPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_usulan::revisi::sk::pangkat');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, UsulanRevisiSkPangkat $usulanRevisiSkPangkat): bool
    {
        return $user->can('view_usulan::revisi::sk::pangkat');
    }
    public function viewOwn(User $user, UsulanRevisiSkPangkat $usulanRevisiSkPangkat): bool
    {
        return $user->can('view_own_usulan::revisi::sk::pangkat');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_usulan::revisi::sk::pangkat');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UsulanRevisiSkPangkat $usulanRevisiSkPangkat): bool
    {
        return $user->can('update_usulan::revisi::sk::pangkat');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UsulanRevisiSkPangkat $usulanRevisiSkPangkat): bool
    {
        return $user->can('delete_usulan::revisi::sk::pangkat');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_usulan::revisi::sk::pangkat');
    }

    /**
     * Determine whether the user can permanently delete.
     */
}

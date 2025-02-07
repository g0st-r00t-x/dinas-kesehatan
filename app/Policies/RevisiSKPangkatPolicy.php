<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UsulanRevisiSkPangkat;
use Illuminate\Auth\Access\HandlesAuthorization;

class RevisiSKPangkatPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_revisiskpangkat');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, UsulanRevisiSkPangkat $revisiSKPangkat): bool
    {
        return $user->can('view_revisiskpangkat');
    }

    public function viewOwn(User $user, UsulanRevisiSkPangkat $revisiSKPangkat): bool
    {
        return $user->can('view_own_revisiskpangkat');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_revisiskpangkat');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UsulanRevisiSkPangkat $revisiSKPangkat): bool
    {
        return $user->can('update_revisiskpangkat');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UsulanRevisiSkPangkat $revisiSKPangkat): bool
    {
        return $user->can('delete_revisiskpangkat');
    }

    /**
     * Determine whether the user can bulk delete models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_revisiskpangkat');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
}

<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UsulanPermohonanCuti;
use Illuminate\Auth\Access\HandlesAuthorization;

class UsulanPermohonanCutiPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_usulan::permohonan::cuti');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, UsulanPermohonanCuti $usulanPermohonanCuti): bool
    {
        return $user->can('view_usulan::permohonan::cuti');
    }

    public function viewOwn(User $user, UsulanPermohonanCuti $usulanPermohonanCuti): bool
    {
        return $user->can('view_own_usulan::permohonan::cuti');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_usulan::permohonan::cuti');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UsulanPermohonanCuti $usulanPermohonanCuti): bool
    {
        return $user->can('update_usulan::permohonan::cuti');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UsulanPermohonanCuti $usulanPermohonanCuti): bool
    {
        return $user->can('delete_usulan::permohonan::cuti');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_usulan::permohonan::cuti');
    }

    /**
     * Determine whether the user can permanently delete.
     */
}

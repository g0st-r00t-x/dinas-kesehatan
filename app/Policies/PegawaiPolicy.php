<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Pegawai;
use Illuminate\Auth\Access\HandlesAuthorization;

class PegawaiPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_pegawai');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Pegawai $pegawai): bool
    {
        return $user->can('view_pegawai');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_pegawai');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Pegawai $pegawai): bool
    {
        return $user->can('update_pegawai');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Pegawai $pegawai): bool
    {
        return $user->can('delete_pegawai');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_pegawai');
    }

    /**
     * Determine whether the user can permanently delete.
     */
}

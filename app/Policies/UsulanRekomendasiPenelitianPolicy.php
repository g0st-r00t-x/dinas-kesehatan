<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UsulanRekomendasiPenelitian;
use Illuminate\Auth\Access\HandlesAuthorization;

class UsulanRekomendasiPenelitianPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_usulan::rekomendasi::penelitian');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, UsulanRekomendasiPenelitian $usulanRekomendasiPenelitian): bool
    {
        return $user->can('view_usulan::rekomendasi::penelitian');
    }
    /**
     * Determine whether the user can view own data.
     */
    public function viewOwn(User $user, UsulanRekomendasiPenelitian $usulanRekomendasiPenelitian): bool
    {
        return $user->can('view_own_usulan::rekomendasi::penelitian');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_usulan::rekomendasi::penelitian');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UsulanRekomendasiPenelitian $usulanRekomendasiPenelitian): bool
    {
        return $user->can('update_usulan::rekomendasi::penelitian');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UsulanRekomendasiPenelitian $usulanRekomendasiPenelitian): bool
    {
        return $user->can('delete_usulan::rekomendasi::penelitian');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_usulan::rekomendasi::penelitian');
    }
}

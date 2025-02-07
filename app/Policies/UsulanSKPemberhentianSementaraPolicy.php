<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UsulanSKPemberhentianSementara;
use Illuminate\Auth\Access\HandlesAuthorization;

class UsulanSKPemberhentianSementaraPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_usulan::s::k::pemberhentian::sementara');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, UsulanSKPemberhentianSementara $usulanSKPemberhentianSementara): bool
    {
        return $user->can('view_usulan::s::k::pemberhentian::sementara');
    }
    public function viewOwn(User $user, UsulanSKPemberhentianSementara $usulanSKPemberhentianSementara): bool
    {
        return $user->can('view_own_usulan::s::k::pemberhentian::sementara');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_usulan::s::k::pemberhentian::sementara');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UsulanSKPemberhentianSementara $usulanSKPemberhentianSementara): bool
    {
        return $user->can('update_usulan::s::k::pemberhentian::sementara');
    }
    /**
     * Determine whether the user can update the model.
     */
    public function downloadFile(User $user, UsulanSKPemberhentianSementara $usulanSKPemberhentianSementara): bool
    {
        return $user->can('download_file_usulan::s::k::pemberhentian::sementara');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UsulanSKPemberhentianSementara $usulanSKPemberhentianSementara): bool
    {
        return $user->can('delete_usulan::s::k::pemberhentian::sementara');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_usulan::s::k::pemberhentian::sementara');
    }

    /**
     * Determine whether the user can permanently delete.
     */
}

<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ArsipSurat;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArsipSuratPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_arsip::surat');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ArsipSurat $arsipSurat): bool
    {
        return $user->can('view_arsip::surat');
    }

    public function kirimNotif(User $user, ArsipSurat $arsipSurat): bool
    {
        return $user->can('kirim_notif_arsip::surat');
    }
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_arsip::surat');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ArsipSurat $arsipSurat): bool
    {
        return $user->can('update_arsip::surat');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ArsipSurat $arsipSurat): bool
    {
        return $user->can('delete_arsip::surat');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_arsip::surat');
    }

    /**
     * Determine whether the user can permanently delete.
     */
}

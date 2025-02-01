<?php

namespace App\Policies;

use App\Models\User;
use App\Models\InventarisAJJ;
use Illuminate\Auth\Access\HandlesAuthorization;

class InventarisAJJPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_pengajuan::a::j::j');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, InventarisAJJ $inventarisAJJ): bool
{
    Log::info('View Policy Check:', [
        'user_id' => $user->id,
        'inventaris_user_id' => $inventarisAJJ->user_id,
        'has_view_any' => $user->can('view_any_pengajuan::a::j::j'),
        'has_view_own' => $user->can('view_own_pengajuan::a::j::j'),
        'matches_user' => $user->id === $inventarisAJJ->user_id
    ]);

    // Jika user memiliki view_any, izinkan mereka melihat semua data
    if ($user->can('view_any_pengajuan::a::j::j')) {
        return true;
    }

    // Jika user memiliki view_own, hanya izinkan melihat data miliknya
    if ($user->can('view_own_pengajuan::a::j::j')) {
        return $user->id === $inventarisAJJ->user_id;
    }

    return false;
}

    public function viewOwn(User $user, InventarisAJJ $inventarisAJJ): bool
    {
        return $user->can('view_own_pengajuan::a::j::j');
    }

    public function kirimNotif(User $user, InventarisAJJ $inventarisAJJ): bool
    {
        return $user->can('kirim_notif_pengajuan::a::j::j');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_pengajuan::a::j::j');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, InventarisAJJ $inventarisAJJ): bool
    {
        return $user->can('update_pengajuan::a::j::j');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, InventarisAJJ $inventarisAJJ): bool
    {
        return $user->can('delete_pengajuan::a::j::j');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_pengajuan::a::j::j');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, InventarisAJJ $inventarisAJJ): bool
    {
        return $user->can('{{ ForceDelete }}');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('{{ ForceDeleteAny }}');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, InventarisAJJ $inventarisAJJ): bool
    {
        return $user->can('{{ Restore }}');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('{{ RestoreAny }}');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, InventarisAJJ $inventarisAJJ): bool
    {
        return $user->can('{{ Replicate }}');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('{{ Reorder }}');
    }
}

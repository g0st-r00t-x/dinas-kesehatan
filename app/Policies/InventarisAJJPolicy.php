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
        return $user->can('view_pengajuan::a::j::j');
    }

    public function viewOwn(User $user, InventarisAJJ $inventarisAJJ): bool
    {
        return $user->can('view_own_pengajuan::a::j::j');
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
}

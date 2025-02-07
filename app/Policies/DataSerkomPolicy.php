<?php

namespace App\Policies;

use App\Models\User;
use App\Models\DataSerkom;
use Illuminate\Auth\Access\HandlesAuthorization;

class DataSerkomPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_data::serkom');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DataSerkom $dataSerkom): bool
    {
        return $user->can('view_data::serkom');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_data::serkom');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DataSerkom $dataSerkom): bool
    {
        return $user->can('update_data::serkom');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DataSerkom $dataSerkom): bool
    {
        return $user->can('delete_data::serkom');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_data::serkom');
    }

    /**
     * Determine whether the user can permanently delete.
     */
}

<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny( $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view( $user, Task $task): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create( $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update( $user, Task $task): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete( $user, Task $task): bool
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore( $user, Task $task): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete( $user, Task $task): bool
    {
        return true;
    }
}

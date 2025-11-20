<?php

namespace App\Policies;

use App\Models\Expense;
use App\Models\User;

class ExpensePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny($user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view($user, Expense $expense): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create($user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update($user, Expense $expense): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete($user, Expense $expense): bool
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore($user, Expense $expense): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete($user, Expense $expense): bool
    {
        return true;
    }
}

<?php

namespace App\Policies;

use App\Models\OperatorRequest;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OperatorRequestPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, OperatorRequest $operatorRequest): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'user' || $user->role === 'admin';
    }

    /**
     * Determine whether the user can approve an operator request.
     */
    public function approve(User $user, OperatorRequest $operatorRequest): bool
    {
        return $user->role === 'admin' && $operatorRequest->status === 'pending';
    }

    /**
     * Determine whether the user can reject an operator request.
     */
    public function reject(User $user, OperatorRequest $operatorRequest): bool
    {
        return $user->role === 'admin' && $operatorRequest->status === 'pending';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, OperatorRequest $operatorRequest): bool
    {
        return false; // Operator requests are not directly updatable by users after submission
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, OperatorRequest $operatorRequest): bool
    {
        return false; // Operator requests are not directly deletable by users
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, OperatorRequest $operatorRequest): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, OperatorRequest $operatorRequest): bool
    {
        return false;
    }
}

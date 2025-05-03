<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user is an Aluno.
     */
    public function isAluno(User $user): bool
    {
        return $user->role === 'Aluno';
    }

    /**
     * Determine if the user is a Cradt (Admin).
     */
    public function isCradt(User $user): bool
    {
        return $user->role === 'Cradt';
    }

    /**
     * Determine if the user is a Manager (novo).
     */
    public function isManager(User $user): bool
    {
        return $user->role === 'Manager';
    }
}

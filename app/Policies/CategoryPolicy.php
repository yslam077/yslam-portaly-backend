<?php

namespace App\Policies;

use App\Models\User;

class CategoryPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->role === 'admin') {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->isStaff();
    }

    public function create(User $user): bool
    {
        return $user->hasRole('editor');
    }

    public function update(User $user): bool
    {
        return $user->hasRole('editor');
    }

    public function delete(User $user): bool
    {
        return $user->hasRole('editor');
    }
}
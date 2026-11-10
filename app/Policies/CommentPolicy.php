<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
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
        return in_array($user->role, ['admin', 'editor', 'moderator']);
    }

    public function manage(User $user, Comment $comment): bool
    {
        return in_array($user->role, ['admin', 'editor', 'moderator']);
    }
}
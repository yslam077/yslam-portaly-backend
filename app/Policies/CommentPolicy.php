<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('comments.manage');
    }

    public function manage(User $user, Comment $comment): bool
    {
        return $user->hasPermission('comments.manage');
    }
}
<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;

class ArticlePolicy
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
        return in_array($user->role, ['admin', 'editor', 'author']);
    }

    public function update(User $user, Article $article): bool
    {
        if ($user->role === 'editor') {
            return true;
        }
        return $user->role === 'author' && $article->user_id === $user->id;
    }

    public function delete(User $user, Article $article): bool
    {
        if ($user->role === 'editor') {
            return true;
        }
        return $user->role === 'author' && $article->user_id === $user->id;
    }

    public function publish(User $user, Article $article): bool
    {
        return $user->role === 'editor';
    }
}
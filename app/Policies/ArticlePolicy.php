<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;

class ArticlePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('articles.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('articles.create');
    }

    public function update(User $user, Article $article): bool
    {
        return $user->hasPermission('articles.update')
            || ($user->hasPermission('articles.update.own') && $article->user_id === $user->id);
    }

    public function delete(User $user, Article $article): bool
    {
        return $user->hasPermission('articles.delete')
            || ($user->hasPermission('articles.delete.own') && $article->user_id === $user->id);
    }

    public function publish(User $user): bool
    {
        return $user->hasPermission('articles.publish');
    }
}
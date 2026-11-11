<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $canArticles = $user->hasPermission('articles.view');
        $seesAllArticles = $user->hasPermission('articles.update');
        $canComments = $user->hasPermission('comments.manage');

        // Автор видит статистику только по своим новостям
        $articles = fn () => Article::query()
            ->when(! $seesAllArticles, fn ($q) => $q->where('user_id', $user->id));

        $stats = [
            'articles_count' => $canArticles ? $articles()->count() : 0,
            'views_total' => $canArticles ? (int) $articles()->sum('views') : 0,
            'categories_count' => Category::count(),
            'comments_count' => $canComments ? Comment::count() : 0,
            'pending_comments' => $canComments ? Comment::where('status', 'pending')->count() : 0,
            'users_count' => $user->hasRole('admin') ? User::count() : 0,
        ];

        $latest_articles = $canArticles
            ? $articles()->with('category', 'user')->latest()->limit(5)->get()
            : collect();

        $latest_comments = $canComments
            ? Comment::with('user', 'article')->latest()->limit(5)->get()
            : collect();

        return view('admin.dashboard', compact('stats', 'latest_articles', 'latest_comments'));
    }
}
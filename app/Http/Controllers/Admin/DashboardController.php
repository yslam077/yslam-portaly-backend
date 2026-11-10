<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'articles_count' => Article::count(),
            'categories_count' => Category::count(),
            'comments_count' => Comment::count(),
            'users_count' => User::count(),
            'pending_comments' => Comment::where('status', 'pending')->count(),
        ];

        $latest_articles = Article::with('category', 'user')->latest('published_at')->limit(5)->get();
        $latest_comments = Comment::with('user', 'article')->latest()->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'latest_articles', 'latest_comments'));
    }
}
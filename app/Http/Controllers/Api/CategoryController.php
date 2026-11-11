<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleListResource;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $locale = app()->getLocale();
        $categories = Cache::remember("categories_list_{$locale}", now()->addMinutes(10), function () {
            return Category::orderBy('sort_order')->get();
        });

        return CategoryResource::collection($categories);
    }

    public function articles(Request $request, string $slug): AnonymousResourceCollection
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $limit = min(max((int) $request->input('limit', 12), 1), 20);

        $articles = $category->articles()
            ->published()
            ->with(['category', 'user'])
            ->latest('published_at')
            ->paginate($limit);

        return ArticleListResource::collection($articles);
    }

    public function popularArticles(Request $request, string $slug): AnonymousResourceCollection
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $limit = min(max((int) $request->input('limit', 12), 1), 20);

        $articles = $category->articles()
            ->published()
            ->with(['category', 'user'])
            ->orderByDesc('views')
            ->paginate($limit);

        return ArticleListResource::collection($articles);
    }
}
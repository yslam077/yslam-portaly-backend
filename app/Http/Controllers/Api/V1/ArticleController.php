<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleDetailResource;
use App\Http\Resources\ArticleListResource;
use App\Http\Resources\CommentResource;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class ArticleController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $limit = min(max((int) $request->input('limit', 12), 1), 20);
        $locale = app()->getLocale();

        $query = Article::published()->with(['category', 'user']);

        // Фильтр по категории (slug)
        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->input('category'));
            });
        }

        // Фильтр по тегу (slug)
        if ($request->has('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('slug', $request->input('tag'));
            });
        }

        // Фильтр по избранному
        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        // Поиск по заголовку или контенту с использованием PostgreSQL ILIKE и JSONB
        if ($search = $request->input('search')) {
            $escapedSearch = '%' . addcslashes($search, '%_\\') . '%';
            $query->where(function ($q) use ($locale, $escapedSearch) {
                $q->whereRaw("title->>? ILIKE ?", [$locale, $escapedSearch])
                  ->orWhereRaw("content->>? ILIKE ?", [$locale, $escapedSearch]);
            });
        }

        // Сортировка
        $sort = $request->input('sort', 'latest');
        if ($sort === 'popular') {
            $query->orderByDesc('views');
        } else {
            $query->orderByDesc('published_at');
        }

        return ArticleListResource::collection($query->paginate($limit));
    }

    public function popular(Request $request): AnonymousResourceCollection
    {
        $locale = app()->getLocale();
        $cacheKey = "articles_popular_{$locale}";

        $articles = Cache::remember($cacheKey, now()->addMinutes(10), function () {
            return Article::published()
                ->with(['category', 'user'])
                ->orderByDesc('views')
                ->limit(6)
                ->get();
        });

        return ArticleListResource::collection($articles);
    }

    public function trending(Request $request): AnonymousResourceCollection
    {
        $locale = app()->getLocale();
        $cacheKey = "articles_trending_{$locale}";

        $articles = Cache::remember($cacheKey, now()->addMinutes(10), function () {
            return Article::published()
                ->with(['category', 'user'])
                ->withCount(['comments' => function ($query) {
                    $query->approved()->where('created_at', '>=', now()->subDays(30));
                }])
                ->orderByDesc('is_featured')
                ->orderByDesc('comments_count')
                ->orderByDesc('views')
                ->limit(6)
                ->get();
        });

        return ArticleListResource::collection($articles);
    }

    public function featured(Request $request): ArticleDetailResource|JsonResponse
    {
        $locale = app()->getLocale();
        $cacheKey = "articles_featured_{$locale}";

        $article = Cache::remember($cacheKey, now()->addMinutes(10), function () {
            return Article::published()
                ->with(['category', 'user', 'tags'])
                ->where('is_featured', true)
                ->latest('published_at')
                ->first() 
                ?? Article::published()->with(['category', 'user', 'tags'])->latest('published_at')->first();
        });

        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        return new ArticleDetailResource($article);
    }

    public function show(string $slug): ArticleDetailResource
    {
        $article = Article::published()
            ->with(['category', 'user', 'tags'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Увеличиваем просмотры атомарно
        $article->increment('views');

        // Ищем 4 похожие статьи из той же категории
        $relatedArticles = Article::published()
            ->where('category_id', $article->category_id)
            ->where('id', '!=', $article->id)
            ->latest('published_at')
            ->limit(4)
            ->get();

        $article->setRelation('related_articles', $relatedArticles);

        return new ArticleDetailResource($article);
    }

    public function comments(string $slug): AnonymousResourceCollection
    {
        $article = Article::published()->where('slug', $slug)->firstOrFail();
        $comments = $article->comments()->approved()->with('user')->latest()->paginate(10);

        return CommentResource::collection($comments);
    }

    public function storeComment(Request $request, string $slug): JsonResponse
    {
        $article = Article::published()->where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:1000'],
        ]);

        $comment = $article->comments()->create([
            'user_id' => $request->user()->id,
            'body' => $validated['body'],
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => __('messages.comment_submitted'),
            'data' => new CommentResource($comment->load('user')),
        ], 201);
    }
}
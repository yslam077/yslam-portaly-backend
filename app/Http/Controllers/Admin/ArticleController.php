<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Services\ImageUploadService;
use App\Support\PublicCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ArticleController extends Controller
{
    public function __construct(protected ImageUploadService $imageUploadService) {}

    public function index(Request $request)
    {
        Gate::authorize('viewAny', Article::class);

        $user = $request->user();
        $query = Article::with(['category', 'user']);

        // Автор видит только свои новости
        if (! $user->hasPermission('articles.update')) {
            $query->where('user_id', $user->id);
        }

        if ($search = trim((string) $request->input('search'))) {
            $like = '%' . str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $search) . '%';
            $query->where(function ($q) use ($like) {
                foreach (['tm', 'ru', 'en'] as $locale) {
                    $q->orWhereRaw('(articles.title::jsonb ->> ?) ILIKE ?', [$locale, $like]);
                }
            });
        }

        $articles = $query->latest()->paginate(15)->withQueryString();

        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        Gate::authorize('create', Article::class);

        return view('admin.articles.create', [
            'categories' => Category::orderBy('sort_order')->get(),
            'tags' => Tag::all(),
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Article::class);

        $validated = $request->validate($this->rules());
        $tags = $validated['tags'] ?? [];
        unset($validated['tags'], $validated['image']);

        $data = $this->prepare($request, $validated);
        $data['user_id'] = $request->user()->id;

        if ($request->hasFile('image')) {
            $data['image'] = $this->imageUploadService->upload($request->file('image'), 'articles');
        }

        $article = Article::create($data);
        $article->tags()->sync($tags);

        PublicCache::flush();

        return redirect()->route('admin.articles.index')->with('success', 'Новость создана.');
    }

    public function edit(Article $article)
    {
        Gate::authorize('update', $article);

        return view('admin.articles.edit', [
            'article' => $article,
            'categories' => Category::orderBy('sort_order')->get(),
            'tags' => Tag::all(),
        ]);
    }

    public function update(Request $request, Article $article)
    {
        Gate::authorize('update', $article);

        $validated = $request->validate($this->rules());
        $tags = $validated['tags'] ?? [];
        unset($validated['tags'], $validated['image']);

        $data = $this->prepare($request, $validated, $article);

        if ($request->hasFile('image')) {
            $this->imageUploadService->delete($article->image);
            $data['image'] = $this->imageUploadService->upload($request->file('image'), 'articles');
        }

        $article->update($data);
        $article->tags()->sync($tags);

        PublicCache::flush();

        return redirect()->route('admin.articles.index')->with('success', 'Новость обновлена.');
    }

    public function destroy(Article $article)
    {
        Gate::authorize('delete', $article);

        // Картинку не удаляем: новость удаляется мягко и может быть восстановлена
        $article->delete();

        PublicCache::flush();

        return redirect()->route('admin.articles.index')->with('success', 'Новость удалена.');
    }

    protected function rules(): array
    {
        return [
            'title' => ['required', 'array'],
            'title.tm' => ['required', 'string', 'max:255'],
            'title.ru' => ['required', 'string', 'max:255'],
            'title.en' => ['nullable', 'string', 'max:255'],
            'content' => ['required', 'array'],
            'content.tm' => ['required', 'string'],
            'content.ru' => ['required', 'string'],
            'content.en' => ['nullable', 'string'],
            'excerpt' => ['nullable', 'array'],
            'excerpt.tm' => ['nullable', 'string', 'max:500'],
            'excerpt.ru' => ['nullable', 'string', 'max:500'],
            'excerpt.en' => ['nullable', 'string', 'max:500'],
            'category_id' => ['required', 'exists:categories,id'],
            'image' => ['nullable', 'image', 'max:2048'],
            'status' => ['required', 'in:draft,pending,published'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:tags,id'],
        ];
    }

    protected function prepare(Request $request, array $data, ?Article $article = null): array
    {
        foreach (['title', 'content', 'excerpt'] as $field) {
            $data[$field] = array_filter($data[$field] ?? [], fn ($v) => $v !== null && $v !== '');
        }

        $canPublish = $request->user()->can('publish', Article::class);

        if (! $canPublish) {
            // Автор не может публиковать
            if ($article && $article->status === 'published') {
                $data['status'] = 'published';
            } elseif ($data['status'] === 'published') {
                $data['status'] = 'pending';
            }
        } else {
            $data['is_featured'] = $request->boolean('is_featured');
        }

        if ($data['status'] === 'published') {
            if (! $article || ! $article->published_at) {
                $data['published_at'] = now();
            }
        } elseif (! $article) {
            $data['published_at'] = null;
        }

        return $data;
    }
}
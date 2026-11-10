<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function __construct(protected ImageUploadService $imageUploadService) {}

    public function index(Request $request)
    {
        $query = Article::with(['category', 'user']);

        if ($search = $request->input('search')) {
            $query->where('title->tm', 'ILIKE', "%{$search}%")
                  ->orWhere('title->ru', 'ILIKE', "%{$search}%");
        }

        $articles = $query->latest()->paginate(15);
        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.articles.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'array'],
            'title.tm' => ['required', 'string', 'max:255'],
            'title.ru' => ['required', 'string', 'max:255'],
            'content' => ['required', 'array'],
            'content.tm' => ['required', 'string'],
            'content.ru' => ['required', 'string'],
            'excerpt' => ['nullable', 'array'],
            'category_id' => ['required', 'exists:categories,id'],
            'image' => ['nullable', 'image', 'max:2048'],
            'is_featured' => ['boolean'],
            'status' => ['required', 'in:draft,published'],
            'tags' => ['array'],
        ]);

        $validated['user_id'] = Auth::id();
        $validated['image'] = $this->imageUploadService->upload($request->file('image'), 'articles');
        $validated['published_at'] = $validated['status'] === 'published' ? now() : null;
        $validated['is_featured'] = $request->boolean('is_featured');

        $article = Article::create($validated);
        if (isset($validated['tags'])) {
            $article->tags()->sync($validated['tags']);
        }

        return redirect()->route('admin.articles.index')->with('success', 'Статья успешно создана.');
    }

    public function edit(Article $article)
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.articles.edit', compact('article', 'categories', 'tags'));
    }

    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title' => ['required', 'array'],
            'title.tm' => ['required', 'string', 'max:255'],
            'title.ru' => ['required', 'string', 'max:255'],
            'content' => ['required', 'array'],
            'content.tm' => ['required', 'string'],
            'content.ru' => ['required', 'string'],
            'excerpt' => ['nullable', 'array'],
            'category_id' => ['required', 'exists:categories,id'],
            'image' => ['nullable', 'image', 'max:2048'],
            'is_featured' => ['boolean'],
            'status' => ['required', 'in:draft,published'],
            'tags' => ['array'],
        ]);

        if ($request->hasFile('image')) {
            $this->imageUploadService->delete($article->image);
            $validated['image'] = $this->imageUploadService->upload($request->file('image'), 'articles');
        }

        if ($validated['status'] === 'published' && !$article->published_at) {
            $validated['published_at'] = now();
        }

        $validated['is_featured'] = $request->boolean('is_featured');

        $article->update($validated);
        if (isset($validated['tags'])) {
            $article->tags()->sync($validated['tags']);
        }

        return redirect()->route('admin.articles.index')->with('success', 'Статья успешно обновлена.');
    }

    public function destroy(Article $article)
    {
        $this->imageUploadService->delete($article->image);
        $article->delete();

        return redirect()->route('admin.articles.index')->with('success', 'Статья удалена.');
    }
}
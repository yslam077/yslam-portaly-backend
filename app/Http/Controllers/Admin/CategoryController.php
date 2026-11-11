<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Support\PublicCache;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('articles')->orderBy('sort_order')->paginate(15);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);

        Category::create($validated);
        PublicCache::flush();

        return redirect()->route('admin.categories.index')->with('success', 'Категория создана.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $category->update($this->validated($request));
        PublicCache::flush();

        return redirect()->route('admin.categories.index')->with('success', 'Категория обновлена.');
    }

    public function destroy(Category $category)
    {
        if ($category->articles()->withTrashed()->exists()) {
            return back()->with('error', 'Нельзя удалить категорию, в которой есть новости.');
        }

        $category->delete();
        PublicCache::flush();

        return redirect()->route('admin.categories.index')->with('success', 'Категория удалена.');
    }

    protected function validated(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'array'],
            'name.tm' => ['required', 'string', 'max:255'],
            'name.ru' => ['required', 'string', 'max:255'],
            'name.en' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $validated['name'] = array_filter($validated['name'], fn ($v) => $v !== null && $v !== '');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        return $validated;
    }
}
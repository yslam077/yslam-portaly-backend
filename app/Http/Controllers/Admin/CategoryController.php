<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('sort_order')->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'array'],
            'name.tm' => ['required', 'string', 'max:255'],
            'name.ru' => ['required', 'string', 'max:255'],
            'sort_order' => ['integer'],
        ]);

        Category::create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Категория успешно создана.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'array'],
            'name.tm' => ['required', 'string', 'max:255'],
            'name.ru' => ['required', 'string', 'max:255'],
            'sort_order' => ['integer'],
        ]);

        $category->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Категория успешно обновлена.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Категория удалена.');
    }
}
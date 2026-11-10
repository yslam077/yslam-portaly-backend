<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::latest()->paginate(15);
        return view('admin.tags.index', compact('tags'));
    }

    public function create()
    {
        return view('admin.tags.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'array'],
            'name.tm' => ['required', 'string', 'max:255'],
            'name.ru' => ['required', 'string', 'max:255'],
        ]);

        Tag::create($validated);

        return redirect()->route('admin.tags.index')->with('success', 'Тег успешно создан.');
    }

    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'name' => ['required', 'array'],
            'name.tm' => ['required', 'string', 'max:255'],
            'name.ru' => ['required', 'string', 'max:255'],
        ]);

        $tag->update($validated);

        return redirect()->route('admin.tags.index')->with('success', 'Тег успешно обновлен.');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();

        return redirect()->route('admin.tags.index')->with('success', 'Тег удален.');
    }
}
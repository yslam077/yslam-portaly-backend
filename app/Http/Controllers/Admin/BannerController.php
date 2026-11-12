<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Services\ImageUploadService;
use App\Support\PublicCache;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function __construct(protected ImageUploadService $imageUploadService) {}

    public function index()
    {
        $banners = Banner::orderBy('sort_order')->paginate(15);

        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'image' => ['required', 'image', 'max:2048'],
            'link' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $validated['image'] = $this->imageUploadService->upload($request->file('image'), 'banners');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active');

        Banner::create($validated);
        PublicCache::flush();

        return redirect()->route('admin.banners.index')->with('success', 'Баннер создан.');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:2048'],
            'link' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        unset($validated['image']);

        if ($request->hasFile('image')) {
            $this->imageUploadService->delete($banner->image);
            $validated['image'] = $this->imageUploadService->upload($request->file('image'), 'banners');
        }

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active');

        $banner->update($validated);
        PublicCache::flush();

        return redirect()->route('admin.banners.index')->with('success', 'Баннер обновлён.');
    }

    public function destroy(Banner $banner)
    {
        $this->imageUploadService->delete($banner->image);
        $banner->delete();
        PublicCache::flush();

        return redirect()->route('admin.banners.index')->with('success', 'Баннер удалён.');
    }
}

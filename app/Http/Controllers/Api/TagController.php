<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class TagController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $locale = app()->getLocale();
        $tagIds = Cache::remember("tags_list_ids_{$locale}", now()->addMinutes(10), function () {
            return Tag::orderBy('id')->pluck('id')->all();
        });
        $tags = Tag::whereKey($tagIds)->orderBy('id')->get();

        return TagResource::collection($tags);
    }
}
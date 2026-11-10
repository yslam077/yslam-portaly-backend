<?php

namespace App\Http\Controllers\Api\V1;

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
        $tags = Cache::remember("tags_list_{$locale}", now()->addMinutes(10), function () {
            return Tag::all();
        });

        return TagResource::collection($tags);
    }
}
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use App\Models\Banner;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class BannerController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $bannerIds = Cache::remember('banners_active_ids', now()->addMinutes(10), function () {
            return Banner::active()->pluck('id')->all();
        });
        $banners = Banner::active()->whereKey($bannerIds)->get();

        return BannerResource::collection($banners);
    }
}
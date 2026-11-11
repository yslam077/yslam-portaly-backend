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
        $banners = Cache::remember('banners_active', now()->addMinutes(10), function () {
            return Banner::active()->get();
        });

        return BannerResource::collection($banners);
    }
}
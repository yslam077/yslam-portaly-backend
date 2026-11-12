<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

class PublicCache
{
    public static function flush(): void
    {
        foreach (['tm', 'ru', 'en'] as $locale) {
            Cache::forget("categories_list_{$locale}");
            Cache::forget("categories_list_ids_{$locale}");
            Cache::forget("articles_trending_{$locale}");
            Cache::forget("articles_trending_ids_{$locale}");
            Cache::forget("articles_popular_{$locale}");
            Cache::forget("articles_popular_ids_{$locale}");
            Cache::forget("tags_list_{$locale}");
            Cache::forget("tags_list_ids_{$locale}");
        }

        Cache::forget('banners_active');
        Cache::forget('banners_active_ids');
    }
}
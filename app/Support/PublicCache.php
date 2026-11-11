<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

class PublicCache
{
    public static function flush(): void
    {
        foreach (['tm', 'ru', 'en'] as $locale) {
            Cache::forget("categories_list_{$locale}");
            Cache::forget("articles_trending_{$locale}");
            for ($limit = 1; $limit <= 20; $limit++) {
                Cache::forget("articles_popular_{$locale}_{$limit}");
            }
        }
    }
}
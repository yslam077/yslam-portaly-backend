<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use App\Support\PublicCache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class PublicFeedCacheTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_feed_caches_store_ids_and_refresh_after_categories_change(): void
    {
        Cache::flush();

        $category = Category::create([
            'name' => ['tm' => 'Synag', 'ru' => 'Тест', 'en' => 'Test'],
            'slug' => 'test',
            'sort_order' => 1,
        ]);
        $tag = Tag::create([
            'name' => ['tm' => 'Bellik', 'ru' => 'Метка', 'en' => 'Tag'],
            'slug' => 'tag',
        ]);
        $user = User::factory()->create();
        Article::create([
            'category_id' => $category->id,
            'user_id' => $user->id,
            'title' => ['tm' => 'Habar', 'ru' => 'Новость', 'en' => 'Article'],
            'content' => ['tm' => 'Tekst', 'ru' => 'Текст', 'en' => 'Content'],
            'excerpt' => ['tm' => 'Gysga', 'ru' => 'Кратко', 'en' => 'Short'],
            'slug' => 'article',
            'status' => 'published',
            'published_at' => now(),
            'views' => 10,
        ])->tags()->attach($tag);
        Banner::create([
            'title' => 'Banner',
            'image' => 'banner.jpg',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $feeds = [
            '/api/v1/categories' => fn (string $locale): string => "categories_list_ids_{$locale}",
            '/api/v1/tags' => fn (string $locale): string => "tags_list_ids_{$locale}",
            '/api/v1/articles/popular' => fn (string $locale): string => "articles_popular_ids_{$locale}",
            '/api/v1/articles/trending' => fn (string $locale): string => "articles_trending_ids_{$locale}",
            '/api/v1/banners' => fn (string $locale): string => 'banners_active_ids',
        ];

        foreach ($feeds as $path => $cacheKey) {
            $this->getJson($path)->assertOk()->assertJsonCount(1, 'data');
            $this->assertIsArray(Cache::get($cacheKey(app()->getLocale())));
            $this->getJson($path)->assertOk()->assertJsonCount(1, 'data');
        }

        $newCategory = Category::create([
            'name' => ['tm' => 'Täze', 'ru' => 'Новая', 'en' => 'New'],
            'slug' => 'new',
            'sort_order' => 2,
        ]);
        PublicCache::flush();

        $this->getJson('/api/v1/categories')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.1.id', $newCategory->id);
    }
}

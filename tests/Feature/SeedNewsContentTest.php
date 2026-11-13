<?php

namespace Tests\Feature;

use App\Models\Article;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SeedNewsContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_creates_thirty_five_tagged_demo_articles_with_images(): void
    {
        Storage::fake('public');

        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseCount('articles', 35);
        $this->assertDatabaseCount('categories', 11);
        $this->assertDatabaseCount('tags', 19);
        $this->assertDatabaseCount('banners', 4);
        $this->assertDatabaseHas('articles', [
            'slug' => 'turkmenistanyn-halkara-hyzmatdaslygynda-taze-dowur',
            'status' => 'published',
        ]);

        $article = Article::query()
            ->where('slug', 'turkmenistanyn-halkara-hyzmatdaslygynda-taze-dowur')
            ->firstOrFail();

        $this->assertSame('[DEMO] The value of open dialogue in international cooperation', $article->getTranslation('title', 'en'));
        $this->assertSame(3, $article->tags()->count());
        $this->assertSame(35, Article::query()->has('tags')->count());
        $this->assertSame(0, Article::query()->whereNull('image')->count());

        foreach (Article::query()->pluck('image')->unique() as $image) {
            Storage::disk('public')->assertExists($image);
        }

        foreach (['banners/banner-1.jpg', 'banners/banner-2.jpg', 'banners/banner-3.jpg', 'banners/banner-4.jpg'] as $image) {
            Storage::disk('public')->assertExists($image);
        }

        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseCount('articles', 35);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banners = [
            [
                'title' => 'Yslam Portaly — Ähli täzelikler bir ýerde',
                'image' => 'banners/banner-1.jpg',
                'link' => '/category/sport',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Sport älemindäki iň soňky habarlar we netijeler',
                'image' => 'banners/banner-2.jpg',
                'link' => '/category/sport',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Ykdysadyýet we Biznes dünýäsindäki ösüşler',
                'image' => 'banners/banner-3.jpg',
                'link' => '/category/biznes',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Dünýä we Syýasat täzelikleri',
                'image' => 'banners/banner-4.jpg',
                'link' => '/category/dunya',
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($banners as $banner) {
            Banner::updateOrCreate(
                ['title' => $banner['title']],
                $banner
            );
        }
    }
}

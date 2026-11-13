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
                'title' => 'Aşgabat we jemgyýetçilik durmuşy',
                'image' => 'banners/banner-1.jpg',
                'link' => '/category/jemgyyet',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Sport we sagdyn durmuş',
                'image' => 'banners/banner-2.jpg',
                'link' => '/category/sport',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Türkmen halyçylyk sungaty we medeni miras',
                'image' => 'banners/banner-3.jpg',
                'link' => '/category/kultura',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Hazar deňzi we syýahatçylyk',
                'image' => 'banners/banner-4.jpg',
                'link' => '/category/syahatcylyk',
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

<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            [
                'name' => ['tm' => 'Täzelikler', 'ru' => 'Новости', 'en' => 'News'],
                'slug' => 'tazelikler',
            ],
            [
                'name' => ['tm' => 'Türkmenistan', 'ru' => 'Туркменистан', 'en' => 'Turkmenistan'],
                'slug' => 'turkmenistan',
            ],
            [
                'name' => ['tm' => 'Tehnologiýa', 'ru' => 'Технологии', 'en' => 'Technology'],
                'slug' => 'tehnologiya',
            ],
            [
                'name' => ['tm' => 'Futbol', 'ru' => 'Футбол', 'en' => 'Football'],
                'slug' => 'futbol',
            ],
            [
                'name' => ['tm' => 'Ylym', 'ru' => 'Наука', 'en' => 'Science'],
                'slug' => 'ylym',
            ],
            [
                'name' => ['tm' => 'Saglyk', 'ru' => 'Здоровье', 'en' => 'Health'],
                'slug' => 'saglyk',
            ],
        ];

        foreach ($tags as $tagData) {
            Tag::updateOrCreate(
                ['slug' => $tagData['slug']],
                ['name' => $tagData['name']]
            );
        }
    }
}

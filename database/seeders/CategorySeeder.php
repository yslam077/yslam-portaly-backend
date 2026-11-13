<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => ['tm' => 'Syyasat', 'ru' => 'Политика', 'en' => 'Politics'],
                'slug' => 'syyasat',
                'sort_order' => 1,
            ],
            [
                'name' => ['tm' => 'Biznes', 'ru' => 'Бизнес', 'en' => 'Business'],
                'slug' => 'biznes',
                'sort_order' => 2,
            ],
            [
                'name' => ['tm' => 'Kultura', 'ru' => 'Культура', 'en' => 'Culture'],
                'slug' => 'kultura',
                'sort_order' => 3,
            ],
            [
                'name' => ['tm' => 'Ekonomika', 'ru' => 'Экономика', 'en' => 'Economy'],
                'slug' => 'ekonomika',
                'sort_order' => 4,
            ],
            [
                'name' => ['tm' => 'Sport', 'ru' => 'Спорт', 'en' => 'Sport'],
                'slug' => 'sport',
                'sort_order' => 5,
            ],
            [
                'name' => ['tm' => 'Awtoulaglar', 'ru' => 'Автомобили', 'en' => 'Cars'],
                'slug' => 'awtoulaglar',
                'sort_order' => 6,
            ],
            [
                'name' => ['tm' => 'Dunya', 'ru' => 'В мире', 'en' => 'World'],
                'slug' => 'dunya',
                'sort_order' => 7,
            ],
            [
                'name' => ['tm' => 'Jemgyýet', 'ru' => 'Общество', 'en' => 'Society'],
                'slug' => 'jemgyyet',
                'sort_order' => 8,
            ],
            [
                'name' => ['tm' => 'Bilim', 'ru' => 'Образование', 'en' => 'Education'],
                'slug' => 'bilim',
                'sort_order' => 9,
            ],
            [
                'name' => ['tm' => 'Tehnologiýa', 'ru' => 'Технологии', 'en' => 'Technology'],
                'slug' => 'tehnologiya',
                'sort_order' => 10,
            ],
            [
                'name' => ['tm' => 'Syýahatçylyk', 'ru' => 'Туризм', 'en' => 'Travel'],
                'slug' => 'syahatcylyk',
                'sort_order' => 11,
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => $cat['slug']],
                [
                    'name' => $cat['name'],
                    'sort_order' => $cat['sort_order'],
                ]
            );
        }
    }
}

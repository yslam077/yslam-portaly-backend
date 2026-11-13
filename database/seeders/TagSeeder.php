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
                'name' => ['tm' => 'Sport', 'ru' => 'Спорт', 'en' => 'Sport'],
                'slug' => 'sport',
            ],
            [
                'name' => ['tm' => 'Ylym', 'ru' => 'Наука', 'en' => 'Science'],
                'slug' => 'ylym',
            ],
            [
                'name' => ['tm' => 'Saglyk', 'ru' => 'Здоровье', 'en' => 'Health'],
                'slug' => 'saglyk',
            ],
            [
                'name' => ['tm' => 'Syýasat', 'ru' => 'Политика', 'en' => 'Politics'],
                'slug' => 'syyasat',
            ],
            [
                'name' => ['tm' => 'Biznes', 'ru' => 'Бизнес', 'en' => 'Business'],
                'slug' => 'biznes',
            ],
            [
                'name' => ['tm' => 'Medeniýet', 'ru' => 'Культура', 'en' => 'Culture'],
                'slug' => 'kultura',
            ],
            [
                'name' => ['tm' => 'Ykdysadyýet', 'ru' => 'Экономика', 'en' => 'Economy'],
                'slug' => 'ekonomika',
            ],
            [
                'name' => ['tm' => 'Jemgyýet', 'ru' => 'Общество', 'en' => 'Society'],
                'slug' => 'jemgyyet',
            ],
            [
                'name' => ['tm' => 'Bilim', 'ru' => 'Образование', 'en' => 'Education'],
                'slug' => 'bilim',
            ],
            [
                'name' => ['tm' => 'Syýahatçylyk', 'ru' => 'Туризм', 'en' => 'Travel'],
                'slug' => 'syahatcylyk',
            ],
            [
                'name' => ['tm' => 'Tebigat', 'ru' => 'Природа', 'en' => 'Nature'],
                'slug' => 'tebigat',
            ],
            [
                'name' => ['tm' => 'Awtoulaglar', 'ru' => 'Автомобили', 'en' => 'Automotive'],
                'slug' => 'awtoulaglar',
            ],
            [
                'name' => ['tm' => 'Energetika', 'ru' => 'Энергетика', 'en' => 'Energy'],
                'slug' => 'energetika',
            ],
            [
                'name' => ['tm' => 'Medeni miras', 'ru' => 'Культурное наследие', 'en' => 'Heritage'],
                'slug' => 'medeni-miras',
            ],
            [
                'name' => ['tm' => 'Suw serişdeleri', 'ru' => 'Водные ресурсы', 'en' => 'Water'],
                'slug' => 'suw',
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

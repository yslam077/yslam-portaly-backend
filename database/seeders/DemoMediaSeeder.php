<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class DemoMediaSeeder extends Seeder
{
    /**
     * @var array<string, list<string>>
     */
    private const MEDIA_DESTINATIONS = [
        'ashgabat-south.jpg' => ['articles/ashgabat-south.jpg', 'banners/banner-1.jpg'],
        'football-team.jpg' => ['articles/football-team-2015.jpg', 'banners/banner-2.jpg'],
        'turkmen-carpet.jpg' => ['articles/turkmen-carpet.jpg', 'banners/banner-3.jpg'],
        'caspian-sea.jpg' => ['articles/caspian-sea.jpg', 'banners/banner-4.jpg'],
        'school-volleyball.jpg' => ['articles/school-volleyball.jpg'],
        'darvaza.jpg' => ['articles/darvaza.jpg'],
        'dust-storm.jpg' => ['articles/dust-storm.jpg'],
        'ashgabat-airport.jpg' => ['articles/ashgabat-airport.jpg'],
        'olympic-complex.jpg' => ['articles/olympic-complex.jpg'],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $publicDisk = Storage::disk('public');

        foreach (self::MEDIA_DESTINATIONS as $sourceName => $destinations) {
            $sourcePath = database_path('seeders/assets/'.$sourceName);
            $contents = File::get($sourcePath);

            foreach ($destinations as $destination) {
                if (! $publicDisk->put($destination, $contents)) {
                    throw new RuntimeException("Unable to seed demo image: {$destination}");
                }
            }
        }
    }
}

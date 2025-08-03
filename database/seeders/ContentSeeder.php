<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ContentSeeder extends Seeder
{
    public function run()
    {
        $contents = [
            [
                'title' => 'The Great Adventure',
                'description' => 'An exhilarating journey of a young hero across uncharted lands.',
                'release_date' => '2023-11-10',
                'type' => 'movie',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Mystery Manor',
                'description' => 'A thrilling mystery unfolding inside a haunted mansion.',
                'release_date' => '2024-05-20',
                'type' => 'series',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Space Odyssey',
                'description' => 'An epic sci-fi saga across galaxies and beyond.',
                'release_date' => '2025-01-15',
                'type' => 'movie',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'City Lights',
                'description' => 'Romantic stories intertwined under the glow of neon lights.',
                'release_date' => '2023-08-05',
                'type' => 'series',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Legends of the Forest',
                'description' => 'Fantasy tales from an enchanted ancient forest.',
                'release_date' => '2024-09-30',
                'type' => 'movie',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('contents')->insert($contents);
    }
}

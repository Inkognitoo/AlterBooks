<?php

use Illuminate\Database\Seeder;

class GenresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $genres = [
            [
                'name' => 'научная фантастика',
                'slug' => 'science-fiction',
            ],
            [
                'name' => 'фэнтези',
                'slug' => 'fantasy',
            ],
            [
                'name' => 'мистика',
                'slug' => 'mystic',
            ],
            [
                'name' => 'хоррор',
                'slug' => 'horror',
            ],
            [
                'name' => 'детектив',
                'slug' => 'detective',
            ],
            [
                'name' => 'классика',
                'slug' => 'classic',
            ],
            [
                'name' => 'современная проза',
                'slug' => 'modern-prose',
            ],
        ];

        foreach ($genres as $genre) {
            $new_genre = \App\Genre::firstOrCreate(['name' => $genre['name'], 'slug' => $genre['slug']]);
            $new_genre->save();
        }


    }
}

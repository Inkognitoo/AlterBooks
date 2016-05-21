<?php

use Illuminate\Database\Seeder;
use App\Language;

class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $language_data = [
            'internal_name' => 'ru',
            'name' => 'Русский'
        ];
        $language = new Language($language_data);
        $language->save();

        $language_data = [
            'internal_name' => 'en',
            'name' => 'English'
        ];
        $language = new Language($language_data);
        $language->save();
    }
}

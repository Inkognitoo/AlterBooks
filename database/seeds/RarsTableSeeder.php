<?php

use Illuminate\Database\Seeder;
use App\Rars;

class RarsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rars_data = [
            'internal_name' => 'zero',
            'ru_name' => '0+',
            'en_name' => '0+'
        ];
        $rars = new Rars($rars_data);
        $rars->save();

        $rars_data = [
            'internal_name' => 'six',
            'ru_name' => '6+',
            'en_name' => '6+'
        ];
        $rars = new Rars($rars_data);
        $rars->save();

        $rars_data = [
            'internal_name' => 'twelve',
            'ru_name' => '12+',
            'en_name' => '12+'
        ];
        $rars = new Rars($rars_data);
        $rars->save();

        $rars_data = [
            'internal_name' => 'sixteen',
            'ru_name' => '16+',
            'en_name' => '16+'
        ];
        $rars = new Rars($rars_data);
        $rars->save();

        $rars_data = [
            'internal_name' => 'eighteen',
            'ru_name' => '18+',
            'en_name' => '18+'
        ];
        $rars = new Rars($rars_data);
        $rars->save();
    }
}

<?php

use Illuminate\Database\Seeder;

class RarsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rars = New \App\Rars();
        $rars->name = '0+';
        $rars->eternal_name = 'no_limits';
        $rars->save();

        $rars = New \App\Rars();
        $rars->name = '6+';
        $rars->eternal_name = 'six_or_more';
        $rars->save();

        $rars = New \App\Rars();
        $rars->name = '12+';
        $rars->eternal_name = 'twelve_or_more';
        $rars->save();

        $rars = New \App\Rars();
        $rars->name = '16+';
        $rars->eternal_name = 'sixteen_or_more';
        $rars->save();

        $rars = New \App\Rars();
        $rars->name = '18+';
        $rars->eternal_name = 'eighteen_or_more';
        $rars->save();
    }
}

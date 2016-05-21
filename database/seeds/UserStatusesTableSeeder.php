<?php

use Illuminate\Database\Seeder;
use App\UserStatus;

class UserStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_statuses_data = [
            'internal_name' => 'standard',
            'ru_name' => 'Стандартный',
            'en_name' => 'Standard'
        ];
        $user_statuses = new UserStatus($user_statuses_data);
        $user_statuses->save();

        $user_statuses_data = [
            'internal_name' => 'verified',
            'ru_name' => 'Верифицированный',
            'en_name' => 'Verified'
        ];
        $user_statuses = new UserStatus($user_statuses_data);
        $user_statuses->save();

        $user_statuses_data = [
            'internal_name' => 'blocked',
            'ru_name' => 'Заблокированный',
            'en_name' => 'Blocked'
        ];
        $user_statuses = new UserStatus($user_statuses_data);
        $user_statuses->save();
    }
}

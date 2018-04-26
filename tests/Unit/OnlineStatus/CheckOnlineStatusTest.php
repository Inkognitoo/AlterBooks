<?php

namespace Tests\Unit\OnlineStatus;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CheckOnlineStatusTest extends TestCase
{
    use DatabaseMigrations;

    use DatabaseTransactions;

    /**
     * Проверяем работу оффлайн статуса
     *
     * @return void
     */
    public function testOfflineStatus()
    {
        $person = factory(User::class)->create();
        $person->last_activity_at = Carbon::parse($person->last_activity_at)
            ->subMinutes($person::ONLINE_ENDED + 1);
        $this->assertFalse($person->isOnline());
    }
}

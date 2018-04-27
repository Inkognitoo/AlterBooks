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
     * Проверяем возврат оффлайн статуса, если last_activity_at пользователя больше, чем ONLINE_ENDED
     *
     * @return void
     */
    public function testLastActivityIsMoreThenOnlineEnded()
    {
        $person = factory(User::class)->create();
        $person->last_activity_at = Carbon::parse($person->last_activity_at)
            ->subMinutes($person::ONLINE_ENDED + 1);
        $this->assertFalse($person->isOnline());
    }

    /**
     * Проверяем возврат онлайн статуса, если last_activity_at пользователя равно текущему времени
     *
     * @return void
     */
    public function testLastActivityIsTheCurrentTime()
    {
        $person = factory(User::class)->create();
        $person->last_activity_at = Carbon::now();
        $this->assertTrue($person->isOnline());
    }

    /**
     * Проверяем возврат онлайн статуса, если last_activity_at пользователя меньше, чем ONLINE_ENDED
     *
     * @return void
     */
    public function testLastActivityIsLessThenOnlineEnded()
    {
        $person = factory(User::class)->create();
        $person->last_activity_at = Carbon::parse($person->last_activity_at)
            ->subMinutes($person::ONLINE_ENDED - 1);
        $this->assertTrue($person->isOnline());
    }

    /**
     * Проверка изменения last_activity_at в большую сторону при посещении страниц
     *
     * @return void
     */
    /*public function testLastActivityAtChangeWhenVisitingPages()
    {

    }*/
}

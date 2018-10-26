<?php

namespace Tests\Unit\OnlineStatus;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use LibraryTestSeeder;
use App\Models\Book;
use App\Models\User;
use Tests\TestCase;
use Carbon\Carbon;
use Auth;

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
    public function testLastActivityAtChangeWhenVisitingPages()
    {
        $person = factory(User::class)->create();
        Auth::attempt(['email' => $person->email, 'password' => 'secret']);
        $last_activity = Auth::user()->last_activity_at;
        sleep(2);
        $this->call('GET', '/');
        $this->assertTrue($last_activity < Auth::user()->last_activity_at);
    }

    /**
     * Проверка изменения last_activity_at в большую сторону при обращении к API
     *
     * @return void
     */
    public function testLastActivityAtChangeWhenAccessingTheAPI()
    {
        $this->seed(LibraryTestSeeder::class);

        /** @var User $person */
        $person = factory(User::class)->create();
        Auth::attempt(['email' => $person->email, 'password' => 'secret']);

        $startPersonLastActivity = Auth::user()->last_activity_at;

        $book = Book::inRandomOrder()
            ->first()
        ;

        $headers = [
            'Authorization' => 'Bearer ' . Auth::user()->api_token
        ];

        Auth::setDefaultDriver('api');

        sleep(2);

        $this->post(route('api.library.add', ['id' => "id{$book->id}"]), [], $headers);

        $this->assertTrue($startPersonLastActivity < Auth::user()->last_activity_at);
    }
}

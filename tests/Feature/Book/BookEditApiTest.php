<?php

namespace Tests\Feature\Book;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BookEditApiTest extends TestCase
{
    use DatabaseMigrations;

    use DatabaseTransactions;

    use WithFaker;

    /**
     * Успешное редактирование данных книги
     * @return void
     * @throws \Exception
     */
    public function testBookEditSuccess()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        /** @var Book $book */
        $book = $user->books()->save(factory(Book::class)->make(['status' => Book::STATUS_OPEN]));

        $title = mb_strlen($book->title) < 50 ? $book->title.'1' : mb_substr($book->title, 0, 20);
        $status = Book::STATUS_CLOSE;
        do {
            $description = mb_convert_encoding($this->faker->realText(random_int(200, 300)), 'UTF-8');
        } while ($description === $book->description);

        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        $response = $this->put(
            route('api.book.edit', ['book_id' => $book->id]),
            [
                'title' => $title,
                'status' => $status,
                'description' => $description
            ],
            $headers
        );

        $response->assertJson([
            'success' => true
        ]);

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => $title,
            'status' => $status,
            'description' => $description
        ]);
    }

    /**
     * Провал редактирования данных книги: название не введено
     *
     * @return void
     * @throws \Exception
     */
    public function testNoTitleFail()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        /** @var Book $book */
        $book = $user->books()->save(factory(Book::class)->make(['status' => Book::STATUS_OPEN]));

        $title = '';
        $status = Book::STATUS_CLOSE;

        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        $response = $this->put(
            route('api.book.edit', ['book_id' => $book->id]),
            [
                'title' => $title,
                'status' => $status
            ],
            $headers
        );

        $response->assertJson([
            'success' => false
        ]);
    }

    /**
     * Провал редактирования данных книги: название длиннее 50 символов
     *
     * @return void
     * @throws \Exception
     */
    public function testTitleMoreMaxFail()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        /** @var Book $book */
        $book = $user->books()->save(factory(Book::class)->make(['status' => Book::STATUS_OPEN]));

        $title = mb_convert_encoding($this->faker->realText(random_int(100, 150)), 'UTF-8');
        $status = Book::STATUS_CLOSE;

        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        $response = $this->put(
            route('api.book.edit', ['book_id' => $book->id]),
            [
                'title' => $title,
                'status' => $status
            ],
            $headers
        );

        $response->assertJson([
            'success' => false
        ]);
    }

    /**
     * Провал редактирования данных книги: не указан статус книги
     *
     * @return void
     * @throws \Exception
     */
    public function testNoStatusFail()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        /** @var Book $book */
        $book = $user->books()->save(factory(Book::class)->make(['status' => Book::STATUS_OPEN]));

        $title = $book->title;
        $status = '';

        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        $response = $this->put(
            route('api.book.edit', ['book_id' => $book->id]),
            [
                'title' => $title,
                'status' => $status
            ],
            $headers
        );

        $response->assertJson([
            'success' => false
        ]);
    }

    /**
     * Провал редактирования данных книги: статус книги указан неверно
     *
     * @return void
     * @throws \Exception
     */
    public function testIncorrectStatusFail()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        /** @var Book $book */
        $book = $user->books()->save(factory(Book::class)->make(['status' => Book::STATUS_OPEN]));

        $title = $book->title;
        $status = Book::STATUS_CLOSE.'1';

        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        $response = $this->put(
            route('api.book.edit', ['book_id' => $book->id]),
            [
                'title' => $title,
                'status' => $status
            ],
            $headers
        );

        $response->assertJson([
            'success' => false
        ]);
    }

    /**
     * Провал редактирования данных книги: описание кгини длиннее 5000 символов
     *
     * @return void
     * @throws \Exception
     */
    public function testDescriptionMoreMaxFail()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        /** @var Book $book */
        $book = $user->books()->save(factory(Book::class)->make(['status' => Book::STATUS_OPEN]));

        $title = $book->title;
        $status = Book::STATUS_CLOSE;
        $description = mb_convert_encoding($this->faker->realText(random_int(8000, 8500)), 'UTF-8');

        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        $response = $this->put(
            route('api.book.edit', ['book_id' => $book->id]),
            [
                'title' => $title,
                'status' => $status,
                'description' => $description
            ],
            $headers
        );

        $response->assertJson([
            'success' => false
        ]);
    }
}


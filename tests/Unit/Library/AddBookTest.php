<?php

namespace Tests\Unit\Library;

use App\Book;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use LibraryTestSeeder;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddBookTest extends TestCase
{
    use DatabaseMigrations;

    use DatabaseTransactions;

    /**
     * Проверяем успешное добавление книги в библиотеку
     *
     * @return void
     */
    public function testAddOneBookToLibrary()
    {
        $this->seed(LibraryTestSeeder::class);

        /** @var User $person */
        $person = factory(User::class)->create();
        $book = Book::inRandomOrder()
            ->first()
        ;

        $headers = [
            'Authorization' => 'Bearer ' . $person->api_token
        ];

        $response = $this->post(route('api.library.add', ['id' => $book->id]), [], $headers);
        $response->assertJson([
            'success' => true,
            'code' => 200
        ]);
        $this->assertDatabaseHas('users_library', [
            'user_id' => $person->id,
            'book_id' => $book->id,
        ]);
    }

    /**
     * Проверяем повторное добавление книги в библиотеку
     *
     * @return void
     */
    public function testAddOneBookToLibraryAgain()
    {
        $this->seed(LibraryTestSeeder::class);

        /** @var User $person */
        $person = factory(User::class)->create();
        $book = Book::inRandomOrder()
            ->first()
        ;

        $headers = [
            'Authorization' => 'Bearer ' . $person->api_token
        ];

        $response = $this->post(route('api.library.add', ['id' => $book->id]), [], $headers);
        $response->assertJson([
            'success' => true,
            'code' => 200
        ]);
        $this->assertDatabaseHas('users_library', [
            'user_id' => $person->id,
            'book_id' => $book->id,
        ]);

        $response = $this->post(route('api.library.add', ['id' => $book->id]), [], $headers);
        $response->assertJson([
            'success' => false,
            'code' => 400
        ]);
        $this->assertDatabaseHas('users_library', [
            'user_id' => $person->id,
            'book_id' => $book->id,
        ]);
    }

    /**
     * Проверяем добавление нескольких книг в библиотеку подряд
     *
     * @return void
     */
    public function testAddMultipleBookToLibrary()
    {
        $this->seed(LibraryTestSeeder::class);

        /** @var User $person */
        $person = factory(User::class)->create();
        $books = Book::inRandomOrder()
            ->limit(10)
            ->get()
        ;

        $headers = [
            'Authorization' => 'Bearer ' . $person->api_token
        ];

        foreach ($books as $book) {
            $response = $this->post(route('api.library.add', ['id' => $book->id]), [], $headers);
            $response->assertJson([
                'success' => true,
                'code' => 200
            ]);
            $this->assertDatabaseHas('users_library', [
                'user_id' => $person->id,
                'book_id' => $book->id,
            ]);

            $response = $this->post(route('api.library.add', ['id' => $book->id]), [], $headers);
            $response->assertJson([
                'success' => false,
                'code' => 400
            ]);
            $this->assertDatabaseHas('users_library', [
                'user_id' => $person->id,
                'book_id' => $book->id,
            ]);

            usleep(500000);
        }
    }

    /**
     * Проверяем невозможность добавления в бибилиотеку собственной книги
     *
     * @return void
     */
    public function testFailAddOwnBookToLibrary()
    {
        $this->seed(LibraryTestSeeder::class);

        /** @var User $person */
        $person = factory(User::class, 10)->create()->each(function ($u) {
            /** @var User $u */
            $u->books()->save(factory(Book::class)->make(['status' => Book::STATUS_OPEN]));
        })->first();
        $book = $person->books->first();

        $headers = [
            'Authorization' => 'Bearer ' . $person->api_token
        ];

        $response = $this->post(route('api.library.add', ['id' => $book->id]), [], $headers);

        $response->assertJson([
            'success' => false,
            'code' => 403
        ]);

        $this->assertDatabaseMissing('users_library', [
            'user_id' => $person->id,
            'book_id' => $book->id,
        ]);
    }
}

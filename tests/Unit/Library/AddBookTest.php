<?php

namespace Tests\Unit\Library;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use LibraryTestSeeder;
use Tests\TestCase;

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

        /** @var User $user */
        $user = factory(User::class)->create();
        $book = Book::inRandomOrder()
            ->first()
        ;

        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        $response = $this->post(route('api.library.add', ['id' => "id{$book->id}"]), [], $headers);
        $response->assertJson([
            'success' => true,
        ]);
        $this->assertDatabaseHas('users_library', [
            'user_id' => $user->id,
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

        /** @var User $user */
        $user = factory(User::class)->create();
        $book = Book::inRandomOrder()
            ->first()
        ;

        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        $response = $this->post(route('api.library.add', ['id' => "id{$book->id}"]), [], $headers);
        $response->assertJson([
            'success' => true,
        ]);
        $this->assertDatabaseHas('users_library', [
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);

        $response = $this->post(route('api.library.add', ['id' => "id{$book->id}"]), [], $headers);
        $response->assertJson([
            'success' => false,
        ]);
        $this->assertDatabaseHas('users_library', [
            'user_id' => $user->id,
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

        /** @var User $user */
        $user = factory(User::class)->create();
        $books = Book::inRandomOrder()
            ->limit(10)
            ->get()
        ;

        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        foreach ($books as $book) {
            $response = $this->post(route('api.library.add', ['id' => "id{$book->id}"]), [], $headers);
            $response->assertJson([
                'success' => true,
            ]);
            $this->assertDatabaseHas('users_library', [
                'user_id' => $user->id,
                'book_id' => $book->id,
            ]);

            $response = $this->post(route('api.library.add', ['id' => "id{$book->id}"]), [], $headers);
            $response->assertJson([
                'success' => false,
            ]);
            $this->assertDatabaseHas('users_library', [
                'user_id' => $user->id,
                'book_id' => $book->id,
            ]);
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

        /** @var User $user */
        $user = factory(User::class, 10)->create()->each(function ($user) {
            /** @var User $user */
            $user->books()->save(factory(Book::class)->make(['status' => Book::STATUS_OPEN]));
        })->first();
        $book = $user->books->first();

        $headers = [
            'Authorization' => 'Bearer ' . $user->api_token
        ];

        $response = $this->post(route('api.library.add', ['id' => "id{$book->id}"]), [], $headers);

        $response->assertJson([
            'success' => false,
        ]);

        $this->assertDatabaseMissing('users_library', [
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);
    }
}

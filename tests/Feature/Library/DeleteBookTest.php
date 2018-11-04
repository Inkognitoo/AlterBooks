<?php

namespace Tests\Feature\Library;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use LibraryTestSeeder;
use Tests\TestCase;

class DeleteBookTest extends TestCase
{
    use DatabaseMigrations;

    use DatabaseTransactions;

    /**
     * Проверяем успешное удаление книги
     *
     * @return void
     */
    public function testDeleteOneBookFromLibrary()
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

        $user->libraryBooks()->save($book);
        $this->assertDatabaseHas('users_library', [
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);

        $response = $this->delete(route('api.library.delete', ['id' => "id{$book->id}"]), [], $headers);
        $response->assertJson([
            'success' => true,
        ]);
        $this->assertDatabaseMissing('users_library', [
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);
    }

    /**
     * Проверяем правильное повторное удаление книги
     *
     * @return void
     */
    public function testDeleteOneBookFromLibraryAgain()
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

        $user->libraryBooks()->save($book);
        $this->assertDatabaseHas('users_library', [
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);

        $response = $this->delete(route('api.library.delete', ['id' => "id{$book->id}"]), [], $headers);
        $response->assertJson([
            'success' => true,
        ]);
        $this->assertDatabaseMissing('users_library', [
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);

        $response = $this->delete(route('api.library.delete', ['id' => "id{$book->id}"]), [], $headers);
        $response->assertJson([
            'success' => false,
        ]);
        $this->assertDatabaseMissing('users_library', [
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);
    }

    /**
     * Проверяем удаление нескольких книг из библиотеки подряд
     *
     * @return void
     */
    public function testDeleteMultipleBookFromLibrary()
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
            $user->libraryBooks()->save($book);
        }
        foreach ($books as $book) {
            $this->assertDatabaseHas('users_library', [
                'user_id' => $user->id,
                'book_id' => $book->id,
            ]);
        }

        foreach ($books as $book) {
            $response = $this->delete(route('api.library.delete', ['id' => "id{$book->id}"]), [], $headers);
            $response->assertJson([
                'success' => true,
            ]);
            $this->assertDatabaseMissing('users_library', [
                'user_id' => $user->id,
                'book_id' => $book->id,
            ]);

            $response = $this->delete(route('api.library.delete', ['id' => "id{$book->id}"]), [], $headers);
            $response->assertJson([
                'success' => false,
            ]);
            $this->assertDatabaseMissing('users_library', [
                'user_id' => $user->id,
                'book_id' => $book->id,
            ]);
        }
    }

    /**
     * Проверяем невозможность удаления из бибилиотеки собственной книги (с учётом того, что её там быть не может)
     *
     * @return void
     */
    public function testFailDeleteOwnBookFromLibrary()
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

        $response = $this->post(route('api.library.delete', ['id' => "id{$book->id}"]), [], $headers);
        $response->assertJson([
            'success' => false,
        ]);
    }
}

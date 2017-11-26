<?php

namespace Tests\Unit\Library;

use App\Book;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use LibraryTestSeeder;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteBookTest extends TestCase
{
    use DatabaseMigrations;

    use DatabaseTransactions;

    /**
     * Проверяем успешное удаление книги
     *
     * @return void
     */
    public function testDeleteOneBookToLibrary()
    {
        $this->seed(LibraryTestSeeder::class);

        $person = factory(User::class)->create();
        $this->be($person);

        $book = Book::inRandomOrder()
            ->first()
        ;
        $person->libraryBooks()->save($book);

        $response = $this->delete(route('api.library.delete', ['id' => $book->id]));
        $response->assertJson([
            'success' => true,
            'code' => 200
        ]);
    }

    /**
     * Проверяем правильное повторное удаление книги
     *
     * @return void
     */
    public function testAddOneBookToLibraryAgain()
    {
        $this->seed(LibraryTestSeeder::class);

        $person = factory(User::class)->create();
        $this->be($person);

        $book = Book::inRandomOrder()
            ->first()
        ;
        $person->libraryBooks()->save($book);

        $response = $this->delete(route('api.library.delete', ['id' => $book->id]));
        $response->assertJson([
            'success' => true,
            'code' => 200
        ]);

        $response = $this->delete(route('api.library.delete', ['id' => $book->id]));
        $response->assertJson([
            'success' => false,
            'code' => 400
        ]);
    }

    /**
     * Проверяем удаление нескольких книг из библиотеки подряд
     *
     * @return void
     */
    public function testAddMultipleBookToLibrary()
    {
        $this->seed(LibraryTestSeeder::class);

        $person = factory(User::class)->create();
        $this->be($person);

        $books = Book::inRandomOrder()
            ->limit(10)
            ->get()
        ;

        foreach ($books as $book) {
            $person->libraryBooks()->save($book);
        }

        foreach ($books as $book) {
            $response = $this->delete(route('api.library.delete', ['id' => $book->id]));
            $response->assertJson([
                'success' => true,
                'code' => 200
            ]);

            $response = $this->delete(route('api.library.delete', ['id' => $book->id]));
            $response->assertJson([
                'success' => false,
                'code' => 400
            ]);
        }
    }
}

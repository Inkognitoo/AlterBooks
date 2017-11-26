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

        $person = factory(User::class)->create();
        $this->be($person);
        $book_id = Book::inRandomOrder()
            ->first()
            ->id
        ;

        $response = $this->post(route('api.library.add', ['id' => $book_id]));

        $response->assertJson([
            'success' => true,
            'code' => 200
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

        $person = factory(User::class)->create();
        $this->be($person);
        $book_id = Book::inRandomOrder()
            ->first()
            ->id
        ;

        $response = $this->post(route('api.library.add', ['id' => $book_id]));
        $response->assertJson([
            'success' => true,
            'code' => 200
        ]);

        $response = $this->post(route('api.library.add', ['id' => $book_id]));
        $response->assertJson([
            'success' => false,
            'code' => 400
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

        $person = factory(User::class)->create();
        $this->be($person);
        $books = Book::inRandomOrder()
            ->limit(10)
            ->get()
        ;

        foreach ($books as $book) {
            $response = $this->post(route('api.library.add', ['id' => $book->id]));
            $response->assertJson([
                'success' => true,
                'code' => 200
            ]);

            $response = $this->post(route('api.library.add', ['id' => $book->id]));
            $response->assertJson([
                'success' => false,
                'code' => 400
            ]);
        }
    }
}

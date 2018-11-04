<?php

namespace Tests\Feature\Book;


use App\Models\Book;
use App\Models\Chapter;
use App\Models\Page;
use App\Models\User;
use Auth;
use File;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use LibraryTestSeeder;
use Tests\TestCase;

class TxtUploadTest extends TestCase
{
    use DatabaseMigrations;

    use DatabaseTransactions;

    /**
     * Проверяем успешную загрузку глав
     *
     * @return void
     */
    public function testChaptersUploadSuccess()
    {
        $this->seed(LibraryTestSeeder::class);

        /** @var User $user */
        $user = factory(User::class)->create();

        /** @var \App\Models\Book $book */
        $book = factory(Book::class)->make();
        $user->books()->save($book);

        Auth::login($user);

        //Тестируем для Алисы в стране чудес
        $book_name = 'Кэрролл_-_Алиса_в_стране_чудес.txt';
        $book_path = storage_path('seed/books/' . $book_name);
        $book_size = filesize(storage_path('seed/books/' . $book_name));
        $book_mime_type = File::mimeType(storage_path('seed/books/' . $book_name));
        $chapters_count = 14;

        $file = new UploadedFile($book_path, $book_name, $book_size, $book_mime_type, null, true);

        $response = $this->post(route('book.edit', ['id' => "id{$book->id}"]), [
            'title' => $book->title,
            'status' => Book::STATUS_OPEN,
            'text' => $file
        ]);
        $response->assertStatus(Response::HTTP_FOUND);

        $this->assertEquals($chapters_count, Chapter::where('book_id', $book->id)->count());

        //Тестируем для мёртвых душ
        $book_name = 'Гоголь_-_Мертвые_души.txt';
        $book_path = storage_path('seed/books/' . $book_name);
        $book_size = filesize(storage_path('seed/books/' . $book_name));
        $book_mime_type = File::mimeType(storage_path('seed/books/' . $book_name));
        $chapters_count = 16;

        $file = new UploadedFile($book_path, $book_name, $book_size, $book_mime_type, null, true);

        $response = $this->post(route('book.edit', ['id' => "id{$book->id}"]), [
            'title' => $book->title,
            'status' => Book::STATUS_OPEN,
            'text' => $file
        ]);
        $response->assertStatus(Response::HTTP_FOUND);

        $this->assertEquals($chapters_count, Chapter::where('book_id', $book->id)->count());
    }

    /**
     * Проверяем успешную загрузку страниц
     *
     * @return void
     */
    public function testPagesUploadSuccess()
    {
        $this->seed(LibraryTestSeeder::class);

        /** @var User $user */
        $user = factory(User::class)->create();

        /** @var \App\Models\Book $book */
        $book = factory(Book::class)->make();
        $user->books()->save($book);

        Auth::login($user);

        //Тестируем для Алисы в стране чудес
        $book_name = 'Кэрролл_-_Алиса_в_стране_чудес.txt';
        $book_path = storage_path('seed/books/' . $book_name);
        $book_size = filesize(storage_path('seed/books/' . $book_name));
        $book_mime_type = File::mimeType(storage_path('seed/books/' . $book_name));

        $file = new UploadedFile($book_path, $book_name, $book_size, $book_mime_type, null, true);

        $response = $this->post(route('book.edit', ['id' => "id{$book->id}"]), [
            'title' => $book->title,
            'status' => Book::STATUS_OPEN,
            'text' => $file
        ]);
        $response->assertStatus(Response::HTTP_FOUND);

        $this->assertGreaterThan(1, Page::where('book_id', $book->id)->count());

        //Тестируем для мёртвых душ
        $book_name = 'Гоголь_-_Мертвые_души.txt';
        $book_path = storage_path('seed/books/' . $book_name);
        $book_size = filesize(storage_path('seed/books/' . $book_name));
        $book_mime_type = File::mimeType(storage_path('seed/books/' . $book_name));

        $file = new UploadedFile($book_path, $book_name, $book_size, $book_mime_type, null, true);

        $response = $this->post(route('book.edit', ['id' => "id{$book->id}"]), [
            'title' => $book->title,
            'status' => Book::STATUS_OPEN,
            'text' => $file
        ]);
        $response->assertStatus(Response::HTTP_FOUND);

        $this->assertGreaterThan(1, Page::where('book_id', $book->id)->count());
    }

}
<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Exceptions\ApiException;
use App\Http\Middleware\Api\ApiWrapper;
use App\Http\Middleware\Api\CanUserLibraryBook;
use App\Http\Middleware\IsBookExist;
use App\Http\Middleware\IsUserAuth;
use Auth;
use Illuminate\Http\Response;

class LibraryBookController extends Controller
{
    public function __construct()
    {
        $this->middleware(IsUserAuth::class);

        $this->middleware(IsBookExist::class);

        $this->middleware(CanUserLibraryBook::class);

        $this->middleware(ApiWrapper::class);
    }

    /**
     * Добавить книгу в библиотеку.
     *
     * @param  int $id
     * @return array
     * @throws ApiException
     */
    public function create($id)
    {
        $book = Book::findAny($id);

        if (Auth::user()->hasBookAtLibrary($book)) {
            throw new ApiException(t('library.api', 'книга уже существует в библиотеке'), Response::HTTP_BAD_REQUEST);
        }

        Auth::user()->libraryBooks()->attach($book);

        return ['message' => t('library.api', 'книга была успешно добавлена в библиотеку')];
    }

    /**
     * Удалить книгу из библиотеки
     *
     * @param  int $id
     * @return array
     * @throws ApiException
     */
    public function destroy($id)
    {
        $book = Book::findAny($id);

        if (!Auth::user()->hasBookAtLibrary($book)) {
            throw new ApiException(t('library.api', 'книга не существует в библиотеке'), Response::HTTP_BAD_REQUEST);
        }

        Auth::user()->libraryBooks()->detach($book);

        return ['message' => t('library.api', 'книга была успешно удалена из библиотеки')];
    }
}

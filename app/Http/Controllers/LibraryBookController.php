<?php

namespace App\Http\Controllers;

use App\Book;
use App\Http\Middleware\Api\CanUserLibraryBook;
use App\Http\Middleware\IsBookExist;
use App\Http\Middleware\IsUserAuth;
use Auth;
use Illuminate\Http\JsonResponse;

class LibraryBookController extends Controller
{
    /** @var array $out */
    private $out = [
        'success' => true,
        'code' => 200,
        'data' => [
            'message' => ''
        ]
    ];

    public function __construct()
    {
        $this->middleware(IsUserAuth::class);

        $this->middleware(IsBookExist::class);

        $this->middleware(CanUserLibraryBook::class);
    }

    /**
     * Добавить книгу в библиотеку.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function create($id)
    {
        $book = Book::findAny($id);

        if (Auth::user()->hasBookAtLibrary($book)) {
            $this->out['success'] = false;
            $this->out['code'] = 400;
            $this->out['data']['message'] = t('library.api', 'книга уже существует в библиотеке');

            return response()->json($this->out);
        }

        Auth::user()->libraryBooks()->save($book);

        $this->out['success'] = true;
        $this->out['code'] = 200;
        $this->out['data']['message'] = t('library.api', 'книга была успешно добавлена в библиотеку');

        return response()->json($this->out);
    }

    /**
     * Удалить книгу из библиотеки
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $book = Book::findAny($id);

        if (!Auth::user()->hasBookAtLibrary($book)) {
            $this->out['success'] = false;
            $this->out['code'] = 400;
            $this->out['data']['message'] = t('library.api', 'книга не существует в библиотеке');

            return response()->json($this->out);
        }

        Auth::user()->getLibraryBook($book)
            ->pivot
            ->delete()
        ;

        $this->out['success'] = true;
        $this->out['code'] = 200;
        $this->out['data']['message'] = t('library.api', 'книга была успешно удалена из библиотеки');

        return response()->json($this->out);
    }
}

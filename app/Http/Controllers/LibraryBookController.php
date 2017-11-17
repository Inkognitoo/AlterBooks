<?php

namespace App\Http\Controllers;

use App\Book;
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
    }

    /**
     * Добавить книгу в библиотеку.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function create($id)
    {
        $book = Book::find($id);

        if (Auth::user()->hasBookAtLibrary($book)) {
            $this->out['code'] = 203;
            $this->out['data']['message'] = 'book already exist in the library';

            return response()->json($this->out);
        }

        Auth::user()->libraryBooks()->save($book);

        $this->out['data']['message'] = 'book was successfully added';

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
        $book = Book::find($id);

        if (!Auth::user()->hasBookAtLibrary($book)) {
            $this->out['success'] = false;
            $this->out['code'] = 403;
            $this->out['data']['message'] = 'book does not exist in the library';

            return response()->json($this->out);
        }

        Auth::user()->getLibraryBook($book)
            ->pivot
            ->delete()
        ;

        $this->out['data']['message'] = 'book was successfully deleted';

        return response()->json($this->out);
    }
}

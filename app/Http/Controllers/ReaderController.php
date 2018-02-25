<?php

namespace App\Http\Controllers;

use App\Book;
use App\Http\Middleware\CheckAuth;
use App\Http\Middleware\CheckUserBookGranted;
use App\Http\Requests\PageUpdateRequest;
use Exception;
use Illuminate\Http\Response;

class ReaderController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(CheckAuth::class)->except(['show']);

        $this->middleware(CheckUserBookGranted::class)->except(['show']);
    }

    /**
     * Возвращаем конкретную страницу книги
     *
     * @param int $id
     * @param int $page_number
     * @return Response
     * @throws Exception
     */
    public function show(int $id, int $page_number)
    {
        $book = Book::findAny($id);

        return view('book.reader.page', [
            'book' => $book,
            'current_page' => $page_number,
            'text' => $book->getPage($page_number)
        ]);
    }

    /**
     * Показываем страницу редактирования конкретной страницы книги
     *
     * @param int $id
     * @param int $page_number
     * @return Response
     * @throws Exception
     */
    public function editShow(int $id, int $page_number)
    {
        $book = Book::findAny($id);

        return view('book.reader.edit', [
            'book' => $book,
            'current_page' => $page_number,
            'text' => $book->getPage($page_number, false)
        ]);
    }

    /**
     * Редактируем конкретную страницу книги
     *
     * @param PageUpdateRequest $request
     * @param int $id
     * @param int $page_number
     * @return Response
     * @throws Exception
     */
    public function edit(PageUpdateRequest $request, int $id, int $page_number)
    {
        $book = Book::findAny($id);

        $book->editPage($page_number, $request->text);

        return redirect(route('book.page.show', ['id' => $id, 'current_page' => $page_number]))
            ->with(['status' => __('reader.success_update')]);
    }
}

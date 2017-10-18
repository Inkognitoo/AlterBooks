<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Storage;

class BookController extends Controller
{
    /**
     * Показываем профиль текущей книги.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return view('book.profile', [
            'book' => Book::find($id),
        ]);
    }

    /**
     * Показываем страницу редактирования профиля книги
     *
     * @param  int  $id
     * @return Response
     */
    public function editShow($id)
    {
        return view('book.edit', [
            'book' => Book::find($id)
        ]);
    }

    /**
     * Редактируем профиль книги
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function edit(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'nullable|max:255',
            'cover' => 'image|max:5120',
            'description' => 'nullable|max:5000',
        ]);

        if ($validator->fails()) {
            return redirect(route('book_edit_show', ['id' => $id]))
                ->withErrors($validator)
                ->withInput();
        }

        $book = Book::find($id);

        if (!empty($request['title'])) {
            $book->title = $request['title'];
        }
        if (!empty($request['cover'])) {
            $imageName = 'book_covers/' . $book->id . '/' . $book->avatar;
            if (Storage::disk('s3')->exists($imageName)) {
                Storage::disk('s3')->delete($imageName);
            }

            $imageName = 'book_covers/' . $book->id;
            $storagePath = Storage::disk('s3')->put($imageName, $request['cover']);
            $book->cover = basename($storagePath);
        }
        if (!empty($request['description'])) {
            $book->description = $request['description'];
        }

        $book->save();

        return view('book.edit', [
            'book' => $book
        ]);
    }

    /**
     * Показываем страницу создания профиля книги
     *
     * @return Response
     */
    public function createShow()
    {
        return view('book.create');
    }

    /**
     * Создаём профиль книги
     *
     * @@param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'cover' => 'image|max:5120',
            'description' => 'nullable|max:5000',
        ]);

        if ($validator->fails()) {
            return redirect(route('book_create_show'))
                ->withErrors($validator)
                ->withInput();
        }

        $book = new Book();

        $book->title = $request['title'];
        if (!empty($request['cover'])) {
            $imageName = 'book_covers/' . $book->id;
            $storagePath = Storage::disk('s3')->put($imageName, $request['cover']);
            $book->cover = basename($storagePath);
        }
        if (!empty($request['description'])) {
            $book->description = $request['description'];
        }
        Auth::user()->books()->save($book);

        $book->save();

        return redirect(route('book_show', ['id' => $book->id]));
    }
}

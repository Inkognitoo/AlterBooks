<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GenreUpdateRequest;
use App\Http\Requests\Admin\GenreCreateRequest;
use App\Models\Admin\Genre;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GenreController extends Controller
{
    /**
     * Показываем список жанров
     *
     * @return Response
     */
    public function index()
    {
        return view('admin.genre.list');
    }

    /**
     * Показываем конкретную рецензию
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function show(Request $request, $id)
    {
        $genre = Genre::withoutGlobalScopes()
            ->find($id)
        ;

        return view('admin.genre.show', ['genre' => $genre]);
    }

    /**
     * Показываем страницу создания жанра
     *
     * @return Response
     */
    public function createShow()
    {
        return view('admin.genre.create');
    }

    /**
     * Создаём жанр
     *
     * @@param GenreCreateRequest $request
     * @return Response
     */
    public function create(GenreCreateRequest $request)
    {
        $genre = new Genre($request->all());
        $genre->save();

        return redirect(route('genre.show', ['genre' => $genre]))
            ->with('status', 'Жанр был успешно создан')
            ;
    }

    /**
     * Показываем интерфейс редактирования конкретного жанра
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function edit(Request $request, $id)
    {
        $genre = Genre::withoutGlobalScopes()
            ->find($id)
        ;

        return view('admin.genre.edit', ['genre' => $genre]);
    }

    /**
     * Обновляем информацию о жанре
     *
     * @param GenreUpdateRequest $request
     * @param $id
     * @return Response
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function update(GenreUpdateRequest $request, $id)
    {
        $genre = Genre::withoutGlobalScopes()
            ->find($id)
        ;

        $genre->fill($request->all());
        $genre->save();

        return redirect(route('genre.edit.show', ['id' => $genre->id]))
            ->with('status', 'Данные были успешно обновлены')
        ;
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReviewUpdateRequest;
use App\Http\Requests\Admin\ReviewCreateRequest;
use App\Models\Admin\Review;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReviewController extends Controller
{
    /**
     * Показываем список рецензий
     *
     * @return Response
     */
    public function index()
    {
        return view('admin.review.list');
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
        $review = Review::withoutGlobalScopes()
            ->find($id)
        ;

        return view('admin.review.show', ['review' => $review]);
    }

    /**
     * Показываем страницу создания рецензии
     *
     * @return Response
     */
    public function createShow()
    {
        return view('admin.review.create');
    }

    /**
     * Создаём рецензию
     *
     * @@param ReviewCreateRequest $request
     * @return Response
     */
    public function create(ReviewCreateRequest $request)
    {
        $review = new Review($request->all());
        $review->save();

        return redirect(route('review.show', ['review' => $review]))
            ->with('status', 'Рецензия была успешно создана')
        ;
    }

    /**
     * Показываем интерфейс редактирования конкретной рецензии
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function edit(Request $request, $id)
    {
        $review = Review::withoutGlobalScopes()
            ->find($id)
        ;

        return view('admin.review.edit', ['review' => $review]);
    }

    /**
     * Обновляем информацию о рецензии
     *
     * @param ReviewUpdateRequest $request
     * @param $id
     * @return Response
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function update(ReviewUpdateRequest $request, $id)
    {
        $review = Review::withoutGlobalScopes()
            ->find($id)
        ;

        $review->fill($request->all());
        $review->save();

        return redirect(route('review.edit.show', ['id' => $review->id]))
            ->with('status', 'Данные были успешно обновлены')
        ;
    }
}

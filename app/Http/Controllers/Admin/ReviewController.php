<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
}

<?php

namespace App\Http\Controllers\Admin\API;

use App\Models\Admin\Search\ReviewSearch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
    /**
     * Получить отфильтрованный список рецензий
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function index(Request $request)
    {
        return (new ReviewSearch())->apply($request);
    }
}

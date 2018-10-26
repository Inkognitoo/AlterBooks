<?php

namespace App\Http\Controllers\Admin\API;

use App\Models\Admin\Search\ReviewSearch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
    /** @var ReviewSearch */
    private $review_search;

    /**
     * ReviewController constructor.
     * @param ReviewSearch $review_search
     */
    public function __construct(ReviewSearch $review_search)
    {
        $this->review_search = $review_search;
    }

    /**
     * Получить отфильтрованный список рецензий
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function index(Request $request)
    {
        return $this->review_search->apply($request);
    }
}

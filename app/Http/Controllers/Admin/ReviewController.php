<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DashboardController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Показываем основную страницу dashboard
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        return view('admin.dashboard');
    }
}

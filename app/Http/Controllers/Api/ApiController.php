<?php

namespace App\Http\Controllers\Api;

use App\Http\Middleware\Api\ApiWrapper;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    /**
     * Выполняем базовые действия для каждого api контроллера
     *
     * ApiController constructor.
     */
    public function __construct()
    {
        $this->middleware(ApiWrapper::class);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Middleware\IsUserNotAuth;
use App\Http\Requests\Api\RegisterValidateRequest;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Api\ApiWrapper;

class RegistrationController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(IsUserNotAuth::class)->only('validate');

        $this->middleware(ApiWrapper::class);
    }

    /**
     * Валидируем запрос
     *
     * @param RegisterValidateRequest $request
     */
    public function validator(RegisterValidateRequest $request)
    {

    }
}

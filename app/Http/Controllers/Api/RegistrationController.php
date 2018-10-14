<?php

namespace App\Http\Controllers\Api;

use App\Http\Middleware\IsUserNotAuth;
use App\Http\Requests\Api\RegistrationValidateRequest;
use Auth;
use App\Http\Controllers\Controller;
use App\Exceptions\ApiException;
use App\Http\Middleware\Api\ApiWrapper;
use App\Http\Requests\ReviewCreateRequest;

class RegistrationController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(IsUserNotAuth::class)->only('create');

        $this->middleware(ApiWrapper::class);
    }

    /**
     * Валидируем запрос
     *
     * @param RegistrationValidateRequest $request
     * @return array
     * @throws \Exception
     */
    public function create(RegistrationValidateRequest $request)
    {
        switch ($request->name) {
            case 'email':
                $validateResult = $request->validate([
                    'value' => 'unique:connection.users,email'
                ]);
                break;
        }

        $response = [
            'success' => true,
            'data' => null,
            'errors' => [],
        ];

        return $response;
    }
}

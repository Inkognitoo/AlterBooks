<?php

namespace App\Http\Requests\Api;

use App\Exceptions\ApiException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Response;

/**
 * Родительский класс для валидации зарпосов к api
 *
 * Class ApiRequest
 * @package App\Http\Requests\Api
 */
class ApiRequest extends FormRequest
{
    /**
     * Переопределяем кидающий исключения метод для корректорной работы с нашим ApiWrapper
     *
     * @param Validator $validator
     * @throws ApiException
     * @throws ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        $error_code = Response::HTTP_BAD_REQUEST;

        $previous_exception = null;
        $exception = null;
        foreach ($errors as $error) {
            $exception = new ApiException(implode(';', $error), $error_code, $previous_exception);
            $previous_exception = $exception;
        }

        if ($exception instanceof ApiException) {
            throw $exception;
        }

        parent::failedValidation($validator);
    }

}

<?php

namespace App\Http\Requests\Api;

use App\Exceptions\ValidateException;
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
     * Переопределяем кидающий исключения метод для корректной работы с нашим ApiWrapper
     *
     * @param Validator $validator
     * @throws ValidationException
     * @throws ValidateException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        $error_code = Response::HTTP_BAD_REQUEST;

        $previous_exception = null;
        $exception = null;
        foreach ($errors as $error_name => $error_text) {
            $exception = new ValidateException($error_name, implode(';', $error_text), $error_code, $previous_exception);
            $previous_exception = $exception;
        }
        if ($exception instanceof ValidateException) {
            throw $exception;
        }

        parent::failedValidation($validator);
    }

}

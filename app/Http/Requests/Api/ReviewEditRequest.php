<?php

namespace App\Http\Requests\Api;


use App\Exceptions\ApiException;
use App\Exceptions\ValidateException;
use App\Rules\CaseInsensitiveUnique;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Response;

/**
 * Проверяем данные для редактирования рецензии
 *
 * Class RegisterValidateRequest
 *
 * @property string $email
 * @property string $password
 * @property string $nickname
 */
class ReviewEditRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'rating' => [
                'required',
                'integer',
                'between:1,10'
            ],
            'header' => [
                'required',
                'max:67'
            ],
            'text' => [
                'required',
                'max:2000'
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'rating.required' => 'оценка книги обязательна для заполнения',
            'rating.integer' => 'оценка книги должна быть целым числом',
            'rating.between' => 'дайте книге оценку от :min до :max',
            'header.required' => 'заголовок рецензии обязателен для заполнения',
            'header.max' => 'заголовок рецензии слишком длинных (более :max символов)',
            'text.required' => 'текст рецензии обязателен для заполнения',
            'text:max' => 'текст рецензии слишком длинный (более :max символов)'
        ];
    }
}

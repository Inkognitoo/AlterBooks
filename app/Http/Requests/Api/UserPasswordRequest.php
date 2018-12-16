<?php

namespace App\Http\Requests\Api;

/**
 * Проверяем данные для редактирования рецензии
 *
 * Class RegisterValidateRequest
 *
 * @property int $rating
 * @property string $text
 * @property string $header
 */
class UserPasswordRequest extends ApiRequest
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
            'password' => [
                'min:6'
            ],
        ];
    }
}

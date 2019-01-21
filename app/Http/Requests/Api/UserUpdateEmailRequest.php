<?php

namespace App\Http\Requests\Api;

use App\Rules\CaseInsensitiveUnique;
use Auth;

/**
 * Валидируем запрос на обновление пользователя
 *
 * App\Http\Requests\UserUpdateRequest
 *
 * @property string $email
 * @property string $password
 * @property string $old_password
 */
class UserUpdateEmailRequest extends ApiRequest
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
            'email' => [
                'required',
                'email',
                'max:255',
                (new CaseInsensitiveUnique('users'))->ignore(Auth::user()->id)
            ],
            'password' => [
                'nullable',
                'min:6',
                'confirmed'
            ],
            'password_confirmation' => [
                'min:6'
            ],
            'old_password' => [
                'required',
                'current_password'
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
            'email.required' => 'адрес электронной почты обязателен для заполнения',
            'email.email' => 'введенная строка не является адресом электронной почты',
            'email.max' => 'адрес электронной почты слишком длинный (более :max символов)',
            'email.unique' => 'данный адрес электронной почты уже используется',
            'password.min' => 'пароль слишком короткий (менее :min символов)',
            'password.confirmed' => 'введите пароль повторно для подтверждения',
            'password_confirmation.min' => 'повтор пароля слишком короткий (менее :min символов)',
            'old_password.required' => 'подтвердите изменение данных авторизации текущим паролем',
            'old_password.current_password' => 'указан неверный текущий пароль'
        ];
    }
}

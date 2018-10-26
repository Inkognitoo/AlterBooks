<?php

namespace App\Http\Requests\Api;


use App\Exceptions\ApiException;
use App\Exceptions\ValidateException;
use App\Rules\CaseInsensitiveUnique;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Response;

/**
 * Валидируем данные для регистрации
 *
 * Class RegisterValidateRequest
 *
 * @property string $email
 * @property string $password
 * @property string $nickname
 */
class RegisterValidateRequest extends ApiRequest
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
                'nullable',
                'max:255',
                'email',
                (new CaseInsensitiveUnique('users'))->setMessage('профиль с данными адресом электронной почты уже существует'),
            ],
            'password' =>[
                'nullable',
                'min:6',
                'max:255',
            ],
            'nickname' => [
                'nullable',
                'max:255',
                (new CaseInsensitiveUnique('users'))->setMessage('пользователь с данным псевдонимом уже существует'),
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
            'email.max' => 'адрес электронной почты слишком длинный',
            'email.email' => 'введённая строка не является адресом электронной почты',
            'email.unique' => 'профиль с данными адресом электронной почты уже существует',
            'password.min' => 'пароль менее :min символов',
            'password.max' => 'пароль слишком длинный',
            'nickname.max' => 'псевдоним слишком длинный',
            'nickname.unique' => 'пользователь с данным псевдонимом уже существует'
        ];
    }
}

<?php

namespace App\Http\Requests;

use App\User;
use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;

/**
 * Валидируем запрос на обновление пользователя
 *
 * App\Http\Requests\UserUpdateRequest
 *
 * @property string $nickname
 * @property UploadedFile|null $avatar
 * @property string|null $name
 * @property string|null $surname
 * @property string|null $patronymic
 * @property string $email
 * @property string $password
 * @property string $gender
 * @property string|null $birthday_date
 * @property string $timezone
 */
class UserUpdateRequest extends FormRequest
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
            'nickname' => [
                'required',
                'max:255',
                Rule::unique('users')->ignore(Auth::user()->id)
            ],
            'avatar' => 'image|max:5120',
            'name' => 'nullable|max:255',
            'surname' => 'nullable|max:255',
            'patronymic' => 'nullable|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore(Auth::user()->id)
            ],
            'password' => 'nullable|min:6|confirmed',
            'gender' => [
                'required',
                Rule::in([User::GENDER_MALE, User::GENDER_FEMALE, User::GENDER_NOT_INDICATED]),
            ],
            'birthday_date' => 'nullable|date',
            'about' => 'nullable|max:5000',
            'timezone' => [
                'required',
                Rule::in(config('app.timezones-UTC')),
            ],
        ];
    }
}

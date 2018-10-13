<?php

namespace App\Http\Requests\Admin;

use App\Rules\CaseInsensitiveUnique;
use App\Rules\Nickname;
use App\Models\User;
use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;

/**
 * Валидируем запрос на создание пользователя
 *
 * App\Http\Requests\UserCreateRequest
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
class UserCreateRequest extends FormRequest
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
        $this['is_admin'] = $this->has('is_admin');

        return [
            'nickname' => [
                'required',
                'max:255',
                'unique:users',
                new Nickname(),
            ],
            'avatar' => 'image|max:5120',
            'name' => 'nullable|max:255',
            'surname' => 'nullable|max:255',
            'patronymic' => 'nullable|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|min:6',
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
            'is_admin'
        ];
    }
}

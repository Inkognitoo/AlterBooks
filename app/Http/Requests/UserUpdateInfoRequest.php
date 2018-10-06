<?php

namespace App\Http\Requests;

use App\Rules\CaseInsensitiveUnique;
use App\Rules\Nickname;
use App\Models\User;
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
 * @property string|null $name
 * @property string|null $surname
 * @property string|null $patronymic
 * @property string $gender
 * @property string|null $birthday_date
 */
class UserUpdateInfoRequest extends FormRequest
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
                (new CaseInsensitiveUnique('users'))->ignore(Auth::user()->id),
                new Nickname(),
            ],
            'name' => 'nullable|max:255',
            'surname' => 'nullable|max:255',
            'patronymic' => 'nullable|max:255',
            'gender' => [
                'required',
                Rule::in([User::GENDER_MALE, User::GENDER_FEMALE, User::GENDER_NOT_INDICATED]),
            ],
            'birthday_date' => 'nullable|date',
            'about' => 'nullable|max:5000',
        ];
    }
}

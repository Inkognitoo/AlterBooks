<?php

namespace App\Http\Requests\Api;

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
class UserUpdateInfoRequest extends ApiRequest
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
                'max:30',
                (new CaseInsensitiveUnique('users'))->ignore(Auth::user()->id)->setMessage('этот псевдоним уже занят'),
                new Nickname(),
            ],
            'name' => 'nullable|max:30',
            'surname' => 'nullable|max:30',
            'patronymic' => 'nullable|max:30',
            'gender' => [
                'required',
                Rule::in([User::GENDER_MALE, User::GENDER_FEMALE, User::GENDER_NOT_INDICATED]),
            ],
            'birthday_date' => 'nullable|date',
            'about' => 'nullable|max:5000',
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
            'nickname.required' => 'каждому писателю или читателю необходим псевдоним',
            'nickname.max' => 'псевдоним слишом длинный (более :max сиволов)',
            'nickname.unique' => 'этот псевдоним уже занят',
            'name.max' => 'имя слишком длинное (более :max сиволов)',
            'surname.max' => 'фамилия слишком длинная (более :max символов)',
            'patronymic.max' => 'отчество слишком длинное (более :max сиволов)',
            'gender.required' => 'вам следует определиться с полом',
            'gender.in' => 'указанного вами пола не существует',
            'birthday_date.date' => 'введенное данное не является датой',
            'about.max' => 'описание слишком длинное (более :max сиволов)'
        ];
    }
}

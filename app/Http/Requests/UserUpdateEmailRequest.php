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
 * @property string $email
 * @property string $password
 * @property string $old_password
 */
class UserUpdateEmailRequest extends FormRequest
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
            'password' => 'nullable|min:6|confirmed',
            'old_password' => [
                'required',
                'current_password'
            ],
        ];
    }
}

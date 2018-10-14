<?php

namespace App\Http\Requests\Api;


/**
 * Валидируем зарпос на получение списка книг
 *
 * Class BookSearchRequest
 *
 * @property string $title
 * @property integer $perPage
 * @property integer $currentPage
 * @property null|array $genres
 */
class RegistrationValidateRequest extends ApiRequest
{
    /**
     * Determine if the user is not authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'value' => 'required|max:255'
        ];
    }
}

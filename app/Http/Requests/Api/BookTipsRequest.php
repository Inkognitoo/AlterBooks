<?php

namespace App\Http\Requests\Api;


/**
 * Валидация запроса на получение подсказок для названия книг
 *
 * Class BookTipsRequest
 *
 * @property string $title
 */
class BookTipsRequest extends ApiRequest
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
            'title' => 'required|max:255',
        ];
    }
}

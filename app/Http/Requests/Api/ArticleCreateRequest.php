<?php

namespace App\Http\Requests\Api;

class ArticleCreateRequest extends ApiRequest
{
    /**
     * Валидируем запрос на создание статьи блога
     *
     * App\Http\Requests\ArticleCreateRequest
     *
     * @property string $title
     * @property string $text
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
            'text' => 'required'
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
            'title.required' => 'заголовок статьи блога обязателен для заполнения',
            'title.max' => 'заголовок статьи блога слишком длинный (более :max символов)',
            'text.required' => 'текст статьи блога обязателен для заполнения'
        ];
    }
}

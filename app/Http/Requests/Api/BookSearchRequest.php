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
 * @property string $sort
 */
class BookSearchRequest extends ApiRequest
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
            'title' => 'nullable|max:255',
            'perPage' => 'nullable|integer',
            'currentPage' => 'nullable|integer',
            'genres' => 'nullable|array',
            'genres.*' => ['exists:genres,slug']
        ];
    }
}

<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Валидируем запрос на обновление жанра
 *
 * App\Http\Requests\ReviewUpdateRequest
 *
 * @property string $name
 * @property string $slug
 */
class GenreUpdateRequest extends FormRequest
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
        $id = $this->route('id');

        return [
            'name' => 'required|max:255',
            'slug' => [
                'required',
                'max:255',
                Rule::unique('genres')->ignore($id),
            ],
        ];
    }
}

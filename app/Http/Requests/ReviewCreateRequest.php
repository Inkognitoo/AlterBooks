<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Валидируем запрос на создание рецензии
 *
 * App\Http\Requests\ReviewCreateRequest
 *
 * @property int $rating
 * @property string $text
 * @property string $header
 */
class ReviewCreateRequest extends FormRequest
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
            'rating' => 'required|integer|between:1,10',
            'text' => 'required|max:2000',
            'header' => 'required|max:67',
        ];
    }
}

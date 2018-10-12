<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Валидируем запрос на обновление рецензии
 *
 * App\Http\Requests\ReviewUpdateRequest
 *
 * @property integer $rating
 * @property string $description
 * @property integer $user_id
 * @property integer $book_id
 */
class ReviewUpdateRequest extends FormRequest
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
            'rating' => 'required|integer|between:1,9',
            'text' => 'required|max:5000',
            'user_id' => 'exists:users,id',
            'book_id' => 'exists:books,id',
        ];
    }
}

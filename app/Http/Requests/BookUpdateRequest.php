<?php

namespace App\Http\Requests;

use App\Book;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;

/**
 * Валидируем запрос на обновление книги
 *
 * App\Http\Requests\BookUpdateRequest
 *
 * @property string $title
 * @property UploadedFile|null $cover
 * @property string|null $description
 * @property UploadedFile|null $text
 * @property string $status
 */
class BookUpdateRequest extends FormRequest
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
            'cover' => 'image|max:5120',
            'description' => 'nullable|max:5000',
            'text' => 'nullable|file|mimes:txt|mimetypes:text/plain',
            'status' => [
                'required',
                Rule::in([Book::OPEN_STATUS, Book::CLOSE_STATUS]),
            ],
        ];
    }
}

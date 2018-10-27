<?php

namespace App\Http\Requests;

use App\Models\Book;
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
 * @property null|array $genres
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
            'title' => 'required|max:50',
            'cover' => 'image|max:5120',
            'description' => 'nullable|max:5000',
            'text' => 'nullable|file|mimes:txt|mimetypes:text/plain',
            'status' => [
                'required',
                Rule::in([Book::STATUS_OPEN, Book::STATUS_CLOSE]),
            ],
            'genres' => 'nullable|array',
            'genres.*' => ['exists:genres,slug']
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
            'title.required' => 'Название книги не может быть пустым',
            'title.max' => 'Название книги должно содержать менее 50 символов',
            'description.max' => 'Описание книги не должно содержать более 5000 символов',
        ];
    }
}

<?php

namespace App\Http\Requests\Api;

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
class BookUpdateRequest extends ApiRequest
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
            'title' => [
                'required',
                'max:50'
            ],
            'cover' => [
                'nullable',
                'image',
                'max:5120'
            ],
            'description' => [
                'nullable',
                'max:5000'
            ],
            'text' => [
                'nullable',
                'file',
                'mimes:txt',
                'mimetypes:text/plain'
            ],
            'status' => [
                'required',
                Rule::in([Book::STATUS_OPEN, Book::STATUS_CLOSE]),
            ],
            'genres' => [
                'nullable',
                'array'
            ],
            'genres.*' => [
                'exists:genres,slug'
            ]
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
            'title.required' => 'дайте книге название',
            'title.max' => 'название книги слишком длинное (более :max сиволов)',
            'description.max' => 'описание книги слишком длинное (более :max сиволов)',
            'text.file' => 'текст книги должен быть файлом',
            'text.mimes' => 'загрузите текст книги в формате :mimes',
            'status.required' => 'следует указать, в каком статусе находится книга',
            'status.in' => 'не существует такого статуса книги',
            'genres.*.exist' => 'одного из указанных вами жанров не существует',
        ];
    }
}

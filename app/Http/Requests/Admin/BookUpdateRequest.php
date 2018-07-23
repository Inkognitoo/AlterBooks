<?php

namespace App\Http\Requests\Admin;

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
 * @property string $slug
 * @property UploadedFile|null $cover
 * @property string|null $description
 * @property integer $author_id
 * @property UploadedFile|null $text
 * @property string $status
 * @property array $genres
 * @property integer $page_count
 * @property boolean $is_processing
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
        if ($this->has('is_processing')) {
            $this['is_processing'] = true;
        } else {
            $this['is_processing'] = false;
        }

        return [
            'title' => 'required|max:255',
            'slug' => 'required|max:255',
            'cover' => 'image|max:5120',
            'description' => 'nullable|max:5000',
            'author_id' => 'exists:users,id',
            'text' => 'nullable|file|mimes:txt|mimetypes:text/plain',
            'status' => [
                'required',
                Rule::in([Book::STATUS_OPEN, Book::STATUS_CLOSE]),
            ],
            'genres' => 'nullable|array',
            'genres.*' => ['exists:genres,slug'],
            'page_count' => 'integer',
            'is_processing'
        ];
    }
}

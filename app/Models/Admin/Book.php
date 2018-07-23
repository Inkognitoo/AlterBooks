<?php

namespace App\Models\Admin;

use App\Models\Book as BaseBook;
use App\Traits\Admin\Attributes;
use App\Models\Genre;

/**
 * Class Book
 *
 * @package App\Models\Admin
 */
class Book extends BaseBook
{
    use Attributes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'slug', 'description', 'cover', 'author_id', 'page_count', 'genres', 'status', 'is_processing',
    ];

    /**
     * @var array $safe_attributes Список атрибутов для отображения
     */
    protected $safe_attributes = [
        'id', 'title', 'slug', 'description', 'cover', 'author_id', 'genres', 'page_count', 'mongodb_book_id',
        'status', 'is_processing', 'created_at', 'updated_at'
    ];

    /**
     * @var array $boolean_fields Список атрибутов, которым нужно отображать булево поле
     */
    protected $boolean_fields = ['is_processing'];

    /**
     * @var array $image_fields Список атрибутов, которым нужно отображать img поле
     */
    protected $image_fields = ['cover'];

    /**
     * @var array $number_edit_fields Список атрибутов, которым нужно отображать поля редактирования целочисленных данных
     */
    protected $number_edit_fields = ['page_count'];

    /**
     * @var array $disabled_edit_fields Список атрибутов, которым нужно отображать неактивные поля редактирования
     */
    protected $disabled_edit_fields = ['id', 'mongodb_book_id', 'updated_at', 'created_at'];

    /**
     * @var array $area_edit_fields Список атрибутов, которым нужно отображать широкие поля редактирования
     */
    protected $area_edit_fields = ['description'];

    /**
     * @var array $checkbox_edit_fields Список атрибутов, которым нужно отображать checkbox-ы
     */
    protected $checkbox_edit_fields = ['is_processing'];

    /**
     * @var array $file_edit_fields Список атрибутов, которым нужно отображать поля для ввода файлов
     */
    protected $file_edit_fields = ['cover'];

    /**
     * @var array $list_edit_fields Список атрибутов, которым нужно отображать выпадающий список
     */
    protected $list_edit_fields = []; // Заполняется в конструкторе

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->list_edit_fields = [
            'status' => [
                'Опубликовано' => self::STATUS_OPEN,
                'Черновик' => self::STATUS_CLOSE,
            ],
        ];
    }

    /**
     * html для отображения автора
     *
     * @return string
     */
    protected function getHtmlViewForAuthorId(): string
    {
        return sprintf('<p>%s (<a href="%s">%s</a>)</p>',
            $this->author_id,
            route('user.show', ['id' => $this->author_id]),
            $this->author->full_name
        );
    }

    /**
     * html для отображения списка жанров
     *
     * @return string
     */
    protected function getHtmlViewForGenres(): string
    {
        $genres = [];

        foreach ($this->genres as $genre) {
            $genres[] = sprintf('<span class="m-badge m-badge--primary m-badge--wide">%s</span>', $genre->name);
        }

        return implode(PHP_EOL, $genres);
    }

    /**
     * html для отображения статуса
     *
     * @return string
     */
    protected function getHtmlViewForStatus(): string
    {
        $status_string = '';

        switch ($this->status) {
            case self::STATUS_OPEN:
                $status_string = 'Опубликовано';
                break;
            case self::STATUS_CLOSE:
                $status_string = 'Черновик';
                break;
        }

        return sprintf('<p>%s (%s)</p>', $this->status, $status_string);
    }

    /**
     * html для отображения списка редактирования жанров
     *
     * @return string
     */
    protected function getHtmlEditForGenres(): string
    {
        $genres = [];

        foreach (Genre::all() as $genre) {
            $genres[] = sprintf('
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="genres[]"
                               value="%s"
                               %s >
                        %s
                    </label>
                </div>
            ', $genre->slug, $this->hasGenre($genre) ? 'checked' : null, $genre->name);
        }

        return implode(PHP_EOL, $genres);
    }
}
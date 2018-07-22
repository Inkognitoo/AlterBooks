<?php

namespace App\Models\Admin;

use App\Models\Book as BaseBook;
use App\Traits\Admin\Attributes;

/**
 * Class Book
 *
 * @package App\Models\Admin
 */
class Book extends BaseBook
{
    use Attributes;

    /**
     * @var array $safe_attributes Список атрибутов для отображения
     */
    protected $safe_attributes = [
        'id', 'title', 'slug', 'description', 'cover', 'author_id', 'page_count', 'mongodb_book_id',
        'status', 'is_processing', 'deleted_at', 'created_at', 'updated_at'
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
}
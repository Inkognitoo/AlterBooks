<?php

namespace App\Models\Admin;

use App\Models\Review as BaseReview;
use App\Traits\Admin\Attributes;

/**
 * Class Review
 *
 * @package App\Models\Admin
 */
class Review extends BaseReview
{
    use Attributes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rating', 'text', 'user_id', 'book_id'
    ];

    /**
     * @var array $safe_attributes Список атрибутов для отображения
     */
    protected $safe_attributes = [
        'id', 'rating', 'text', 'user_id', 'book_id', 'deleted_at', 'created_at', 'updated_at',
    ];

    /**
     * @var array $number_edit_fields Список атрибутов которым нужно отображать числовое поле редактирования
     */
    protected $number_edit_fields = ['rating'];

    /**
     * @var array $disabled_edit_fields Список атрибутов, которым нужно отображать неактивные поля редактирования
     */
    protected $disabled_edit_fields = ['id', 'deleted_at', 'updated_at', 'created_at'];

    /**
     * @var array $area_edit_fields Список атрибутов, которым нужно отображать широкие поля редактирования
     */
    protected $area_edit_fields = ['text'];

    /**
     * @var array $number_create_fields Список атрибутов которым нужно отображать числовое поле создания
     */
    protected $number_create_fields = ['rating'];

    /**
     * @var array $area_create_fields Список атрибутов, которым нужно отображать широкие поля создания
     */
    protected $area_create_fields = ['text'];

    /**
     * Книга, к которой оставлена рецензия
     */
    public function book()
    {
        return $this->belongsTo(Book::class)
            ->withoutGlobalScopes()
        ;
    }

    /**
     * html для отображения автора
     *
     * @return string
     */
    protected function getHtmlViewForUserId(): string
    {
        return sprintf('<p>%s (<a href="%s">%s</a>)</p>',
            $this->user_id,
            route('user.show', ['id' => $this->user_id]),
            $this->user->full_name
        );
    }

    /**
     * html для отображения книги
     *
     * @return string
     */
    protected function getHtmlViewForBookId(): string
    {
        return sprintf('<p>%s (<a href="%s">%s</a>)</p>',
            $this->book_id,
            route('book.show', ['id' => $this->book_id]),
            $this->book->title
        );
    }
}
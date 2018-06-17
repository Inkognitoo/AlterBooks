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
     * Вернуть html для отображения автора
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
     * Вернуть html для отображения книги
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
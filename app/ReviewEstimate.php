<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ReviewEstimate
 *
 * @property-read \App\Review $review
 * @property-read \App\User $user
 * @mixin \Eloquent
 */
class ReviewEstimate extends Model
{
    /**
     * Получить хозяина оценки
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Получить рецензию, К которой оставлена оценка
     */
    public function review()
    {
        return $this->belongsTo('App\Review');
    }
}

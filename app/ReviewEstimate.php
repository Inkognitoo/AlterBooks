<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ReviewEstimate
 *
 * @property int $id
 * @property int $user_id
 * @property int $review_id
 * @property int $estimate
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Review $review
 * @property-read \App\User $user
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReviewEstimate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReviewEstimate whereEstimate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReviewEstimate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReviewEstimate whereReviewId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReviewEstimate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReviewEstimate whereUserId($value)
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

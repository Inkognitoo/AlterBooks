<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ReviewEstimate
 *
 * @property int $id
 * @property int $user_id
 * @property int $review_id
 * @property int $estimate
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Review $review
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReviewEstimate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReviewEstimate whereEstimate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReviewEstimate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReviewEstimate whereReviewId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReviewEstimate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReviewEstimate whereUserId($value)
 * @mixin \Eloquent
 */
class ReviewEstimate extends Model
{
    /**
     * Пользователь оставивший оценку
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Рецензия, к которой оставлена оценка
     */
    public function review()
    {
        return $this->belongsTo(Review::class);
    }
}

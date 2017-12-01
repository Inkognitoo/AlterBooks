<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

/**
 * App\Review
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $rating
 * @property string $text Текст рецензии с переводами строки заменёными на <br>
 * @property string $text_plain Текст рецензии как он есть в бд
 * @property int $user_id
 * @property int $book_id
 * @property \Carbon\Carbon|null $deleted_at
 * @property \Carbon\Carbon|null $created_at Дата создания сущности в соотвествии с часовым поясом пользователя
 * @property \Carbon\Carbon|null $created_at_plain Дата создания сущности как она есть в бд
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Book $book
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Query\Builder|\App\Review onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Review whereBookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Review whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Review whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Review whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Review whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Review whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Review whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Review whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Review withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Review withoutTrashed()
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ReviewEstimate[] $reviewEstimates
 * @method static bool|null forceDelete()
 */
class Review extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rating', 'text'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Получить автора рецензии
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Получить книгу, к которой оставлена рецензия
     */
    public function book()
    {
        return $this->belongsTo('App\Book');
    }

    /**
     * Получить все оценки текущей рецензии
     */
    public function reviewEstimates()
    {
        return $this->hasMany('App\ReviewEstimate', 'review_id');
    }

    /**
     * Сохранить текст рецензии с экранированием опасных символов
     *
     * @param string $value
     */
    public function setTextAttribute($value)
    {
        $this->attributes['text'] = htmlspecialchars($value, ENT_HTML5);
    }

    /** Вывести текст рецензии, заменяя переводы строки на <br>
     *
     * @param string $value
     * @return string
     */
    public function getTextAttribute($value)
    {
        $pattern = '/(\r\n)/i';
        $replacement = '<br>';

        return preg_replace($pattern, $replacement, $value);
    }

    /**
     * Вывести текст рецензии как есть
     *
     * @return string
     */
    public function getTextPlainAttribute()
    {
        return $this->attributes['text'];
    }

    /**
     * Вывести дату создания рецензии в соответствии с часовым поясом
     *
     * @param string $value
     * @return Carbon
    */
    public function getCreatedAtAttribute($value)
    {
        $date_time = new Carbon($value, config('app.timezone'));
        if (Auth::check()) {
            $date_time->timezone = Auth::user()->timezone;
        }

        return $date_time;
    }

    /**
     * Дата создания сущности, как она есть в бд
     *
     * @return Carbon
     */
    public function getCreatedAtPlainAttribute()
    {
        return new Carbon($this->attributes['created_at']);
    }
}

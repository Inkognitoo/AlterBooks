<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

/**
 * App\Review
 *
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ReviewEstimate[] $reviewEstimates
 * @property-read \App\Book $book
 * @property-read \App\User $user
 * @property-read int $estimate Совокупная оценка рецензии
 * @method static \Illuminate\Database\Query\Builder|\App\Review onlyTrashed()
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
 * @method static bool|null forceDelete()
 * @method static bool|null restore()
 * @mixin \Eloquent
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
     * Автор рецензии
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Книга, к которой оставлена рецензия
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Все оценки текущей рецензии
     */
    public function reviewEstimates()
    {
        return $this->hasMany(ReviewEstimate::class, 'review_id');
    }

    /**
     * Оценка рецензии конкретным пользователем, если таковая имеется
     *
     * @param User $user
     * @return ReviewEstimate | null
     */
    public function usersEstimate(User $user)
    {
        return $this->reviewEstimates()
            ->where('user_id', '=', $user->id)
            ->first()
            ;
    }

    /**
     * Проверить, является ли конкретный пользователь автором рецензии
     *
     * @param User $user
     * @return boolean
     */
    public function isAuthor(User $user)
    {
        return ($this->user_id === $user->id);
    }

    /**
     * Проверить, оставлена ли рецензия к одной из книг конкретного пользователя
     *
     * @param User $user
     * @return boolean
     */
    public function isForBookOfUser(User $user)
    {
        return ($this->book->author_id === $user->id);
    }

    /** Текст рецензии, с переводами строки заменёнными на <br>
     *
     * @param string $text
     * @return string
     */
    public function getTextAttribute($text)
    {
        $pattern = '/(\r\n)/i';
        $replacement = '<br>';

        return preg_replace($pattern, $replacement, $text);
    }

    /**
     * Сохранить текст рецензии с экранированием опасных символов
     *
     * @param string $text
     */
    public function setTextAttribute($text)
    {
        $this->attributes['text'] = htmlspecialchars($text, ENT_HTML5);
    }

    /**
     * Текст рецензии как есть
     *
     * @return string
     */
    public function getTextPlainAttribute()
    {
        return $this->attributes['text'];
    }

    /**
     * Дата создания рецензии в соответствии с часовым поясом
     *
     * @param string $created_at
     * @return Carbon
    */
    public function getCreatedAtAttribute($created_at)
    {
        $date_time = new Carbon($created_at, config('app.timezone'));
        if (Auth::check()) {
            $date_time->timezone = Auth::user()->timezone;
        }

        return $date_time;
    }

    /**
     * Дата создания рецензии, как она есть
     *
     * @return Carbon
     */
    public function getCreatedAtPlainAttribute()
    {
        return new Carbon($this->attributes['created_at']);
    }

    /**
     * Совокупная оценка рецензии
     *
     * @return int
     */
    public function getEstimateAttribute()
    {
        return $this->reviewEstimates->sum('estimate');
    }

}

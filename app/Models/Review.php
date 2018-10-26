<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

/**
 * App\Models\Review
 *
 * @property int $id
 * @property int $rating
 * @property string $text Текст рецензии с переводами строки заменёными на <br>
 * @property string $text_plain Текст рецензии как он есть в бд
 * @property int $user_id
 * @property int $book_id
 * @property string $header Заголовок рецензии
 * @property \Carbon\Carbon|null $deleted_at
 * @property \Carbon\Carbon|null $created_at Дата создания сущности в соотвествии с часовым поясом пользователя
 * @property \Carbon\Carbon|null $created_at_plain Дата создания сущности как она есть в бд
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ReviewEstimate[] $reviewEstimates
 * @property-read \App\Models\Book $book
 * @property-read \App\Models\User $user
 * @property-read int $estimate Совокупная оценка рецензии
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Review onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Review whereBookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Review whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Review whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Review whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Review whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Review whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Review whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Review whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Review withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Review withoutTrashed()
 * @method static bool|null forceDelete()
 * @method static bool|null restore()
 * @mixin \Eloquent
 */
class Review extends Model
{
    use SoftDeletes;

    //Возможный тон рецензии
    public const TONE_NEUTRAL = 0;

    public const TONE_POSITIVE = 1;

    public const TONE_NEGATIVE = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rating', 'text', 'header'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Автор рецензии
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Книга, к которой оставлена рецензия
     *
     * @return BelongsTo
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Все оценки текущей рецензии
     *
     * @return HasMany
     */
    public function reviewEstimates(): HasMany
    {
        return $this->hasMany(ReviewEstimate::class, 'review_id');
    }

    /**
     * Оценка рецензии конкретным пользователем, если таковая имеется
     *
     * @param User $user
     * @return ReviewEstimate | null
     */
    public function usersEstimate(User $user): ?ReviewEstimate
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
    public function isAuthor(User $user): bool
    {
        return ($this->user_id === $user->id);
    }

    /**
     * Проверить, оставлена ли рецензия к одной из книг конкретного пользователя
     *
     * @param User $user
     * @return boolean
     */
    public function isForBookOfUser(User $user): bool
    {
        return ($this->book->author_id === $user->id);
    }

    /** Текст рецензии, с переводами строки заменёнными на <br>
     *
     * @param string $text
     * @return string
     */
    public function getTextAttribute($text): string
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
    public function setTextAttribute($text): void
    {
        $this->attributes['text'] = htmlspecialchars($text, ENT_HTML5);
    }

    /**
     * Текст рецензии как есть
     *
     * @return string
     */
    public function getTextPlainAttribute(): string
    {
        return $this->attributes['text'];
    }

    /**
     * Дата создания рецензии в соответствии с часовым поясом
     *
     * @param string $created_at
     * @return Carbon
    */
    public function getCreatedAtAttribute($created_at): Carbon
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
    public function getCreatedAtPlainAttribute(): Carbon
    {
        return new Carbon($this->attributes['created_at']);
    }

    /**
     * Совокупная оценка рецензии
     *
     * @return int
     */
    public function getEstimateAttribute(): int
    {
        return $this->reviewEstimates->sum('estimate');
    }

}

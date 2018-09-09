<?php
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Review
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
 * @property int $tone
 * @property string|null $header
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Review whereHeader($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Review whereTone($value)
 */
	class Review extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string|null $name
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property string|null $surname
 * @property string|null $patronymic
 * @property string $gender
 * @property string $nickname
 * @property Carbon|null $birthday_date
 * @property string|null $birthday_date_plain
 * @property string|null $avatar Название аватарки пользователя
 * @property string $avatar_path Путь до аватара пользователя в рамках Amazon S3
 * @property string $avatar_url Ссылка на аватар пользователя
 * @property string $url Ссылка на пользователя
 * @property string $full_name ФИО пользователя
 * @property string $timezone таймзона пользователя
 * @property string $about Информация "О себе" с переводами строки заменёными на <br>
 * @property string $about_plain Информация "О себе" как она есть в бд
 * @property string $api_token Токен пользователя для api запросов
 * @property float $rating Средняя оценка книги
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Book[] $books Список книг созданных пользователем
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Book[] $libraryBooks Список книг добавленных пользователем в свою библиотеку
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ReviewEstimate[] $reviewEstimates Оценки к рецензиями оставленные пользователем
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Review[] $reviews
 * @property-read string $canonical_url Каноничный (основной, постоянный) url пользователя
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereBirthdayDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePatronymic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereSurname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereAbout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereApiToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereTimezone($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User findByIdOrSlug($id, $slug_name = null)
 * @property bool $is_admin
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereIsAdmin($value)
 * @property string $last_activity_at
 * @property string|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereLastActivityAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User withoutTrashed()
 */
	class User extends \Eloquent {}
}

namespace App\Models\Admin{
/**
 * Class Review
 *
 * @package App\Models\Admin
 * @property int $id
 * @property int $rating
 * @property string $text
 * @property int $user_id
 * @property int $book_id
 * @property \Carbon\Carbon|null $deleted_at
 * @property \Carbon $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int $tone
 * @property string|null $header
 * @property-read \App\Models\Admin\Book $book
 * @property-read \Carbon $created_at_plain
 * @property-read int $estimate
 * @property-read string $html_create_for
 * @property-read string $html_edit_for
 * @property-read string $html_view_for
 * @property-read string $text_plain
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ReviewEstimate[] $reviewEstimates
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Review whereBookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Review whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Review whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Review whereHeader($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Review whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Review whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Review whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Review whereTone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Review whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Review whereUserId($value)
 */
	class Review extends \Eloquent {}
}

namespace App\Models\Admin\Search\Helper{
/**
 * Класс хелпер для парсинга запроса от JQueryTables.
 * 
 * Используется для пагинации отфильтрованного запроса
 *
 * @package App\Models\Admin\Search\Helper
 * @property int $start Сущность начиная с которой необходимо получить выборку
 * @property int $length Количество сущностей в выборке
 */
	class Paginate extends \Eloquent {}
}

namespace App\Models\Admin\Search\Helper{
/**
 * Класс хелпер для парсинга запроса от JQueryTables.
 * 
 * Используется для сортировки отфильтрованного запроса
 *
 * @package App\Models\Admin\Search\Helper
 * @property-read string $column Столбец по которому будет происходить сортировка
 * @property-read string $direction Направление сортировки
 */
	class Sort extends \Eloquent {}
}

namespace App\Models\Admin{
/**
 * Class User
 *
 * @package App\Models\Admin
 * @property int $id
 * @property string|null $name
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $surname
 * @property string|null $patronymic
 * @property string $gender
 * @property string $nickname
 * @property \Carbon\Carbon|null $birthday_date
 * @property string $avatar
 * @property string $about
 * @property string $timezone
 * @property string|null $api_token
 * @property bool $is_admin
 * @property string $last_activity_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Book[] $books
 * @property-read string $about_plain
 * @property-read string $avatar_path
 * @property-read string $avatar_url
 * @property-read string|null $birthday_date_plain
 * @property-read string $canonical_url
 * @property-read string $full_name
 * @property-read string $html_create_for
 * @property-read string $html_edit_for
 * @property-read string $html_view_for
 * @property-read float $rating
 * @property-read string $url
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Book[] $libraryBooks
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ReviewEstimate[] $reviewEstimates
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Review[] $reviews
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User findByIdOrSlug($id, $slug_name = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\User whereAbout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\User whereApiToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\User whereBirthdayDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\User whereIsAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\User whereLastActivityAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\User whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\User wherePatronymic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\User whereSurname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\User whereTimezone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models\Admin{
/**
 * Class Book
 *
 * @package App\Models\Admin
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $cover
 * @property int $author_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $mongodb_book_id
 * @property int $page_count
 * @property \Carbon\Carbon|null $deleted_at
 * @property string $status
 * @property string $slug
 * @property bool $is_processing
 * @property-read \App\Models\User $author
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Genre[] $genres
 * @property-read string $canonical_url
 * @property-read string $cover_path
 * @property-read string $cover_url
 * @property-read string $description_plain
 * @property-read string $html_create_for
 * @property-read string $html_edit_for
 * @property-read string $html_view_for
 * @property-read float $rating
 * @property-read string $status_css
 * @property-read string $url
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Review[] $reviews
 * @property-write mixed $text
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Book findByIdOrSlug($id, $slug_name = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Book findSimilarSlugs($attribute, $config, $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Book whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Book whereCover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Book whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Book whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Book whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Book whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Book whereIsProcessing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Book whereMongodbBookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Book wherePageCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Book whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Book whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Book whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Book whereUpdatedAt($value)
 */
	class Book extends \Eloquent {}
}

namespace App\Models\Admin{
/**
 * Class Genre
 *
 * @package App\Models\Admin
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Book[] $books
 * @property-read string $html_create_for
 * @property-read string $html_edit_for
 * @property-read string $html_view_for
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Genre findSimilarSlugs($attribute, $config, $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Genre whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Genre whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Genre whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Genre whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\Genre whereUpdatedAt($value)
 */
	class Genre extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Book
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $description Описание книги с переводами строки заменёными на <br>
 * @property string|null $description_plain Описание книги как оно есть в бд
 * @property string|null $cover Название обложки книги
 * @property string $status Статус текущей книги (черновик/чистовик)
 * @property int $author_id
 * @property int $mongodb_book_id Идентификатор документа в MongoDB
 * @property int $page_count Количество страниц в книге
 * @property string $cover_path Путь до обложки книги в рамках Amazon S3
 * @property string $cover_url Ссылка на обложку книги
 * @property string $url Ссылка на книгу
 * @property string $status_css css класс соответствующий текущему статусу книги
 * @property float $rating Средняя оценка книги
 * @property string $slug Имя книги для формирования url
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read \App\Models\User $author
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users Коллекция пользователей добавивших к себе книгу
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Review[] $reviews
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Genre[] $genres
 * @property-read string $canonical_url Каноничный (основной, постоянный) url книги
 * @property-write mixed $text
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Book whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Book whereCover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Book whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Book whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Book whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Book whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Book whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Book onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Book whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Book whereMongodbBookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Book wherePageCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Book whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Book withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Book withoutTrashed()
 * @method static bool|null forceDelete()
 * @method static bool|null restore()
 * @mixin Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Book findByIdOrSlug($id, $slug_name = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Book findSimilarSlugs($attribute, $config, $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Book whereSlug($value)
 * @property bool $is_processing
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Book whereIsProcessing($value)
 */
	class Book extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Genre
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Genre[] $genres
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Book[] $books
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Genre whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Genre whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Genre whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Genre whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Genre whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Genre findSimilarSlugs($attribute, $config, $slug)
 */
	class Genre extends \Eloquent {}
}

namespace App\Models{
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
	class ReviewEstimate extends \Eloquent {}
}


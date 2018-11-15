<?php

use App\Models\Genre;
use App\Models\ReviewEstimate;
use App\Models\User;
use App\Models\Book;
use App\Models\Review;
use App\Models\Blog\Article;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Http\UploadedFile;
use Illuminate\Queue\QueueManager;

class FixtureSeeder extends Seeder
{
    const WITH_IMAGES = false;

    const WITH_BOOKS = false;

    /**
     * Run the database seeds.
     *
     * @param QueueManager $queueManager
     * @return void
     */
    public function run(QueueManager $queueManager)
    {
        $queueManager->setDefaultDriver('sync');
        $this->call(GenresTableSeeder::class);
        $this->runFixtures();
        $this->runDefaultUser();
    }

    public function runFixtures()
    {
        $files = File::allFiles(storage_path('seed/books/'));
        $count = 10;
        factory(User::class, $count)->create()->each(function ($user) use ($files) {
            $this->command->info('Перехожу к созданию фейкового пользователя');

            /** @var User $user*/
            $faker = Faker::create();

            if (self::WITH_IMAGES) {
                $this->command->info('Создаю аватару для фейкового пользователя');

                $avatar_path = $faker->image('/tmp', random_int(200, 600), random_int(200, 600));
                $avatar = new UploadedFile($avatar_path, 'cat');

                $user->avatar = $avatar;
                $user->save();

                $this->command->info('Аватара создана');
            }

            $count = random_int(1, 5);
            for ($i = 0; $i < $count; $i++) {
                $this->command->info('Создаю книгу #' . ($i + 1) . ' для фейкового пользователя');

                /** @var Book $book */
                $book = factory(Book::class)->make(['status' => Book::STATUS_OPEN]);
                $user->books()->save($book);

                if (self::WITH_IMAGES) {
                    $this->command->info('Создаю обложку для книги');

                    $cover_path = $faker->image('/tmp', random_int(200, 600), random_int(200, 600));
                    $cover = new UploadedFile($cover_path, 'cover');
                    $book->cover = $cover;
                    $book->save();

                    $this->command->info('Обложка создана');
                }

                if (self::WITH_BOOKS) {
                    $this->command->info('Загружаю текст книги');

                    /** @var SplFileInfo $file_info */
                    $file_info = $faker->randomElement($files);
                    $file = new UploadedFile($file_info->getPathname(), $file_info->getFilename());
                    $book->text = $file;
                    $book->save();
                    $book->is_processing = false;
                    $book->save();

                    $this->command->info('Текст загружен');
                }

                $this->command->info('Книга создана');
            }

            $count = random_int(1, 5);
            for ($i = 0; $i < $count; $i++) {
                $this->command->info('Создаю статью #' . ($i + 1) . ' для фейкового пользователя');

                /** @var Article $article */
                $article = factory(Article::class)->make();
                $user->articles()->save($article);

                $this->command->info('Статья создана');
            }

            $this->command->info('Заполняю жанры для книг');

            $genres = Genre::all();
            if (filled($genres)) {
                $user->books->each(function ($book) use ($genres) {
                    /** @var Book $book */
                    $book->genres()->attach(
                        $genres->random(random_int(1, $genres->count()))->pluck('id')->toArray()
                    );
                });
            }

            $this->command->info('Создаю рецензии');

            $count = random_int(1, 5);
            for ($i = 0; $i < $count; $i++) {
                $book = Book::inRandomOrder()->where('author_id', '!=', $user->id)->first();
                if (filled($book)) {
                    $user->reviews()->save(factory(Review::class)->make([
                        'book_id' => $book->id,
                        'user_id' => $user->id
                    ]));
                }
            }

            $this->command->info('Создаю оценки к чужим книгам от текущего пользователя');

            $review = Review::inRandomOrder()->where('user_id', '!=', $user->id)->first();
            if (filled($review)) {
                $review->reviewEstimates()->save(factory(ReviewEstimate::class)->make([
                    'user_id' => $user->id
                ]));
            }

            $this->command->info('Фейковый пользователь создан');
            $this->command->line('');
        });
    }

    /**
     * Создать пользователя для авторизации если заданы дефолтные данные и его не существует в бд
     */
    public function runDefaultUser()
    {
        if (blank(env('SEEDER_USER_EMAIL')) || blank(env('SEEDER_USER_PASSWORD'))) {
            return;
        }

        if (filled(User::where(['email' => env('SEEDER_USER_EMAIL')])->first())) {
            return;
        }

        $this->command->info('Перехожу к созданию дефолтного пользователя');

        $params = [
            'email' => env('SEEDER_USER_EMAIL'),
            'password' => env('SEEDER_USER_PASSWORD'),
            'is_admin' => true
        ];

        if (filled(env('SEEDER_USER_NAME'))) {
            $params['name'] = env('SEEDER_USER_NAME');
        }
        if (filled(env('SEEDER_USER_SURNAME'))) {
            $params['surname'] = env('SEEDER_USER_SURNAME');
        }
        if (filled(env('SEEDER_USER_PATRONYMIC'))) {
            $params['patronymic'] = env('SEEDER_USER_PATRONYMIC');
        }
        if (filled(env('SEEDER_USER_NICKNAME'))) {
            $params['nickname'] = env('SEEDER_USER_NICKNAME');
        }
        if (filled(env('SEEDER_USER_GENDER'))) {
            $params['gender'] = env('SEEDER_USER_GENDER');
        }
        if (filled(env('SEEDER_USER_BIRTHDAY_DATE'))) {
            $params['birthday_date'] = env('SEEDER_USER_BIRTHDAY_DATE');
        }

        $user = factory(\App\Models\User::class)->create($params);

        if (filled(env('SEEDER_USER_AVATAR_NAME'))) {
            $avatar_path = storage_path('seed/avatars/' . env('SEEDER_USER_AVATAR_NAME'));
            $avatar = new \Illuminate\Http\UploadedFile($avatar_path, env('SEEDER_USER_AVATAR_NAME'));
            $user->avatar = $avatar;
            $user->save();
        }

        $this->command->info('Создание дефолтного пользователя завершено');
    }
}

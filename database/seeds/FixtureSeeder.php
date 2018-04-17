<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Http\UploadedFile;
use Illuminate\Queue\QueueManager;

class FixtureSeeder extends Seeder
{
    const WITH_IMAGES = true;

    const WITH_BOOKS = true;

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
        $count = 1;
        factory(App\Models\User::class, $count)->create()->each(function ($user) use ($files) {
            /** @var \App\Models\User $user*/
            $faker = Faker::create();

            if (self::WITH_IMAGES) {
                $avatar_path = $faker->image('/tmp', rand(200, 600), rand(200, 600), 'cats');
                $avatar = new UploadedFile($avatar_path, 'cat');
                $user->avatar = $avatar;
                $user->save();
            }

            $count = rand(1, 5);
            $count = 1;
            for ($i = 0; $i < $count; $i++) {
                /** @var \App\Models\Book $book */
                $book = factory(App\Models\Book::class)->make(['status' => \App\Models\Book::STATUS_OPEN]);
                $user->books()->save($book);

                if (self::WITH_IMAGES) {
                    $cover_path = $faker->image('/tmp', rand(200, 600), rand(200, 600));
                    $cover = new UploadedFile($cover_path, 'cover');
                    $book->cover = $cover;
                    $book->save();
                }

                if (self::WITH_BOOKS) {
                    /** @var SplFileInfo $file_info */
                    $file_info = $faker->randomElement($files);
                    $file = new UploadedFile($file_info->getPathname(), $file_info->getFilename());
                    $book->text = $file;
                    $book->save();
                    $book->is_processing = false;
                    $book->save();
                }
            }

            $genres = \App\Models\Genre::all();
            if (filled($genres)) {
                $user->books->each(function ($book) use ($genres) {
                    $book->genres()->attach(
                        $genres->random(rand(1, $genres->count()))->pluck('id')->toArray()
                    );
                });
            }

            $count = rand(1, 5);
            for ($i = 0; $i < $count; $i++) {
                $book = \App\Models\Book::inRandomOrder()->where('author_id', '!=', $user->id)->first();
                if (filled($book)) {
                    $user->reviews()->save(factory(App\Models\Review::class)->make([
                        'book_id' => $book->id,
                        'user_id' => $user->id
                    ]));
                }
            }

            $review = \App\Models\Review::inRandomOrder()->where('user_id', '!=', $user->id)->first();
            if (filled($review)) {
                $review->reviewEstimates()->save(factory(App\Models\ReviewEstimate::class)->make([
                    'user_id' => $user->id
                ]));
            }
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

    }
}

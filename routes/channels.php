<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


Broadcast::channel('App.Book.{book_id}', function ($user, $book_id) {
    /** @var App\Models\User $user */
    $book = App\Models\Book::findAny(['id' => $book_id]);
    if (blank($book)) {
        return false;
    }

    return $user->isAuthor($book);
});

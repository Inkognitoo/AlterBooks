<?php

namespace App\Listeners;

use App;
use App\Event\Error;
use App\Mail\Error as ErrorMail;
use Illuminate\Support\Arr;
use Mail;

class ErrorListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Error  $event
     * @return void
     */
    public function handle(Error $event)
    {
        if (App::environment('production')) {
            // Очередь не может сериализовать Closure из класса exception. По этому получаем все данные заранее
            $message = $event->exception->getMessage();
            $file = $event->exception->getFile();
            $line = $event->exception->getLine();
            $trace = print_r(collect($event->exception->getTrace())->map(function ($trace) {
                return Arr::except($trace, ['args']);
            })->all(), true);

            Mail::queue(new ErrorMail($message, $file, $line, $trace));
        }
    }
}

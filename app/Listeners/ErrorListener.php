<?php

namespace App\Listeners;

use App;
use App\Event\Error;
use App\Mail\Error as ErrorMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
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
            Mail::send(new ErrorMail($event->exception));
        }
    }
}

<?php

namespace App\Mail;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Arr;

class Error extends Mailable
{
    use Queueable, SerializesModels;

    public $exception;

    /**
     * Create a new message instance.
     *
     * @param Exception $exception
     */
    public function __construct(Exception $exception)
    {
        $this->exception = $exception;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $trace = print_r(collect($this->exception->getTrace())->map(function ($trace) {
            return Arr::except($trace, ['args']);
        })->all(), true);

        return $this->markdown('email.error')
            ->to(config('mail.error.addresses'))
            ->attachData($trace, 'trace_error.txt', [
                'mime' => 'text/plain',
            ]);
    }
}

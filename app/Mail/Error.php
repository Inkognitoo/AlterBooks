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

    public $message;

    public $file;

    public $line;

    public $trace;

    /**
     * Create a new message instance.
     *
     * @param $message
     * @param $file
     * @param $line
     * @param $trace
     */
    public function __construct($message, $file, $line, $trace)
    {
        $this->message = $message;
        $this->file = $file;
        $this->line = $line;
        $this->trace = $trace;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('email.error')
            ->to(config('mail.error.addresses'))
            ->attachData($this->trace, 'trace_error.txt', [
                'mime' => 'text/plain',
            ]);
    }
}

<?php

namespace App\Event;

use Exception;
use Illuminate\Queue\SerializesModels;

class Error
{
    use SerializesModels;

    public $exception;

    /**
     * Create a new event instance.
     *
     * @param Exception $exception
     */
    public function __construct(Exception $exception)
    {
        $this->exception = $exception;
    }
}

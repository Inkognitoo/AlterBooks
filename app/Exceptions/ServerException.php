<?php

namespace App\Exceptions;


use Throwable;

class ServerException extends ApiException
{

    public function __construct($message = "", $code = 500, Throwable $previous = null)
    {
        if (blank($message)) {
            $message = 'There is some server error. Please, contact us via email or try one more time later';
        }

        parent::__construct($message, $code, $previous);
    }

}
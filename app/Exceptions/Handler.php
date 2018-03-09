<?php

namespace App\Exceptions;

use App\Event\Error;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        ApiException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        event(new Error($exception));

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {

        if (!($exception instanceof ApiException)) {
            return parent::render($request, $exception);
        }

        $response = [
            'success' => false,
            'data' => null,
            'errors' => $this->getError($exception),
        ];

        return response()->json($response)->header('X-error', true);
    }


    protected function getError(Exception $exception)
    {

        $error = [
            'code' => $exception->getCode(),
            'message' => $exception->getMessage(),
        ];

        if (empty($exception->getPrevious())) {
            return [$error];
        }

        $errors = $this->getError($exception->getPrevious());
        $errors[] = $error;
        return $errors;
    }
}

<?php

namespace App\Exceptions;

use Exception;
use App\Entities\Status;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
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
        // Handle not allowed methods specifically.
        if ($exception instanceof MethodNotAllowedHttpException) {
            $status = new Status(
                Status::STATUS_FAILURE,
                Status::REASON_METHOD_NOT_ALLOWED,
                sprintf('The method "%s" is not allowed at this endpoint', $request->getMethod())
            );

            return (new Response(json_encode($status), Response::HTTP_METHOD_NOT_ALLOWED))
                ->withHeaders(['Content-Type' => 'application/json']);
        }

        return parent::render($request, $exception);
    }
}

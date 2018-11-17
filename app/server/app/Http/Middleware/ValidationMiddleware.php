<?php

namespace App\Http\Middleware;

use App\Entities\Status;
use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ValidationMiddleware
{
    /**
     * Inspects an incoming request and the controller that it's destined for, and determines whether the controller
     * is able to parse the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param string $controller The controller that fulfills this request
     * @return mixed
     */
    public function handle($request, Closure $next, string $controller = '')
    {
        $requirements = call_user_func([$controller, 'getRequiredParameters']);

        $validator = Validator::make(
            $request->all(),
            $requirements
        );

        if ($validator->fails()) {
            $warning = '';

            // Decode errors into warning string
            foreach ($validator->errors()->toArray() as $param => $errors) {
                $warning .= "parameter '$param' failed validation: ";

                foreach ($errors as $error) {
                    $warning .= "$error ";
                }
            }

            $status = new Status(
                Status::STATUS_FAILURE,
                Status::REASON_BAD_REQUEST,
                'request is not valid: ' . trim($warning)
            );

            return (new Response(json_encode($status), Response::HTTP_BAD_REQUEST))
                ->withHeaders(['Content-Type'=> 'application/json']);
        }

        return $next($request);
    }
}

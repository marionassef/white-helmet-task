<?php

namespace App\Exceptions;

use App\Helpers\CustomResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\Exceptions\OAuthServerException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Throwable $exception
     * @return Response
     * @throws Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof CustomQueryException) {
            return CustomResponse::errorResponse('Something went wrong, please contact our customer support', 500);
        }
        if ($exception instanceof QueryException) {
            return CustomResponse::errorResponse('Something went wrong, please contact our customer support', 500);
        }
        if ($exception instanceof NotFoundHttpException) {
            return CustomResponse::errorResponse($exception->getMessage());
        }
        if ($exception instanceof CustomValidationException) {
            if($exception->getError()){
                return CustomResponse::errorResponse($exception->getMessage(), 400, $exception->getError());
            }
            return CustomResponse::errorResponse($exception->getMessage());
        }
        if ($exception instanceof ValidationException) {
            return CustomResponse::errorResponse($exception->validator->errors()->first());
        }
        if ($exception instanceof ModelNotFoundException) {
            return CustomResponse::errorResponse("No data found");
        }
        if ($exception instanceof MethodNotAllowedHttpException) {
            return CustomResponse::errorResponse('Wrong Request', 405);
        }
        if ($exception instanceof HttpException) {
            return CustomResponse::errorResponse($exception->getMessage());
        }
        if ($exception instanceof AuthenticationException) {
            return CustomResponse::errorResponse($exception->getMessage(), 401);
        }
        if ($exception instanceof UnauthorizedException) {
            return CustomResponse::errorResponse($exception->getMessage(), 401);
        }
        if ($exception instanceof OAuthServerException) {
            return CustomResponse::errorResponse("Invalid Credentials", 401);
        }
        return parent::render($request, $exception);
    }
}

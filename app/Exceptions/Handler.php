<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (HttpException $exception, $request) {

            if ($request->is('api/*')) {

                switch ($exception->getStatusCode()) {

                    case 401:
                        return response()->json([
                            'success' => false,
                            'error' => [
                                'type' => 'unauthenticated_error',
                                'code' => 401,
                                'message' => 'Unauthenticated',
                            ],

                        ], 401);

                    case 403:
                        return response()->json([
                            'success' => false,
                            'error' => [
                                'type' => 'forbidden_error',
                                'code' => 403,
                                'message' => 'Unauthorized'
                            ]
                        ], 403);

                    case 404:
                        return response()->json([
                            'success' => false,
                            'error' => [
                                'type' => 'http_not_found_error',
                                'code' => 404,
                                'message' => 'Page not found!'
                            ]
                        ], 404);

                    case 405:
                        return response()->json([
                            'success' => false,
                            'error' => [
                                'type' => 'method_not_allowed_error',
                                'code' => 405,
                                'message' => 'Method is not allowed by the server!'
                            ]
                        ], 405);

                    case 500:
                        return response()->json([
                            'success' => false,
                            'error' => [
                                'type' => 'server error',
                                'code' => 500,
                                'message' => $exception->getMessage()
                            ]
                        ], 500);
                }
            }
        });

        $this->renderable(function (ValidationException $exception, $request) {
            if ($request->is('api/*')) {

                return response()->json([
                    'success' => false,
                    'error' => $exception->errors()
                ], 422);
            }
        });
    }
}

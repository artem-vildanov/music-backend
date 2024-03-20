<?php

namespace App\Exceptions;

use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Exceptions\FavouritesExceptions\FavouritesException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;

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
//        $this->reportable(function (Throwable $e) {
//            //
//        });

        $this->renderable(function (DataAccessException $exception, Request $request) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], $exception->getCode());
        });

        $this->renderable(function (MinioException $exception, Request $request) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], $exception->getCode());
        });

        $this->renderable(function (PlaylistSongsException $exception, Request $request) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], $exception->getCode());
        });

        $this->renderable(function (FavouritesException $exception, Request $request) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], $exception->getCode());
        });

        $this->renderable(function (RedisException $exception, Request $request) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], $exception->getCode());
        });

        $this->renderable(function (JwtException $exception, Request $request) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], $exception->getCode());
        });
    }
}

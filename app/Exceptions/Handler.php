<?php

namespace App\Exceptions;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        if (request()->wantsJson()) {
            $this->renderable(function (CouldNotConsumeFuel $exception) {
                return response()->json([
                    'error' => 'AllowedQuotaLimitExceeded',
                    'message' => $exception->getMessage(),
                ], 500);
            });
        }

        $this->renderable(function (QueryException $exception, Request $request) {
            if ($exception->errorInfo[1] === 1451) {
                $request->session()->flash('flash.banner', 'Some records cannot be deletes
                as it is associated with other records !');
                $request->session()->flash('flash.bannerStyle', 'danger');

                return back();
            }
        });
    }
}

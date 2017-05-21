<?php

namespace Tests;

use Illuminate\Contracts\Debug\ExceptionHandler;
use App\Exceptions\Handler;
use Exception;

trait DisableExceptionHandling {

    public function disableExceptionHandling()
    {
        $this->oldExceptionHandler = $this->app->make(ExceptionHandler::class);

        $this->app->instance(ExceptionHandler::class, new class extends Handler{
            public function __construct() {}
            public function report(Exception $e) {}
            public function render($request, Exception $e) {
                throw $e;
            }
        });
    }

    public function withExceptionHandling()
    {
        $this->app->instance(ExceptionHandler::class, $this->oldExceptionHandler);

        return $this;
    }
}

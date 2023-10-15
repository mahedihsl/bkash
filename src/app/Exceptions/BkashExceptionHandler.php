<?php

namespace Mahedi250\Bkash\App\Exceptions;


use Throwable;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class BkashExceptionHandler extends ExceptionHandler
{
    public function render($request, Throwable $e)
    {
        if ($e instanceof BkashException) {
            return new JsonResponse([
                'error' => 'Package Validation error',
                'message' => $e->getMessage(),
            ], 400);
        }

        return parent::render($request, $e);
    }
}

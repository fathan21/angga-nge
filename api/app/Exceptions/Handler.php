<?php 
// app/Exceptions/Handler.php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

public function render($request, Throwable $exception)
{
    if ($exception instanceof ModelNotFoundException) {
        return response()->json([
            'error' => 'Resource not found',
            'message' => $exception->getMessage(),
        ], 404);
    }

    if ($exception instanceof \Illuminate\Validation\ValidationException) {
        return response()->json([
            'error' => 'Validation error',
            'message' => $exception->errors(),
        ], 422);
    }

    if ($exception instanceof HttpException && $exception->getStatusCode() === 403) {
        return response()->json([
            'error' => 'Forbidden',
            'message' => 'You do not have permission to access this resource.',
        ], 403);
    }

    return parent::render($request, $exception);
}

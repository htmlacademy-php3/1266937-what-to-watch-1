<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Responses\SuccessResponse;
use App\Http\Responses\FailResponse;

/**
 * @psalm-api
 */
abstract class Controller
{
    use AuthorizesRequests;

    /**
     * Create a JSON success response.
     *
     * @param mixed $data Response data.
     * @param int $statusCode HTTP status code.
     * @return SuccessResponse
     */
    protected function successResponse(
        mixed $data,
        int $statusCode = Response::HTTP_OK
    ): SuccessResponse {

        return app(SuccessResponse::class, [
            'data' => $data,
            'statusCode' => $statusCode
        ]);
    }

    protected function failResponse(
        mixed $data = null,
        int $statusCode = Response::HTTP_OK,
        ?string $message = null
    ): FailResponse {

        return app(FailResponse::class, [
            'data' => $data,
            'message' => $message,
            'statusCode' => $statusCode
        ]);
    }
}

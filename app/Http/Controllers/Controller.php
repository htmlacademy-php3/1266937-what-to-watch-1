<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Responses\SuccessResponse;

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
    protected function successResponse(mixed $data, int $statusCode = Response::HTTP_OK): SuccessResponse
    {
        return app(SuccessResponse::class, [
            'data' => $data,
            'statusCode' => $statusCode
        ]);
    }
}

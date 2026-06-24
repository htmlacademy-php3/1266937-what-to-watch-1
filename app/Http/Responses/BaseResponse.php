<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseResponse implements Responsable
{
    public function __construct(
        protected mixed $data = [],
        public int $statusCode = RESPONSE::HTTP_OK

    ) {
    }

    /**
     * Create an HTTP response that represents the object
     * @param mixed $request
     * @return JsonResponse
     */
    public function toResponse($request): JsonResponse
    {
        return response()->json($this->makeResponseData(), $this->statusCode);
    }

    /**
     *
     * @return array
     */
    protected function prepareData(): array
    {
        if ($this->data instanceof Arrayable) {
            return $this->data->toArray();
        }

        return $this->data;

    }

    /**
     *
     * @return array|null
     */
    abstract protected function makeResponseData(): ?array;
}

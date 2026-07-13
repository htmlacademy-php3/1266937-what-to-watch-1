<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseResponse implements Responsable
{
    public function __construct(
        protected mixed $data = [],
        public int $statusCode = Response::HTTP_OK
    ) {
    }

    /**
     * Create an HTTP response that represents the object.
     *
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
        $paginator = ($this->data instanceof ResourceCollection)
            ? $this->data->resource
            : $this->data;

        if ($paginator instanceof LengthAwarePaginator) {
            $items = ($this->data instanceof ResourceCollection)
                ? $this->data->resolve()
                : $paginator->items();

            return [
                'data' => $items,
                'current_page' => $paginator->currentPage(),
                'first_page_url' => $paginator->url(1),
                'next_page_url' => $paginator->nextPageUrl(),
                'prev_page_url' => $paginator->previousPageUrl(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ];
        }

        if ($this->data instanceof JsonResource) {
            return $this->data->resolve();
        }

        if ($this->data instanceof Arrayable) {
            return $this->data->toArray();
        }

        return (array) $this->data;
    }

    /**
     * Format structured response data for API.
     *
     * @return array<string, mixed>|null
     */
    abstract protected function makeResponseData(): ?array;
}

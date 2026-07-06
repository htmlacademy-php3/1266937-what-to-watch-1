<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\Response;

class PaginatedSuccessResponse extends BaseResponse
{
    public function __construct(
        protected mixed $data,
        protected LengthAwarePaginator $paginator,
        public int $statusCode = RESPONSE::HTTP_OK
    ) {
        parent::__construct($data, $statusCode);
    }

    /**
     * Формирует плоскую структуру ответа: data и пагинация на одном уровне
     *
     * @return array
     */
    protected function makeResponseData(): array
    {
        return [
            'data' => $this->prepareData(),
            'current_page' => $this->paginator->currentPage(),
            'first_page_url' => $this->paginator->url(1),
            'next_page_url' => $this->paginator->nextPageUrl(),
            'prev_page_url' => $this->paginator->previousPageUrl(),
            'per_page' => $this->paginator->perPage(),
            'total' => $this->paginator->total(),
        ];
    }
}

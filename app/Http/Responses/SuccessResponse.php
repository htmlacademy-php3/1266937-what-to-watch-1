<?php

namespace App\Http\Responses;

class SuccessResponse extends BaseResponse
{
    /**
     * @inheritDoc

     * @return array<string, mixed>|null
     */
    protected function makeResponseData(): ?array
    {
        return [
            'data' => $this->prepareData(),
        ];
    }
}

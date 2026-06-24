<?php

namespace App\Http\Responses;

class SuccessResponse extends BaseResponse
{
    /**
     *
     * @return array
     */
    protected function makeResponseData(): array
    {
        return [
            'data' => $this->prepareData()
        ];
    }
}

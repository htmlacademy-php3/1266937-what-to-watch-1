<?php

namespace App\Http\Responses;

final class SuccessResponse extends BaseResponse
{
    /**
     * @inheritDoc

     * @return array<string, mixed>|null
     */
    #[\Override]
    protected function makeResponseData(): ?array
    {
        $preparedData = $this->prepareData();

        if (isset($preparedData['current_page'])) {
            return $preparedData;
        }

        return [
            'data' => $preparedData,
        ];
    }
}

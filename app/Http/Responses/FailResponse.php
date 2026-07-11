<?php

namespace App\Http\Responses;

class FailResponse extends BaseResponse
{
    public function __construct(
        mixed $data = [],
        int $statusCode = Response::HTTP_BAD_REQUEST,
        protected ?string $message = null,
    ) {
        parent::__construct($data, $statusCode);
    }

    /**
     * @inheritDoc
     *
     * @return array{message: string|null, errors?: array<string, mixed>}
     */
    public function makeResponseData(): array
    {
        $response = [
            'message' => $this->message,
        ];

        $errors = $this->prepareData();

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return $response;
    }
}

<?php

namespace App\Http\Responses;

use Exception;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Support\Responsable;

final class ApiErrorResponse implements Responsable
{
    public function __construct(
        private Exception $exception,
        private string $message = '',
        private int $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY,
        private array $headers = [],
        private int $options = 0
    ) {
    }

    public function toResponse($request): JsonResponse
    {
        $response = ["message" => $this->message];

        if (!is_null($this->exception) && config('app.debug')) {
            $response['debug'] = [
                'message' => $this->exception->getMessage(),
                'file'    => $this->exception->getFile(),
                'line'    => $this->exception->getLine(),
                'trace'   => $this->exception->getTraceAsString()
            ];
        }

        return response()->json(
            data: $response,
            status: $this->statusCode,
            headers: $this->headers,
            options: $this->options
        );
    }
}

<?php

namespace App\Http\Responses;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Pagination\LengthAwarePaginator;

class ApiSuccessResponse implements Responsable
{
    public function __construct(
        private int $httpCode = Response::HTTP_OK,
        private mixed $data = [],
        private array $metadata = [],
        private array $headers = [],
        private string $message = ''
    ) {
        
    }

    public function toResponse($request): JsonResponse
    {

        if($this->message) {
            $this->data['message'] = $this->message;
        }

        if ($this->data instanceof LengthAwarePaginator) {

            $current_metadata = [
                'current_page' => $this->data->currentPage(),
                'per_page' => $this->data->perPage(),
                'from' => $this->data->firstItem(),
                'to' => $this->data->lastItem(),
                'path' => $this->data->path(),
                'first_page_url' => $this->data->url(1),
                'next_page_url' => $this->data->nextPageUrl(),
                'prev_page_url' => $this->data->previousPageUrl(),
                'total' => $this->data->total(),
                'has_more_pages' => $this->data->hasMorePages(),
                'last_page' => $this->data->lastPage(),
            ];

            return response()->json(
                data: [
                    'data' => $this->data->items(),
                    'metadata' => array_merge($current_metadata, $this->metadata),
                ],
                status: $this->httpCode,
                headers: $this->headers
            );
        }


        return response()->json(
            data: [
                'data' => $this->data,
                'metadata' => $this->metadata,
            ],
            status: $this->httpCode,
            headers: $this->headers
        );
    }
}

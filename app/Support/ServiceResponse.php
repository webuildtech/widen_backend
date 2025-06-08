<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;

class ServiceResponse
{
    public function __construct(
        public bool $success,
        public mixed $data = null,
        public ?string $error = null,
        public int $code = 200
    ) {}

    public static function success(mixed $data = null, int $code = 200): self
    {
        return new self(true, $data, null, $code);
    }

    public static function error(string $message, int $code = 400): self
    {
        return new self(false, null, $message, $code);
    }

    public function withData(mixed $data): self
    {
        $this->data = $data;
        return $this;
    }

    public function toResponse(): JsonResponse
    {
        return response()->json(
            $this->success
                ? ['data' => $this->data]
                : ['error' => $this->error, 'data' => $this->data],
            $this->code
        );
    }
}

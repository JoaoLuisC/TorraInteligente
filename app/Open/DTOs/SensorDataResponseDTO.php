<?php

namespace App\Open\DTOs;

/**
 * DTO para resposta da API externa
 */
class SensorDataResponseDTO
{
    public function __construct(
        public readonly bool $success,
        public readonly string $message,
        public readonly ?array $data = null,
        public readonly ?string $errorCode = null,
        public readonly ?array $errors = null
    ) {}

    public static function success(string $message, ?array $data = null): self
    {
        return new self(
            success: true,
            message: $message,
            data: $data
        );
    }

    public static function error(string $message, string $errorCode, ?array $errors = null): self
    {
        return new self(
            success: false,
            message: $message,
            errorCode: $errorCode,
            errors: $errors
        );
    }

    public function toArray(): array
    {
        $response = [
            'success' => $this->success,
            'message' => $this->message,
        ];

        if ($this->data !== null) {
            $response['data'] = $this->data;
        }

        if ($this->errorCode !== null) {
            $response['error_code'] = $this->errorCode;
        }

        if ($this->errors !== null) {
            $response['errors'] = $this->errors;
        }

        return $response;
    }
}

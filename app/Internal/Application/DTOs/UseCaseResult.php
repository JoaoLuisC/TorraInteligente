<?php

namespace App\Internal\Application\DTOs;

/**
 * DTO para resultado de Use Cases
 * Padroniza retornos de casos de uso
 */
class UseCaseResult
{
    public function __construct(
        private readonly bool $success,
        private readonly ?array $data = null,
        private readonly ?string $errorMessage = null,
        private readonly ?string $errorCode = null
    ) {}

    public static function success(array $data = []): self
    {
        return new self(
            success: true,
            data: $data
        );
    }

    public static function failure(string $errorMessage, string $errorCode): self
    {
        return new self(
            success: false,
            errorMessage: $errorMessage,
            errorCode: $errorCode
        );
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function isFailure(): bool
    {
        return !$this->success;
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    public function getErrorCode(): ?string
    {
        return $this->errorCode;
    }

    public function toArray(): array
    {
        $result = [
            'success' => $this->success
        ];

        if ($this->success && $this->data !== null) {
            $result['data'] = $this->data;
        }

        if (!$this->success) {
            $result['error_message'] = $this->errorMessage;
            $result['error_code'] = $this->errorCode;
        }

        return $result;
    }
}

<?php

namespace App\Internal\Domain\ValueObjects;

/**
 * Value Object para Device Key
 * Encapsula validações e regras da chave do dispositivo
 */
class DeviceKey
{
    private const MIN_LENGTH = 6;
    private const MAX_LENGTH = 32;
    private const PATTERN = '/^[a-zA-Z0-9_-]+$/';

    public function __construct(
        private readonly string $value
    ) {
        if (!$this->isValid()) {
            throw new \InvalidArgumentException(
                "Device Key '{$this->value}' é inválida"
            );
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    // === VALIDAÇÕES ===

    public function isValid(): bool
    {
        return $this->hasValidLength() &&
               $this->hasValidFormat() &&
               $this->isNotEmpty();
    }

    private function hasValidLength(): bool
    {
        $length = strlen($this->value);
        return $length >= self::MIN_LENGTH && $length <= self::MAX_LENGTH;
    }

    private function hasValidFormat(): bool
    {
        return preg_match(self::PATTERN, $this->value) === 1;
    }

    private function isNotEmpty(): bool
    {
        return !empty(trim($this->value));
    }

    // === MÉTODOS DE NEGÓCIO ===

    public function isTestDevice(): bool
    {
        return str_starts_with(strtolower($this->value), 'test_') ||
               str_starts_with(strtolower($this->value), 'dev_');
    }

    public function getShortKey(): string
    {
        if (strlen($this->value) <= 8) {
            return $this->value;
        }

        return substr($this->value, 0, 4) . '...' . substr($this->value, -4);
    }

    // === COMPARAÇÕES ===

    public function equals(DeviceKey $other): bool
    {
        return $this->value === $other->value;
    }

    // === MÉTODOS ESTÁTICOS ===

    public static function generate(): self
    {
        $key = 'TI_' . date('Y') . '_' . bin2hex(random_bytes(8));
        return new self($key);
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }
}

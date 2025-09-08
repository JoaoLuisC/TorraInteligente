<?php

namespace App\Open\DTOs;

/**
 * DTO para receber dados do ESP8266 via API
 * Este DTO é exposto externamente
 */
class SensorDataRequestDTO
{
    public function __construct(
        public readonly string $deviceKey,
        public readonly float $temperatura,
        public readonly int $timestamp,
        public readonly ?int $tempo = null,
        public readonly ?int $rssi = null,
        public readonly ?int $uptime = null,
        public readonly ?int $freeHeap = null,
        public readonly ?string $version = null
    ) {}

    public static function fromRequest(array $request): self
    {
        return new self(
            deviceKey: $request['device_key'],
            temperatura: (float) $request['temperatura'],
            timestamp: (int) $request['timestamp'],
            tempo: isset($request['tempo']) ? (int) $request['tempo'] : null,
            rssi: isset($request['rssi']) ? (int) $request['rssi'] : null,
            uptime: isset($request['uptime']) ? (int) $request['uptime'] : null,
            freeHeap: isset($request['free_heap']) ? (int) $request['free_heap'] : null,
            version: $request['version'] ?? null
        );
    }

    public function validate(): array
    {
        $errors = [];

        if (empty($this->deviceKey)) {
            $errors[] = 'device_key é obrigatório';
        }

        if ($this->temperatura < -50 || $this->temperatura > 500) {
            $errors[] = 'Temperatura deve estar entre -50°C e 500°C';
        }

        if ($this->timestamp <= 0) {
            $errors[] = 'Timestamp inválido';
        }

        if ($this->tempo !== null && $this->tempo < 0) {
            $errors[] = 'Tempo não pode ser negativo';
        }

        return $errors;
    }

    public function isValid(): bool
    {
        return empty($this->validate());
    }
}

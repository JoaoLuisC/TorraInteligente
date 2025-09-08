<?php

namespace App\DTOs;

class SensorDataDTO
{
    public function __construct(
        public readonly string $deviceKey,
        public readonly float $temperatura,
        public readonly int $tempo,
        public readonly int $timestampEsp,
        public readonly ?int $rssi = null,
        public readonly ?int $uptime = null,
        public readonly ?int $freeHeap = null,
        public readonly ?string $version = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            deviceKey: $data['device_key'],
            temperatura: (float) $data['temperatura'],
            tempo: (int) ($data['tempo'] ?? 0),
            timestampEsp: (int) $data['timestamp'],
            rssi: isset($data['rssi']) ? (int) $data['rssi'] : null,
            uptime: isset($data['uptime']) ? (int) $data['uptime'] : null,
            freeHeap: isset($data['free_heap']) ? (int) $data['free_heap'] : null,
            version: $data['version'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'device_key' => $this->deviceKey,
            'temperatura' => $this->temperatura,
            'tempo' => $this->tempo,
            'timestamp_esp' => $this->timestampEsp,
            'rssi' => $this->rssi,
            'uptime' => $this->uptime,
            'free_heap' => $this->freeHeap,
            'version' => $this->version
        ];
    }

    public function isValid(): bool
    {
        return !empty($this->deviceKey) &&
               $this->temperatura >= -50 &&
               $this->temperatura <= 500 &&
               $this->tempo >= 0;
    }
}

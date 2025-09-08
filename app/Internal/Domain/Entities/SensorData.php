<?php

namespace App\Internal\Domain\Entities;

use App\Internal\Domain\ValueObjects\Temperatura;
use App\Internal\Domain\ValueObjects\DeviceKey;
use App\Internal\Domain\ValueObjects\Timestamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Entidade de domínio para dados do sensor
 * Contém regras de negócio específicas do domínio
 */
class SensorData extends Model
{
    protected $table = 'dados_sensores';

    protected $fillable = [
        'torrador_id',
        'temperatura',
        'tempo',
        'timestamp_esp',
        'rssi',
        'uptime',
        'free_heap',
        'version'
    ];

    protected $casts = [
        'temperatura' => 'decimal:2',
        'tempo' => 'integer',
        'timestamp_esp' => 'integer',
        'rssi' => 'integer',
        'uptime' => 'integer',
        'free_heap' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // === RELACIONAMENTOS ===

    public function torrador(): BelongsTo
    {
        return $this->belongsTo(Torrador::class);
    }

    // === VALUE OBJECTS ===

    public function getTemperaturaObject(): Temperatura
    {
        return new Temperatura($this->temperatura);
    }

    public function getDeviceKey(): DeviceKey
    {
        return new DeviceKey($this->torrador->codigo_conexao);
    }

    public function getTimestampObject(): Timestamp
    {
        return new Timestamp($this->timestamp_esp);
    }

    // === REGRAS DE NEGÓCIO ===

    public function isTemperaturaCritica(): bool
    {
        return $this->getTemperaturaObject()->isCritica();
    }

    public function isTemperaturaSegura(): bool
    {
        return $this->getTemperaturaObject()->isSegura();
    }

    public function needsAlert(): bool
    {
        // Alerta se temperatura crítica OU sinal WiFi muito fraco
        return $this->isTemperaturaCritica() ||
               ($this->rssi && $this->rssi < -85);
    }

    public function getEstadoTorra(): string
    {
        $temperatura = $this->getTemperaturaObject();

        return match (true) {
            $temperatura->isPreAquecimento() => 'pre_aquecimento',
            $temperatura->isDesenvolvimento() => 'desenvolvimento',
            $temperatura->isPrimeiroCrack() => 'primeiro_crack',
            $temperatura->isSegundoCrack() => 'segundo_crack',
            $temperatura->isFinalizacao() => 'finalizacao',
            default => 'indefinido'
        };
    }

    public function getDuracaoFormatada(): string
    {
        if (!$this->tempo) {
            return '00:00';
        }

        $minutos = floor($this->tempo / 60);
        $segundos = $this->tempo % 60;

        return sprintf('%02d:%02d', $minutos, $segundos);
    }

    public function getQualidadeSinal(): string
    {
        if (!$this->rssi) {
            return 'desconhecido';
        }

        return match (true) {
            $this->rssi >= -50 => 'excelente',
            $this->rssi >= -60 => 'bom',
            $this->rssi >= -70 => 'regular',
            $this->rssi >= -80 => 'fraco',
            default => 'muito_fraco'
        };
    }

    // === SCOPES ===

    public function scopeRecentes($query, int $minutos = 30)
    {
        return $query->where('created_at', '>=', now()->subMinutes($minutos));
    }

    public function scopeTemperaturaCritica($query)
    {
        return $query->where('temperatura', '>', Temperatura::TEMPERATURA_CRITICA);
    }

    public function scopePorPeriodo($query, string $periodo)
    {
        return match ($periodo) {
            '1h' => $query->where('created_at', '>=', now()->subHour()),
            '6h' => $query->where('created_at', '>=', now()->subHours(6)),
            '24h' => $query->where('created_at', '>=', now()->subDay()),
            '7d' => $query->where('created_at', '>=', now()->subWeek()),
            '30d' => $query->where('created_at', '>=', now()->subMonth()),
            default => $query
        };
    }

    // === VALIDAÇÕES DE DOMÍNIO ===

    public function validateBusinessRules(): array
    {
        $errors = [];

        // Validar temperatura
        if (!$this->getTemperaturaObject()->isValid()) {
            $errors[] = 'Temperatura fora dos limites válidos para torra de café';
        }

        // Validar consistência temporal
        if ($this->tempo && $this->tempo > 3600) { // Mais de 1 hora
            $errors[] = 'Tempo de torra excede limite máximo recomendado';
        }

        // Validar qualidade do sinal
        if ($this->rssi && $this->rssi < -90) {
            $errors[] = 'Sinal WiFi muito fraco pode comprometer a qualidade dos dados';
        }

        return $errors;
    }

    public function isValid(): bool
    {
        return empty($this->validateBusinessRules());
    }
}

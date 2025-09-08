<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class DadosSensor extends Model
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

    // === MÉTODOS DE NEGÓCIO ===

    public function isTemperaturaAlta(): bool
    {
        return $this->temperatura > 240; // Temperatura crítica para café
    }

    public function isTemperaturaBaixa(): bool
    {
        return $this->temperatura < 160; // Temperatura muito baixa
    }

    public function getTemperaturaStatusAttribute(): string
    {
        if ($this->isTemperaturaAlta()) {
            return 'crítica';
        }

        if ($this->isTemperaturaBaixa()) {
            return 'baixa';
        }

        return 'normal';
    }

    public function getTempoFormatadoAttribute(): string
    {
        $minutos = floor($this->tempo / 60);
        $segundos = $this->tempo % 60;

        return sprintf('%02d:%02d', $minutos, $segundos);
    }

    public function getSinalWifiQualidadeAttribute(): string
    {
        if (!$this->rssi) {
            return 'desconhecido';
        }

        if ($this->rssi >= -50) return 'excelente';
        if ($this->rssi >= -60) return 'bom';
        if ($this->rssi >= -70) return 'regular';

        return 'fraco';
    }

    public function isLeituraRecente(): bool
    {
        return $this->created_at->diffInMinutes(now()) <= 5;
    }

    // === SCOPES ===

    public function scopeRecentes($query, int $minutos = 30)
    {
        return $query->where('created_at', '>=', now()->subMinutes($minutos));
    }

    public function scopePorTorrador($query, int $torradorId)
    {
        return $query->where('torrador_id', $torradorId);
    }

    public function scopeTemperaturaEntre($query, float $min, float $max)
    {
        return $query->whereBetween('temperatura', [$min, $max]);
    }

    public function scopeUltimoDia($query)
    {
        return $query->where('created_at', '>=', now()->subDay());
    }

    // === VALIDAÇÕES DE NEGÓCIO ===

    public function validarDados(): array
    {
        $erros = [];

        if ($this->temperatura < -50 || $this->temperatura > 500) {
            $erros[] = 'Temperatura fora do range válido (-50°C a 500°C)';
        }

        if ($this->tempo < 0) {
            $erros[] = 'Tempo não pode ser negativo';
        }

        if ($this->rssi && ($this->rssi > 0 || $this->rssi < -100)) {
            $erros[] = 'RSSI inválido (deve estar entre -100 e 0 dBm)';
        }

        return $erros;
    }

    public function isDadoValido(): bool
    {
        return empty($this->validarDados());
    }
}

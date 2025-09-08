<?php

namespace App\Events;

use App\Entities\DadosSensor;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TemperaturaAlarmEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly DadosSensor $dadosSensor,
        public readonly string $tipoAlerta // 'alta', 'baixa', 'critica'
    ) {}

    public function getTemperatura(): float
    {
        return $this->dadosSensor->temperatura;
    }

    public function getDeviceKey(): string
    {
        return $this->dadosSensor->torrador->codigo_conexao;
    }

    public function isCritico(): bool
    {
        return $this->tipoAlerta === 'critica' ||
               ($this->tipoAlerta === 'alta' && $this->dadosSensor->temperatura > 250);
    }
}

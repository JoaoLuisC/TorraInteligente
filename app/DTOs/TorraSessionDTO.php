<?php

namespace App\DTOs;

class TorraSessionDTO
{
    public function __construct(
        public readonly string $deviceKey,
        public readonly \DateTime $inicioSessao,
        public readonly ?\DateTime $fimSessao = null,
        public readonly ?float $temperaturaMaxima = null,
        public readonly ?float $temperaturaMedia = null,
        public readonly ?int $duracaoTotal = null,
        public readonly ?int $totalLeituras = null,
        public readonly array $dadosCompletos = []
    ) {}

    public static function fromDatabase(object $data): self
    {
        return new self(
            deviceKey: $data->device_key,
            inicioSessao: new \DateTime($data->inicio_sessao),
            fimSessao: $data->fim_sessao ? new \DateTime($data->fim_sessao) : null,
            temperaturaMaxima: $data->temperatura_maxima,
            temperaturaMedia: $data->temperatura_media,
            duracaoTotal: $data->duracao_total,
            totalLeituras: $data->total_leituras,
            dadosCompletos: $data->dados_completos ?? []
        );
    }

    public function toArray(): array
    {
        return [
            'device_key' => $this->deviceKey,
            'inicio_sessao' => $this->inicioSessao->format('Y-m-d H:i:s'),
            'fim_sessao' => $this->fimSessao?->format('Y-m-d H:i:s'),
            'temperatura_maxima' => $this->temperaturaMaxima,
            'temperatura_media' => $this->temperaturaMedia,
            'duracao_total' => $this->duracaoTotal,
            'total_leituras' => $this->totalLeituras,
            'dados_completos' => $this->dadosCompletos
        ];
    }

    public function isSessionActive(): bool
    {
        return $this->fimSessao === null;
    }

    public function getDuracaoFormatada(): string
    {
        if (!$this->duracaoTotal) return '00:00';

        $minutos = floor($this->duracaoTotal / 60);
        $segundos = $this->duracaoTotal % 60;

        return sprintf('%02d:%02d', $minutos, $segundos);
    }
}

<?php

namespace App\Mappers;

use App\Entities\DadosSensor;
use App\DTOs\SensorDataDTO;

class SensorDataMapper
{
    /**
     * Converte Entity para Array de resposta da API
     */
    public function toArray(DadosSensor $entity): array
    {
        return [
            'id' => $entity->id,
            'temperatura' => (float) $entity->temperatura,
            'tempo' => $entity->tempo,
            'tempo_formatado' => $entity->tempo_formatado,
            'timestamp_esp' => $entity->timestamp_esp,
            'rssi' => $entity->rssi,
            'uptime' => $entity->uptime,
            'free_heap' => $entity->free_heap,
            'version' => $entity->version,
            'created_at' => $entity->created_at->toISOString(),
            'updated_at' => $entity->updated_at->toISOString(),

            // Dados calculados
            'temperatura_status' => $entity->temperatura_status,
            'wifi_qualidade' => $entity->sinal_wifi_qualidade,
            'is_recente' => $entity->isLeituraRecente(),
            'is_valido' => $entity->isDadoValido(),

            // Metadados
            'metadata' => [
                'uptime_formatado' => $this->formatarUptime($entity->uptime),
                'memoria_livre_mb' => $entity->free_heap ? round($entity->free_heap / 1024, 2) : null,
                'delay_rede_ms' => $this->calcularDelayRede($entity),
            ]
        ];
    }

    /**
     * Converte Entity para Array para gráficos
     */
    public function toChartData(DadosSensor $entity): array
    {
        return [
            'x' => $entity->tempo,
            'y' => (float) $entity->temperatura,
            'timestamp' => $entity->created_at->timestamp,
            'label' => $entity->tempo_formatado,
            'rssi' => $entity->rssi,
            'status' => $entity->temperatura_status
        ];
    }

    /**
     * Converte DTO para Array de envio
     */
    public function dtoToArray(SensorDataDTO $dto): array
    {
        return [
            'device_key' => $dto->deviceKey,
            'temperatura' => $dto->temperatura,
            'tempo' => $dto->tempo,
            'timestamp_esp' => $dto->timestampEsp,
            'rssi' => $dto->rssi,
            'uptime' => $dto->uptime,
            'free_heap' => $dto->freeHeap,
            'version' => $dto->version
        ];
    }

    /**
     * Converte Collection para formato de gráfico
     */
    public function collectionToChartData($dados): array
    {
        return [
            'labels' => $dados->pluck('tempo_formatado')->toArray(),
            'datasets' => [
                [
                    'label' => 'Temperatura (°C)',
                    'data' => $dados->pluck('temperatura')->toArray(),
                    'borderColor' => 'rgb(75, 192, 192)',
                    'backgroundColor' => 'rgba(75, 192, 192, 0.1)',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.4
                ]
            ],
            'metadata' => [
                'total_pontos' => $dados->count(),
                'temperatura_maxima' => $dados->max('temperatura'),
                'temperatura_minima' => $dados->min('temperatura'),
                'temperatura_media' => round($dados->avg('temperatura'), 2),
                'duracao_total' => $dados->max('tempo'),
                'intervalo_medio' => $this->calcularIntervaloMedio($dados)
            ]
        ];
    }

    /**
     * Converte dados de sessão para resumo
     */
    public function sessionToSummary($sessao): array
    {
        return [
            'device_key' => $sessao->device_key,
            'torrador_nome' => $sessao->torrador_nome,
            'data_sessao' => $sessao->data_sessao,
            'inicio' => $sessao->inicio_sessao,
            'fim' => $sessao->fim_sessao,
            'duracao_formatada' => $this->formatarDuracao($sessao->duracao_total),
            'temperatura' => [
                'maxima' => (float) $sessao->temperatura_maxima,
                'media' => round($sessao->temperatura_media, 2)
            ],
            'total_leituras' => $sessao->total_leituras,
            'qualidade' => $this->avaliarQualidadeSessao($sessao)
        ];
    }

    // === MÉTODOS PRIVADOS ===

    private function formatarUptime(?int $uptime): ?string
    {
        if (!$uptime) {
            return null;
        }

        $horas = floor($uptime / 3600);
        $minutos = floor(($uptime % 3600) / 60);
        $segundos = $uptime % 60;

        if ($horas > 0) {
            return sprintf('%02dh %02dm %02ds', $horas, $minutos, $segundos);
        }

        if ($minutos > 0) {
            return sprintf('%02dm %02ds', $minutos, $segundos);
        }

        return sprintf('%02ds', $segundos);
    }

    private function calcularDelayRede(DadosSensor $entity): ?int
    {
        if (!$entity->timestamp_esp) {
            return null;
        }

        // Diferença entre timestamp do ESP e timestamp do servidor
        $timestampServidor = $entity->created_at->timestamp * 1000; // ms
        $delay = abs($timestampServidor - $entity->timestamp_esp);

        // Retorna delay em ms (limitado a valores razoáveis)
        return $delay > 60000 ? null : (int) $delay;
    }

    private function calcularIntervaloMedio($dados): ?float
    {
        if ($dados->count() < 2) {
            return null;
        }

        $intervalos = [];
        $dadosArray = $dados->sortBy('created_at')->values();

        for ($i = 1; $i < $dadosArray->count(); $i++) {
            $anterior = $dadosArray[$i - 1];
            $atual = $dadosArray[$i];

            $intervalo = $atual->created_at->diffInSeconds($anterior->created_at);
            if ($intervalo < 300) { // Ignorar intervalos muito grandes (> 5min)
                $intervalos[] = $intervalo;
            }
        }

        return count($intervalos) > 0 ? round(array_sum($intervalos) / count($intervalos), 1) : null;
    }

    private function formatarDuracao(?int $segundos): string
    {
        if (!$segundos) {
            return '00:00';
        }

        $minutos = floor($segundos / 60);
        $segundos = $segundos % 60;

        return sprintf('%02d:%02d', $minutos, $segundos);
    }

    private function avaliarQualidadeSessao($sessao): array
    {
        $qualidade = 'boa';
        $problemas = [];

        // Verificar duração
        if ($sessao->duracao_total < 600) { // Menos de 10 minutos
            $qualidade = 'curta';
            $problemas[] = 'Sessão muito curta';
        }

        // Verificar temperatura
        if ($sessao->temperatura_maxima < 180) {
            $qualidade = 'baixa_temperatura';
            $problemas[] = 'Temperatura máxima baixa';
        }

        if ($sessao->temperatura_maxima > 250) {
            $qualidade = 'alta_temperatura';
            $problemas[] = 'Temperatura muito alta';
        }

        // Verificar consistência dos dados
        if ($sessao->total_leituras < ($sessao->duracao_total / 10)) { // Menos que 1 leitura a cada 10s
            $qualidade = 'dados_inconsistentes';
            $problemas[] = 'Poucos dados coletados';
        }

        return [
            'status' => $qualidade,
            'problemas' => $problemas,
            'score' => $this->calcularScoreQualidade($sessao, $problemas)
        ];
    }

    private function calcularScoreQualidade($sessao, array $problemas): int
    {
        $score = 100;

        // Penalizar por problemas
        $score -= count($problemas) * 20;

        // Bonus por duração adequada
        if ($sessao->duracao_total >= 600 && $sessao->duracao_total <= 1800) {
            $score += 10;
        }

        // Bonus por temperatura adequada
        if ($sessao->temperatura_maxima >= 180 && $sessao->temperatura_maxima <= 230) {
            $score += 10;
        }

        return max(0, min(100, $score));
    }
}

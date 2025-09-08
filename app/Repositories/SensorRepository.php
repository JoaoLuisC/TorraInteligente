<?php

namespace App\Repositories;

use App\Repositories\Contracts\SensorRepositoryInterface;
use App\DTOs\SensorDataDTO;
use App\DTOs\TorraSessionDTO;
use App\Entities\DadosSensor;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class SensorRepository implements SensorRepositoryInterface
{
    public function salvarDados(SensorDataDTO $dados, int $torradorId): DadosSensor
    {
        return DadosSensor::create([
            'torrador_id' => $torradorId,
            'temperatura' => $dados->temperatura,
            'tempo' => $dados->tempo,
            'timestamp_esp' => $dados->timestampEsp,
            'rssi' => $dados->rssi,
            'uptime' => $dados->uptime,
            'free_heap' => $dados->freeHeap,
            'version' => $dados->version
        ]);
    }

    public function buscarDadosRecentes(string $deviceKey, int $minutos = 30): Collection
    {
        return DadosSensor::query()
            ->join('torradores', 'dados_sensores.torrador_id', '=', 'torradores.id')
            ->where('torradores.codigo_conexao', $deviceKey)
            ->where('dados_sensores.created_at', '>=', now()->subMinutes($minutos))
            ->select('dados_sensores.*')
            ->orderBy('dados_sensores.created_at', 'asc')
            ->get();
    }

    public function buscarUltimosDados(string $deviceKey, int $limite = 5): Collection
    {
        return DadosSensor::query()
            ->join('torradores', 'dados_sensores.torrador_id', '=', 'torradores.id')
            ->where('torradores.codigo_conexao', $deviceKey)
            ->select('dados_sensores.*')
            ->orderBy('dados_sensores.created_at', 'desc')
            ->limit($limite)
            ->get();
    }

    public function buscarHistorico(
        int $userId,
        ?int $torradorId = null,
        ?string $dataInicio = null,
        ?string $dataFim = null,
        int $perPage = 20
    ): LengthAwarePaginator {
        $query = DadosSensor::query()
            ->join('torradores', 'dados_sensores.torrador_id', '=', 'torradores.id')
            ->where('torradores.usuario_id', $userId);

        if ($torradorId) {
            $query->where('torradores.id', $torradorId);
        }

        if ($dataInicio) {
            $query->whereDate('dados_sensores.created_at', '>=', $dataInicio);
        }

        if ($dataFim) {
            $query->whereDate('dados_sensores.created_at', '<=', $dataFim);
        }

        return $query
            ->select([
                'torradores.nome as torrador_nome',
                DB::raw('DATE(dados_sensores.created_at) as data_torra'),
                DB::raw('MIN(dados_sensores.created_at) as inicio'),
                DB::raw('MAX(dados_sensores.created_at) as fim'),
                DB::raw('MAX(temperatura) as temp_maxima'),
                DB::raw('AVG(temperatura) as temp_media'),
                DB::raw('MAX(tempo) as duracao_total'),
                DB::raw('COUNT(*) as total_leituras')
            ])
            ->groupBy('torradores.id', DB::raw('DATE(dados_sensores.created_at)'))
            ->orderBy('dados_sensores.created_at', 'desc')
            ->paginate($perPage);
    }

    public function buscarSessoes(int $userId, array $filtros = []): Collection
    {
        $query = DB::table('dados_sensores')
            ->join('torradores', 'dados_sensores.torrador_id', '=', 'torradores.id')
            ->where('torradores.usuario_id', $userId);

        // Aplicar filtros
        if (isset($filtros['torrador_id'])) {
            $query->where('torradores.id', $filtros['torrador_id']);
        }

        if (isset($filtros['data_inicio'])) {
            $query->whereDate('dados_sensores.created_at', '>=', $filtros['data_inicio']);
        }

        if (isset($filtros['data_fim'])) {
            $query->whereDate('dados_sensores.created_at', '<=', $filtros['data_fim']);
        }

        return $query
            ->select([
                'torradores.codigo_conexao as device_key',
                'torradores.nome as torrador_nome',
                DB::raw('DATE(dados_sensores.created_at) as data_sessao'),
                DB::raw('MIN(dados_sensores.created_at) as inicio_sessao'),
                DB::raw('MAX(dados_sensores.created_at) as fim_sessao'),
                DB::raw('MAX(temperatura) as temperatura_maxima'),
                DB::raw('AVG(temperatura) as temperatura_media'),
                DB::raw('MAX(tempo) as duracao_total'),
                DB::raw('COUNT(*) as total_leituras')
            ])
            ->groupBy('torradores.id', DB::raw('DATE(dados_sensores.created_at)'))
            ->orderBy('dados_sensores.created_at', 'desc')
            ->get();
    }

    public function buscarEstatisticas(int $userId, ?string $periodo = null): array
    {
        $cacheKey = "stats_user_{$userId}_{$periodo}";

        return Cache::remember($cacheKey, 300, function () use ($userId, $periodo) {
            $query = DadosSensor::query()
                ->join('torradores', 'dados_sensores.torrador_id', '=', 'torradores.id')
                ->where('torradores.usuario_id', $userId);

            // Aplicar período
            switch ($periodo) {
                case 'hoje':
                    $query->whereDate('dados_sensores.created_at', today());
                    break;
                case 'semana':
                    $query->where('dados_sensores.created_at', '>=', now()->subWeek());
                    break;
                case 'mes':
                    $query->where('dados_sensores.created_at', '>=', now()->subMonth());
                    break;
            }

            return [
                'total_leituras' => $query->count(),
                'temperatura_maxima' => $query->max('temperatura'),
                'temperatura_media' => $query->avg('temperatura'),
                'temperatura_minima' => $query->min('temperatura'),
                'tempo_total_horas' => $query->max('tempo') / 3600,
                'sessoes_ativas' => $this->contarSessoesAtivas($userId),
                'torradores_ativos' => $this->contarTorradoresAtivos($userId, $periodo)
            ];
        });
    }

    public function buscarDadosGrafico(string $deviceKey, string $periodo = '24h'): Collection
    {
        $query = DadosSensor::query()
            ->join('torradores', 'dados_sensores.torrador_id', '=', 'torradores.id')
            ->where('torradores.codigo_conexao', $deviceKey);

        // Aplicar período
        switch ($periodo) {
            case '1h':
                $query->where('dados_sensores.created_at', '>=', now()->subHour());
                break;
            case '6h':
                $query->where('dados_sensores.created_at', '>=', now()->subHours(6));
                break;
            case '24h':
                $query->where('dados_sensores.created_at', '>=', now()->subDay());
                break;
            case '7d':
                $query->where('dados_sensores.created_at', '>=', now()->subWeek());
                break;
        }

        return $query
            ->select([
                'dados_sensores.temperatura',
                'dados_sensores.tempo',
                'dados_sensores.created_at',
                'dados_sensores.rssi'
            ])
            ->orderBy('dados_sensores.created_at', 'asc')
            ->get();
    }

    public function deviceKeyExiste(string $deviceKey): bool
    {
        return DB::table('torradores')
            ->where('codigo_conexao', $deviceKey)
            ->exists();
    }

    public function buscarTorradorPorDeviceKey(string $deviceKey): ?object
    {
        return DB::table('torradores')
            ->where('codigo_conexao', $deviceKey)
            ->first();
    }

    public function limparDadosAntigos(int $diasParaManter = 90): int
    {
        return DadosSensor::where('created_at', '<', now()->subDays($diasParaManter))
            ->delete();
    }

    // === MÉTODOS PRIVADOS ===

    private function contarSessoesAtivas(int $userId): int
    {
        return DB::table('dados_sensores')
            ->join('torradores', 'dados_sensores.torrador_id', '=', 'torradores.id')
            ->where('torradores.usuario_id', $userId)
            ->where('dados_sensores.created_at', '>=', now()->subMinutes(5))
            ->distinct('torradores.id')
            ->count('torradores.id');
    }

    private function contarTorradoresAtivos(int $userId, ?string $periodo): int
    {
        $query = DB::table('dados_sensores')
            ->join('torradores', 'dados_sensores.torrador_id', '=', 'torradores.id')
            ->where('torradores.usuario_id', $userId);

        switch ($periodo) {
            case 'hoje':
                $query->whereDate('dados_sensores.created_at', today());
                break;
            case 'semana':
                $query->where('dados_sensores.created_at', '>=', now()->subWeek());
                break;
            case 'mes':
                $query->where('dados_sensores.created_at', '>=', now()->subMonth());
                break;
        }

        return $query->distinct('torradores.id')
            ->count('torradores.id');
    }
}

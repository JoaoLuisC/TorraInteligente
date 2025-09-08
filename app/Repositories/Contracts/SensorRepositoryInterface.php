<?php

namespace App\Repositories\Contracts;

use App\DTOs\SensorDataDTO;
use App\DTOs\TorraSessionDTO;
use App\Entities\DadosSensor;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface SensorRepositoryInterface
{
    /**
     * Salva dados do sensor
     */
    public function salvarDados(SensorDataDTO $dados, int $torradorId): DadosSensor;

    /**
     * Busca dados recentes por device key
     */
    public function buscarDadosRecentes(string $deviceKey, int $minutos = 30): Collection;

    /**
     * Busca últimos dados por device key
     */
    public function buscarUltimosDados(string $deviceKey, int $limite = 5): Collection;

    /**
     * Busca histórico paginado
     */
    public function buscarHistorico(
        int $userId,
        ?int $torradorId = null,
        ?string $dataInicio = null,
        ?string $dataFim = null,
        int $perPage = 20
    ): LengthAwarePaginator;

    /**
     * Busca sessões de torra agrupadas
     */
    public function buscarSessoes(int $userId, array $filtros = []): Collection;

    /**
     * Busca estatísticas do sensor
     */
    public function buscarEstatisticas(int $userId, ?string $periodo = null): array;

    /**
     * Busca dados para gráfico
     */
    public function buscarDadosGrafico(
        string $deviceKey,
        string $periodo = '24h'
    ): Collection;

    /**
     * Verifica se device key existe
     */
    public function deviceKeyExiste(string $deviceKey): bool;

    /**
     * Busca torrador por device key
     */
    public function buscarTorradorPorDeviceKey(string $deviceKey): ?object;

    /**
     * Remove dados antigos
     */
    public function limparDadosAntigos(int $diasParaManter = 90): int;
}

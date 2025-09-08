<?php

namespace App\Internal\Infrastructure\Repositories;

use App\Internal\Domain\Entities\SensorData;
use App\Internal\Domain\ValueObjects\DeviceKey;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

/**
 * Repository de infraestrutura para dados do sensor
 * Implementa persistência e cache
 */
class SensorDataRepository
{
    private const CACHE_TTL = 300; // 5 minutos

    public function save(SensorData $sensorData): SensorData
    {
        $sensorData->save();

        // Invalidar cache relacionado
        $this->invalidateCache($sensorData->torrador_id);

        return $sensorData;
    }

    public function findRecentByDeviceKey(DeviceKey $deviceKey, int $limit = 5): Collection
    {
        $cacheKey = "sensor_recent_{$deviceKey->getValue()}_{$limit}";

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($deviceKey, $limit) {
            return SensorData::query()
                ->join('torradores', 'dados_sensores.torrador_id', '=', 'torradores.id')
                ->where('torradores.codigo_conexao', $deviceKey->getValue())
                ->orderBy('dados_sensores.created_at', 'desc')
                ->limit($limit)
                ->select('dados_sensores.*')
                ->get();
        });
    }

    public function findByDeviceKeyAndPeriod(DeviceKey $deviceKey, string $period): Collection
    {
        $cacheKey = "sensor_period_{$deviceKey->getValue()}_{$period}";

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($deviceKey, $period) {
            return SensorData::query()
                ->join('torradores', 'dados_sensores.torrador_id', '=', 'torradores.id')
                ->where('torradores.codigo_conexao', $deviceKey->getValue())
                ->porPeriodo($period)
                ->orderBy('dados_sensores.created_at', 'asc')
                ->select('dados_sensores.*')
                ->get();
        });
    }

    public function findCriticalTemperatures(int $torradorId, int $hours = 24): Collection
    {
        return SensorData::query()
            ->where('torrador_id', $torradorId)
            ->where('created_at', '>=', now()->subHours($hours))
            ->temperaturaCritica()
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getStatistics(int $torradorId, string $period = '24h'): array
    {
        $cacheKey = "sensor_stats_{$torradorId}_{$period}";

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($torradorId, $period) {
            $query = SensorData::query()
                ->where('torrador_id', $torradorId)
                ->porPeriodo($period);

            return [
                'total_readings' => $query->count(),
                'avg_temperature' => $query->avg('temperatura'),
                'max_temperature' => $query->max('temperatura'),
                'min_temperature' => $query->min('temperatura'),
                'max_time' => $query->max('tempo'),
                'critical_count' => $query->where('temperatura', '>', 240)->count(),
                'last_reading' => $query->orderBy('created_at', 'desc')->first()?->created_at,
            ];
        });
    }

    public function findSessionData(int $torradorId, \DateTime $startDate, ?\DateTime $endDate = null): Collection
    {
        $query = SensorData::query()
            ->where('torrador_id', $torradorId)
            ->where('created_at', '>=', $startDate);

        if ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }

        return $query->orderBy('created_at', 'asc')->get();
    }

    public function deleteOldData(int $daysToKeep = 90): int
    {
        $deletedCount = SensorData::query()
            ->where('created_at', '<', now()->subDays($daysToKeep))
            ->delete();

        // Limpar cache após limpeza
        Cache::flush();

        return $deletedCount;
    }

    // === MÉTODOS PRIVADOS ===

    private function invalidateCache(int $torradorId): void
    {
        // Buscar device key para invalidar cache específico
        $deviceKey = DB::table('torradores')
            ->where('id', $torradorId)
            ->value('codigo_conexao');

        if ($deviceKey) {
            $patterns = [
                "sensor_recent_{$deviceKey}_*",
                "sensor_period_{$deviceKey}_*",
                "sensor_stats_{$torradorId}_*"
            ];

            foreach ($patterns as $pattern) {
                Cache::forget($pattern);
            }
        }
    }
}

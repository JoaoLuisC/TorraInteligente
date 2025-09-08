<?php

namespace App\Open\Services;

use App\Open\DTOs\SensorDataRequestDTO;
use App\Open\DTOs\SensorDataResponseDTO;
use App\Internal\Application\UseCases\ProcessSensorDataUseCase;
use App\Internal\Application\UseCases\GetRealtimeDataUseCase;
use App\Internal\Application\UseCases\GetChartDataUseCase;
use Illuminate\Support\Facades\Log;

/**
 * Service público para API externa do ESP8266
 * Este service é exposto externamente
 */
class SensorApiService
{
    public function __construct(
        private readonly ProcessSensorDataUseCase $processSensorDataUseCase,
        private readonly GetRealtimeDataUseCase $getRealtimeDataUseCase,
        private readonly GetChartDataUseCase $getChartDataUseCase
    ) {}

    /**
     * Processa dados vindos do ESP8266
     */
    public function processSensorData(SensorDataRequestDTO $request): SensorDataResponseDTO
    {
        try {
            // Validar entrada
            if (!$request->isValid()) {
                return SensorDataResponseDTO::error(
                    'Dados inválidos',
                    'VALIDATION_ERROR',
                    $request->validate()
                );
            }

            // Executar caso de uso
            $result = $this->processSensorDataUseCase->execute($request);

            if ($result->isSuccess()) {
                return SensorDataResponseDTO::success(
                    'Dados processados com sucesso',
                    $result->getData()
                );
            }

            return SensorDataResponseDTO::error(
                $result->getErrorMessage(),
                $result->getErrorCode()
            );

        } catch (\Exception $e) {
            Log::error('Erro no SensorApiService::processSensorData', [
                'error' => $e->getMessage(),
                'device_key' => $request->deviceKey
            ]);

            return SensorDataResponseDTO::error(
                'Erro interno do servidor',
                'INTERNAL_ERROR'
            );
        }
    }

    /**
     * Busca dados em tempo real
     */
    public function getRealtimeData(string $deviceKey, int $limit = 5): SensorDataResponseDTO
    {
        try {
            $result = $this->getRealtimeDataUseCase->execute($deviceKey, $limit);

            if ($result->isSuccess()) {
                return SensorDataResponseDTO::success(
                    'Dados obtidos com sucesso',
                    $result->getData()
                );
            }

            return SensorDataResponseDTO::error(
                $result->getErrorMessage(),
                $result->getErrorCode()
            );

        } catch (\Exception $e) {
            Log::error('Erro no SensorApiService::getRealtimeData', [
                'error' => $e->getMessage(),
                'device_key' => $deviceKey
            ]);

            return SensorDataResponseDTO::error(
                'Erro interno do servidor',
                'INTERNAL_ERROR'
            );
        }
    }

    /**
     * Busca dados para gráfico
     */
    public function getChartData(string $deviceKey, string $period = '24h'): SensorDataResponseDTO
    {
        try {
            $result = $this->getChartDataUseCase->execute($deviceKey, $period);

            if ($result->isSuccess()) {
                return SensorDataResponseDTO::success(
                    'Dados do gráfico obtidos com sucesso',
                    $result->getData()
                );
            }

            return SensorDataResponseDTO::error(
                $result->getErrorMessage(),
                $result->getErrorCode()
            );

        } catch (\Exception $e) {
            Log::error('Erro no SensorApiService::getChartData', [
                'error' => $e->getMessage(),
                'device_key' => $deviceKey,
                'period' => $period
            ]);

            return SensorDataResponseDTO::error(
                'Erro interno do servidor',
                'INTERNAL_ERROR'
            );
        }
    }
}

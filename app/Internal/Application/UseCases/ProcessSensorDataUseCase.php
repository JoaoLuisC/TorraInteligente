<?php

namespace App\Internal\Application\UseCases;

use App\Open\DTOs\SensorDataRequestDTO;
use App\Internal\Domain\Entities\SensorData;
use App\Internal\Domain\ValueObjects\DeviceKey;
use App\Internal\Domain\ValueObjects\Temperatura;
use App\Internal\Infrastructure\Repositories\SensorDataRepository;
use App\Internal\Infrastructure\Repositories\TorradorRepository;
use App\Internal\Domain\Events\SensorDataReceived;
use App\Internal\Application\DTOs\UseCaseResult;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

/**
 * Use Case para processar dados do sensor
 * Contém toda a lógica de negócio para recebimento e processamento
 */
class ProcessSensorDataUseCase
{
    public function __construct(
        private readonly SensorDataRepository $sensorRepository,
        private readonly TorradorRepository $torradorRepository
    ) {}

    public function execute(SensorDataRequestDTO $request): UseCaseResult
    {
        try {
            // 1. Validar device key
            $deviceKey = new DeviceKey($request->deviceKey);

            // 2. Verificar se torrador existe
            $torrador = $this->torradorRepository->findByDeviceKey($deviceKey);
            if (!$torrador) {
                return UseCaseResult::failure(
                    'Device não encontrado',
                    'DEVICE_NOT_FOUND'
                );
            }

            // 3. Validar temperatura
            $temperatura = new Temperatura($request->temperatura);
            if (!$temperatura->isValid()) {
                return UseCaseResult::failure(
                    'Temperatura inválida',
                    'INVALID_TEMPERATURE'
                );
            }

            // 4. Criar entidade de dados do sensor
            $sensorData = new SensorData([
                'torrador_id' => $torrador->id,
                'temperatura' => $temperatura->getValue(),
                'tempo' => $request->tempo ?? 0,
                'timestamp_esp' => $request->timestamp,
                'rssi' => $request->rssi,
                'uptime' => $request->uptime,
                'free_heap' => $request->freeHeap,
                'version' => $request->version
            ]);

            // 5. Validar regras de negócio
            if (!$sensorData->isValid()) {
                $errors = $sensorData->validateBusinessRules();
                Log::warning('Dados do sensor violam regras de negócio', [
                    'device_key' => $deviceKey->getValue(),
                    'errors' => $errors
                ]);
                // Continua o processamento, mas registra o warning
            }

            // 6. Salvar dados
            $savedData = $this->sensorRepository->save($sensorData);

            // 7. Verificar alertas
            $this->processAlerts($savedData);

            // 8. Disparar evento
            Event::dispatch(new SensorDataReceived($savedData));

            // 9. Log de sucesso
            Log::info('Dados do sensor processados com sucesso', [
                'device_key' => $deviceKey->getValue(),
                'temperatura' => $temperatura->getValue(),
                'sensor_data_id' => $savedData->id,
                'estado_torra' => $savedData->getEstadoTorra()
            ]);

            return UseCaseResult::success([
                'sensor_data_id' => $savedData->id,
                'temperatura' => $temperatura->getValue(),
                'estado_torra' => $savedData->getEstadoTorra(),
                'timestamp' => $savedData->created_at->toISOString(),
                'alerts' => $this->getAlerts($savedData)
            ]);

        } catch (\InvalidArgumentException $e) {
            return UseCaseResult::failure(
                $e->getMessage(),
                'VALIDATION_ERROR'
            );

        } catch (\Exception $e) {
            Log::error('Erro ao processar dados do sensor', [
                'device_key' => $request->deviceKey,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return UseCaseResult::failure(
                'Erro interno ao processar dados',
                'INTERNAL_ERROR'
            );
        }
    }

    // === MÉTODOS PRIVADOS ===

    private function processAlerts(SensorData $sensorData): void
    {
        if ($sensorData->needsAlert()) {
            // Disparar evento de alerta
            Event::dispatch(new \App\Internal\Domain\Events\TemperaturaAlarmEvent(
                $sensorData,
                $sensorData->isTemperaturaCritica() ? 'temperatura_critica' : 'sinal_fraco'
            ));
        }
    }

    private function getAlerts(SensorData $sensorData): array
    {
        $alerts = [];

        if ($sensorData->isTemperaturaCritica()) {
            $alerts[] = [
                'type' => 'temperatura_critica',
                'message' => 'Temperatura crítica detectada',
                'severity' => 'high',
                'temperatura' => $sensorData->temperatura
            ];
        }

        if ($sensorData->rssi && $sensorData->rssi < -85) {
            $alerts[] = [
                'type' => 'sinal_fraco',
                'message' => 'Sinal WiFi fraco detectado',
                'severity' => 'medium',
                'rssi' => $sensorData->rssi
            ];
        }

        return $alerts;
    }
}

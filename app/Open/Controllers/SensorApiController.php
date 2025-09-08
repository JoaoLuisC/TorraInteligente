<?php

namespace App\Open\Controllers;

use App\Http\Controllers\Controller;
use App\Open\DTOs\SensorDataRequestDTO;
use App\Open\Services\SensorApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Controller público para API externa
 * Exposto para consumo do ESP8266 e outras aplicações externas
 */
class SensorApiController extends Controller
{
    public function __construct(
        private readonly SensorApiService $sensorApiService
    ) {}

    /**
     * Endpoint para receber dados do ESP8266
     * POST /api/sensor/data
     */
    public function receiveData(Request $request): JsonResponse
    {
        // Validação Laravel
        $validator = Validator::make($request->all(), [
            'device_key' => 'required|string|max:32',
            'temperatura' => 'required|numeric|between:-50,500',
            'timestamp' => 'required|integer|min:1',
            'tempo' => 'nullable|integer|min:0',
            'rssi' => 'nullable|integer|between:-100,0',
            'uptime' => 'nullable|integer|min:0',
            'free_heap' => 'nullable|integer|min:0',
            'version' => 'nullable|string|max:10'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dados de entrada inválidos',
                'errors' => $validator->errors(),
                'error_code' => 'VALIDATION_ERROR'
            ], 422);
        }

        // Converter para DTO
        $sensorDataDTO = SensorDataRequestDTO::fromRequest($request->all());

        // Processar via service
        $response = $this->sensorApiService->processSensorData($sensorDataDTO);

        // Determinar status HTTP
        $statusCode = $response->success ? 200 : $this->getHttpStatusCode($response->errorCode);

        return response()->json($response->toArray(), $statusCode);
    }

    /**
     * Endpoint para dados em tempo real
     * GET /api/sensor/realtime/{deviceKey}
     */
    public function realtime(string $deviceKey, Request $request): JsonResponse
    {
        $limit = $request->get('limit', 5);
        $limit = min(max($limit, 1), 50); // Entre 1 e 50

        $response = $this->sensorApiService->getRealtimeData($deviceKey, $limit);
        $statusCode = $response->success ? 200 : $this->getHttpStatusCode($response->errorCode);

        return response()->json($response->toArray(), $statusCode);
    }

    /**
     * Endpoint para dados de gráfico
     * GET /api/sensor/chart/{deviceKey}
     */
    public function chartData(string $deviceKey, Request $request): JsonResponse
    {
        $period = $request->get('period', '24h');

        // Validar período
        $validPeriods = ['1h', '6h', '24h', '7d', '30d'];
        if (!in_array($period, $validPeriods)) {
            return response()->json([
                'success' => false,
                'message' => 'Período inválido',
                'error_code' => 'INVALID_PERIOD',
                'valid_periods' => $validPeriods
            ], 400);
        }

        $response = $this->sensorApiService->getChartData($deviceKey, $period);
        $statusCode = $response->success ? 200 : $this->getHttpStatusCode($response->errorCode);

        return response()->json($response->toArray(), $statusCode);
    }

    /**
     * Health check endpoint
     * GET /api/sensor/health
     */
    public function health(): JsonResponse
    {
        return response()->json([
            'status' => 'healthy',
            'service' => 'Torra Inteligente Sensor API',
            'version' => '1.0.0',
            'timestamp' => now()->toISOString()
        ]);
    }

    // === MÉTODOS PRIVADOS ===

    private function getHttpStatusCode(?string $errorCode): int
    {
        return match ($errorCode) {
            'DEVICE_NOT_FOUND' => 404,
            'VALIDATION_ERROR' => 422,
            'INVALID_PERIOD' => 400,
            'UNAUTHORIZED' => 401,
            'FORBIDDEN' => 403,
            'RATE_LIMITED' => 429,
            default => 500
        };
    }
}

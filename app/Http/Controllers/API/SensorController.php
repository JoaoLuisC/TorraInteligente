<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\SensorDataService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class SensorController extends Controller
{
    public function __construct(
        private readonly SensorDataService $sensorService
    ) {}

    /**
     * Recebe dados do ESP8266
     */
    public function receberDados(Request $request): JsonResponse
    {
        // Validação básica
        $validated = $request->validate([
            'device_key' => 'required|string|max:32',
            'temperatura' => 'required|numeric|between:-50,500',
            'timestamp' => 'required|numeric',
            'tempo' => 'nullable|integer|min:0',
            'rssi' => 'nullable|integer|between:-100,0',
            'uptime' => 'nullable|integer|min:0',
            'free_heap' => 'nullable|integer|min:0',
            'version' => 'nullable|string|max:10'
        ]);

        // Processar dados através do service
        $resultado = $this->sensorService->processarDadosSensor($validated);

        // Retornar resposta baseada no resultado
        $statusCode = $resultado['success'] ? 200 : $this->getStatusCode($resultado);

        return response()->json($resultado, $statusCode);
    }

    /**
     * Busca dados recentes para gráficos
     */
    public function buscarDados(Request $request, string $deviceKey): JsonResponse
    {
        $minutos = $request->get('minutos', 30);

        $resultado = $this->sensorService->buscarDadosTempoReal($deviceKey, 50);

        return response()->json($resultado, $resultado['success'] ? 200 : 404);
    }

    /**
     * Dados em tempo real (polling)
     */
    public function dadosTempoReal(string $deviceKey): JsonResponse
    {
        $resultado = $this->sensorService->buscarDadosTempoReal($deviceKey, 5);

        return response()->json($resultado, $resultado['success'] ? 200 : 404);
    }

    /**
     * Dados formatados para gráficos
     */
    public function dadosGrafico(Request $request, string $deviceKey): JsonResponse
    {
        $periodo = $request->get('periodo', '24h');

        $resultado = $this->sensorService->buscarDadosGrafico($deviceKey, $periodo);

        return response()->json($resultado, $resultado['success'] ? 200 : 404);
    }

    /**
     * Estatísticas do usuário (requer autenticação)
     */
    public function estatisticas(Request $request): JsonResponse
    {
        $periodo = $request->get('periodo');
        $userId = Auth::id();

        $resultado = $this->sensorService->buscarEstatisticas($userId, $periodo);

        return response()->json($resultado);
    }

    /**
     * Histórico de sessões (requer autenticação)
     */
    public function historico(Request $request): JsonResponse
    {
        $userId = Auth::id();

        $filtros = $request->only([
            'torrador_id',
            'data_inicio',
            'data_fim'
        ]);

        $resultado = $this->sensorService->buscarHistoricoSessoes($userId, $filtros);

        return response()->json($resultado);
    }

    // === MÉTODOS PRIVADOS ===

    private function getStatusCode(array $resultado): int
    {
        return match ($resultado['error_code'] ?? 'INTERNAL_ERROR') {
            'DEVICE_NOT_FOUND' => 404,
            'INVALID_DATA' => 422,
            'INTERNAL_ERROR' => 500,
            default => 500
        };
    }
}

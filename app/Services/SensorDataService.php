<?php

namespace App\Services;

use App\DTOs\SensorDataDTO;
use App\DTOs\TorraSessionDTO;
use App\Repositories\Contracts\SensorRepositoryInterface;
use App\Mappers\SensorDataMapper;
use App\Exceptions\DeviceNotFoundException;
use App\Exceptions\InvalidSensorDataException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Event;
use App\Events\SensorDataReceived;
use App\Events\TemperaturaAlarmEvent;

class SensorDataService
{
    public function __construct(
        private readonly SensorRepositoryInterface $sensorRepository,
        private readonly SensorDataMapper $mapper
    ) {}

    /**
     * Processa dados recebidos do sensor
     */
    public function processarDadosSensor(array $dadosRequest): array
    {
        try {
            // 1. Converter para DTO
            $dadosDTO = SensorDataDTO::fromArray($dadosRequest);

            // 2. Validar dados
            $this->validarDados($dadosDTO);

            // 3. Verificar se device existe
            $torrador = $this->sensorRepository->buscarTorradorPorDeviceKey($dadosDTO->deviceKey);
            if (!$torrador) {
                throw new DeviceNotFoundException("Device key '{$dadosDTO->deviceKey}' não encontrado");
            }

            // 4. Salvar dados
            $dadosSalvos = $this->sensorRepository->salvarDados($dadosDTO, $torrador->id);

            // 5. Verificar alertas de temperatura
            $this->verificarAlertas($dadosSalvos);

            // 6. Disparar evento
            Event::dispatch(new SensorDataReceived($dadosSalvos));

            // 7. Log de sucesso
            Log::info('Dados do sensor processados com sucesso', [
                'device_key' => $dadosDTO->deviceKey,
                'temperatura' => $dadosDTO->temperatura,
                'dados_id' => $dadosSalvos->id
            ]);

            return [
                'success' => true,
                'message' => 'Dados recebidos com sucesso',
                'dados_id' => $dadosSalvos->id,
                'timestamp' => $dadosSalvos->created_at->toISOString()
            ];

        } catch (DeviceNotFoundException $e) {
            Log::warning('Device key não encontrado', [
                'device_key' => $dadosRequest['device_key'] ?? 'N/A',
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
                'error_code' => 'DEVICE_NOT_FOUND'
            ];

        } catch (InvalidSensorDataException $e) {
            Log::warning('Dados do sensor inválidos', [
                'dados' => $dadosRequest,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
                'error_code' => 'INVALID_DATA'
            ];

        } catch (\Exception $e) {
            Log::error('Erro ao processar dados do sensor', [
                'dados' => $dadosRequest,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Erro interno do servidor',
                'error_code' => 'INTERNAL_ERROR'
            ];
        }
    }

    /**
     * Busca dados em tempo real
     */
    public function buscarDadosTempoReal(string $deviceKey, int $limite = 5): array
    {
        try {
            // Verificar se device existe
            if (!$this->sensorRepository->deviceKeyExiste($deviceKey)) {
                throw new DeviceNotFoundException("Device key '{$deviceKey}' não encontrado");
            }

            // Buscar dados recentes
            $dados = $this->sensorRepository->buscarUltimosDados($deviceKey, $limite);

            // Mapear para resposta
            $dadosMapeados = $dados->map(function ($dado) {
                return $this->mapper->toArray($dado);
            });

            return [
                'success' => true,
                'timestamp' => time(),
                'dados' => $dadosMapeados,
                'total' => $dados->count()
            ];

        } catch (DeviceNotFoundException $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'error_code' => 'DEVICE_NOT_FOUND'
            ];

        } catch (\Exception $e) {
            Log::error('Erro ao buscar dados em tempo real', [
                'device_key' => $deviceKey,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Erro interno do servidor',
                'error_code' => 'INTERNAL_ERROR'
            ];
        }
    }

    /**
     * Busca histórico de sessões
     */
    public function buscarHistoricoSessoes(int $userId, array $filtros = []): array
    {
        try {
            $sessoes = $this->sensorRepository->buscarSessoes($userId, $filtros);

            // Mapear para DTOs
            $sessoesMapeadas = $sessoes->map(function ($sessao) {
                return TorraSessionDTO::fromDatabase($sessao);
            });

            return [
                'success' => true,
                'sessoes' => $sessoesMapeadas->map(fn($s) => $s->toArray()),
                'total' => $sessoes->count()
            ];

        } catch (\Exception $e) {
            Log::error('Erro ao buscar histórico de sessões', [
                'user_id' => $userId,
                'filtros' => $filtros,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Erro ao buscar histórico',
                'error_code' => 'INTERNAL_ERROR'
            ];
        }
    }

    /**
     * Busca estatísticas do sensor
     */
    public function buscarEstatisticas(int $userId, ?string $periodo = null): array
    {
        try {
            $stats = $this->sensorRepository->buscarEstatisticas($userId, $periodo);

            return [
                'success' => true,
                'periodo' => $periodo ?? 'total',
                'estatisticas' => $stats
            ];

        } catch (\Exception $e) {
            Log::error('Erro ao buscar estatísticas', [
                'user_id' => $userId,
                'periodo' => $periodo,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Erro ao buscar estatísticas',
                'error_code' => 'INTERNAL_ERROR'
            ];
        }
    }

    /**
     * Busca dados para gráfico
     */
    public function buscarDadosGrafico(string $deviceKey, string $periodo = '24h'): array
    {
        try {
            if (!$this->sensorRepository->deviceKeyExiste($deviceKey)) {
                throw new DeviceNotFoundException("Device key '{$deviceKey}' não encontrado");
            }

            $dados = $this->sensorRepository->buscarDadosGrafico($deviceKey, $periodo);

            // Processar dados para gráfico
            $dadosGrafico = $dados->map(function ($dado) {
                return [
                    'temperatura' => (float) $dado->temperatura,
                    'tempo' => $dado->tempo,
                    'timestamp' => $dado->created_at->timestamp,
                    'tempo_formatado' => sprintf('%02d:%02d',
                        floor($dado->tempo / 60),
                        $dado->tempo % 60
                    )
                ];
            });

            return [
                'success' => true,
                'periodo' => $periodo,
                'dados' => $dadosGrafico,
                'total_pontos' => $dados->count(),
                'temperatura_maxima' => $dados->max('temperatura'),
                'temperatura_media' => $dados->avg('temperatura'),
                'duracao_total' => $dados->max('tempo')
            ];

        } catch (DeviceNotFoundException $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'error_code' => 'DEVICE_NOT_FOUND'
            ];

        } catch (\Exception $e) {
            Log::error('Erro ao buscar dados para gráfico', [
                'device_key' => $deviceKey,
                'periodo' => $periodo,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Erro ao buscar dados',
                'error_code' => 'INTERNAL_ERROR'
            ];
        }
    }

    // === MÉTODOS PRIVADOS ===

    private function validarDados(SensorDataDTO $dados): void
    {
        if (!$dados->isValid()) {
            throw new InvalidSensorDataException('Dados do sensor são inválidos');
        }

        // Validações específicas do negócio
        if ($dados->temperatura < -50 || $dados->temperatura > 500) {
            throw new InvalidSensorDataException('Temperatura fora do range válido (-50°C a 500°C)');
        }

        if ($dados->tempo < 0) {
            throw new InvalidSensorDataException('Tempo não pode ser negativo');
        }
    }

    private function verificarAlertas($dadosSensor): void
    {
        // Alerta de temperatura alta
        if ($dadosSensor->temperatura > 240) {
            Event::dispatch(new TemperaturaAlarmEvent($dadosSensor, 'alta'));
        }

        // Alerta de temperatura baixa (se já passou de um tempo mínimo)
        if ($dadosSensor->tempo > 300 && $dadosSensor->temperatura < 160) {
            Event::dispatch(new TemperaturaAlarmEvent($dadosSensor, 'baixa'));
        }
    }
}

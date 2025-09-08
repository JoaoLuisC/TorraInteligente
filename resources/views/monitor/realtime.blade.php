@extends('layouts.app')

@section('title', 'Monitor de Torra em Tempo Real')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">🌡️ Monitor de Torra em Tempo Real</h3>
                    <div class="d-flex align-items-center">
                        <select id="deviceSelect" class="form-control me-3" style="width: 200px;">
                            <option value="">Selecione um Torrador</option>
                            @foreach($torradores as $torrador)
                                <option value="{{ $torrador->codigo_conexao }}">{{ $torrador->nome }}</option>
                            @endforeach
                        </select>
                        <span id="statusConnection" class="badge bg-secondary">Desconectado</span>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Estatísticas em tempo real -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h4 id="currentTemp">--°C</h4>
                                    <small>Temperatura Atual</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h4 id="currentTime">--:--</h4>
                                    <small>Tempo de Torra</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h4 id="wifiSignal">-- dBm</h4>
                                    <small>Sinal WiFi</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h4 id="lastUpdate">--</h4>
                                    <small>Última Atualização</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gráfico -->
                    <div class="row">
                        <div class="col-12">
                            <canvas id="temperatureChart" width="400" height="150"></canvas>
                        </div>
                    </div>

                    <!-- Controles -->
                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <button id="startStop" class="btn btn-success me-2">▶️ Iniciar Monitoramento</button>
                            <button id="clearChart" class="btn btn-warning me-2">🗑️ Limpar Dados</button>
                            <button id="exportData" class="btn btn-info">📊 Exportar CSV</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Variáveis globais
let temperatureChart;
let monitoringInterval;
let isMonitoring = false;
let currentDeviceKey = '';
let chartData = {
    labels: [],
    temperatures: []
};

// Inicialização
document.addEventListener('DOMContentLoaded', function() {
    initializeChart();
    setupEventListeners();
});

// Configurar gráfico
function initializeChart() {
    const ctx = document.getElementById('temperatureChart').getContext('2d');

    temperatureChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.labels,
            datasets: [{
                label: 'Temperatura (°C)',
                data: chartData.temperatures,
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            interaction: {
                intersect: false,
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Curva de Temperatura da Torra'
                }
            },
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Tempo'
                    }
                },
                y: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Temperatura (°C)'
                    },
                    suggestedMin: 0,
                    suggestedMax: 300
                }
            }
        }
    });
}

// Event listeners
function setupEventListeners() {
    // Seleção do dispositivo
    document.getElementById('deviceSelect').addEventListener('change', function() {
        currentDeviceKey = this.value;
        if (isMonitoring) {
            stopMonitoring();
            if (currentDeviceKey) {
                startMonitoring();
            }
        }
    });

    // Botão iniciar/parar
    document.getElementById('startStop').addEventListener('click', function() {
        if (isMonitoring) {
            stopMonitoring();
        } else {
            if (!currentDeviceKey) {
                alert('Selecione um torrador primeiro!');
                return;
            }
            startMonitoring();
        }
    });

    // Limpar gráfico
    document.getElementById('clearChart').addEventListener('click', function() {
        clearChartData();
    });

    // Exportar dados
    document.getElementById('exportData').addEventListener('click', function() {
        exportToCSV();
    });
}

// Iniciar monitoramento
function startMonitoring() {
    if (!currentDeviceKey) return;

    isMonitoring = true;
    document.getElementById('startStop').innerHTML = '⏸️ Parar Monitoramento';
    document.getElementById('startStop').className = 'btn btn-danger me-2';
    document.getElementById('statusConnection').innerHTML = 'Conectando...';
    document.getElementById('statusConnection').className = 'badge bg-warning';

    // Buscar dados a cada 5 segundos
    monitoringInterval = setInterval(() => {
        fetchRealtimeData();
    }, 5000);

    // Primeira busca imediata
    fetchRealtimeData();
}

// Parar monitoramento
function stopMonitoring() {
    isMonitoring = false;
    if (monitoringInterval) {
        clearInterval(monitoringInterval);
    }

    document.getElementById('startStop').innerHTML = '▶️ Iniciar Monitoramento';
    document.getElementById('startStop').className = 'btn btn-success me-2';
    document.getElementById('statusConnection').innerHTML = 'Desconectado';
    document.getElementById('statusConnection').className = 'badge bg-secondary';
}

// Buscar dados em tempo real
async function fetchRealtimeData() {
    if (!currentDeviceKey) return;

    try {
        const response = await fetch(`/api/sensor/realtime/${currentDeviceKey}`);
        const data = await response.json();

        if (data.dados && data.dados.length > 0) {
            const ultimoDado = data.dados[0]; // Mais recente
            updateUI(ultimoDado);
            addDataToChart(ultimoDado);

            document.getElementById('statusConnection').innerHTML = 'Conectado ✅';
            document.getElementById('statusConnection').className = 'badge bg-success';
        }

    } catch (error) {
        console.error('Erro ao buscar dados:', error);
        document.getElementById('statusConnection').innerHTML = 'Erro ❌';
        document.getElementById('statusConnection').className = 'badge bg-danger';
    }
}

// Atualizar interface
function updateUI(dado) {
    document.getElementById('currentTemp').textContent = `${parseFloat(dado.temperatura).toFixed(1)}°C`;

    // Converter tempo em segundos para MM:SS
    const minutos = Math.floor(dado.tempo / 60);
    const segundos = dado.tempo % 60;
    document.getElementById('currentTime').textContent = `${minutos.toString().padStart(2, '0')}:${segundos.toString().padStart(2, '0')}`;

    document.getElementById('wifiSignal').textContent = `${dado.rssi || '--'} dBm`;

    const agora = new Date();
    document.getElementById('lastUpdate').textContent = agora.toLocaleTimeString();
}

// Adicionar dados ao gráfico
function addDataToChart(dado) {
    const minutos = Math.floor(dado.tempo / 60);
    const segundos = dado.tempo % 60;
    const timeLabel = `${minutos}:${segundos.toString().padStart(2, '0')}`;

    chartData.labels.push(timeLabel);
    chartData.temperatures.push(parseFloat(dado.temperatura));

    // Manter apenas últimos 50 pontos
    if (chartData.labels.length > 50) {
        chartData.labels.shift();
        chartData.temperatures.shift();
    }

    temperatureChart.update('none'); // Atualização sem animação para tempo real
}

// Limpar dados do gráfico
function clearChartData() {
    chartData.labels = [];
    chartData.temperatures = [];
    temperatureChart.update();

    // Limpar cards
    document.getElementById('currentTemp').textContent = '--°C';
    document.getElementById('currentTime').textContent = '--:--';
    document.getElementById('wifiSignal').textContent = '-- dBm';
    document.getElementById('lastUpdate').textContent = '--';
}

// Exportar para CSV
function exportToCSV() {
    if (chartData.labels.length === 0) {
        alert('Nenhum dado para exportar!');
        return;
    }

    let csv = 'Tempo,Temperatura\n';
    for (let i = 0; i < chartData.labels.length; i++) {
        csv += `${chartData.labels[i]},${chartData.temperatures[i]}\n`;
    }

    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `torra_${currentDeviceKey}_${new Date().toISOString().split('T')[0]}.csv`;
    a.click();
    window.URL.revokeObjectURL(url);
}
</script>
@endpush

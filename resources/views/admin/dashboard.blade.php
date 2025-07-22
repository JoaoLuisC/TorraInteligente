@extends('master')

@section('title', 'Dashboard Administrativo')
@section('breadcrumb-title', 'Dashboard Admin')

@section('MainContent')
<div class="container-fluid">
    <!-- Estatísticas Principais -->
    <div class="row mt-5">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $estatisticas['total_usuarios'] }}</h3>
                    <p>Total de Usuários</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('admin.usuarios') }}" class="small-box-footer">
                    Gerenciar <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $estatisticas['total_torras'] }}</h3>
                    <p>Torras Cadastradas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-coffee"></i>
                </div>
                <div class="small-box-footer">
                    <span class="text-white">Sistema ativo</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $estatisticas['total_analises'] }}</h3>
                    <p>Análises Realizadas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-microscope"></i>
                </div>
                <div class="small-box-footer">
                    <span class="text-white">Este mês</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $estatisticas['analises_pendentes'] }}</h3>
                    <p>Análises Pendentes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="small-box-footer">
                    <span class="text-white">Aguardando</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos e Informações -->
    <div class="row">
        <!-- Distribuição de Usuários por Tipo -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-pie-chart me-2"></i>
                        Distribuição de Usuários
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="usuariosChart" width="400" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Análises por Mês -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line me-2"></i>
                        Análises por Mês
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="analisesChart" width="400" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabelas de Resumo -->
    <div class="row mt-4">
        <!-- Usuários Recentes -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-plus me-2"></i>
                        Usuários Recentes
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.usuarios') }}" class="btn btn-tool">
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if(count($usuariosRecentes) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Usuário</th>
                                        <th>Tipo</th>
                                        <th>Cadastro</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($usuariosRecentes as $usuario)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($usuario->tipo === 'Administrador')
                                                        <i class="fas fa-crown text-warning me-2"></i>
                                                    @elseif($usuario->tipo === 'Analista')
                                                        <i class="fas fa-microscope text-info me-2"></i>
                                                    @else
                                                        <i class="fas fa-user text-success me-2"></i>
                                                    @endif
                                                    <div>
                                                        <strong>{{ $usuario->nome }} {{ $usuario->sobrenome }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $usuario->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($usuario->tipo === 'Administrador')
                                                    <span class="badge bg-warning text-dark">Admin</span>
                                                @elseif($usuario->tipo === 'Analista')
                                                    <span class="badge bg-info">Analista</span>
                                                @else
                                                    <span class="badge bg-success">Produtor</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small>{{ \Carbon\Carbon::parse($usuario->criado_em)->diffForHumans() }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <p class="text-muted">Nenhum usuário recente</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Análises Recentes -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-microscope me-2"></i>
                        Análises Recentes
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('analise.historico') }}" class="btn btn-tool">
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if(count($analisesRecentes) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Torra</th>
                                        <th>Nota</th>
                                        <th>Data</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($analisesRecentes as $analise)
                                        <tr>
                                            <td>
                                                <div>
                                                    <strong>{{ $analise->torra_nome }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $analise->produtor_nome }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $nota = $analise->nota_final;
                                                    $badgeClass = 'bg-secondary';
                                                    if ($nota >= 85) $badgeClass = 'bg-success';
                                                    elseif ($nota >= 80) $badgeClass = 'bg-warning text-dark';
                                                    elseif ($nota >= 70) $badgeClass = 'bg-info text-dark';
                                                    else $badgeClass = 'bg-danger';
                                                @endphp
                                                <span class="badge {{ $badgeClass }}">
                                                    {{ number_format($nota, 1) }}
                                                </span>
                                            </td>
                                            <td>
                                                <small>{{ $analise->data_analise->diffForHumans() }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <p class="text-muted">Nenhuma análise recente</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
/* Cores personalizadas para os cards da dashboard admin */
.bg-success {
    background: linear-gradient(135deg, #28a745, #20c997) !important; /* Verde mais claro */
}

.bg-danger {
    background: linear-gradient(135deg, #dc3545, #fd7e14) !important; /* Vermelho claro */
}

.bg-warning {
    background: linear-gradient(135deg, #ffc107, #fd7e14) !important; /* Amarelo mantido */
}
</style>
@endpush

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfico de Distribuição de Usuários
    const usuariosCtx = document.getElementById('usuariosChart').getContext('2d');
    new Chart(usuariosCtx, {
        type: 'doughnut',
        data: {
            labels: ['Produtores', 'Analistas', 'Administradores'],
            datasets: [{
                data: [
                    {{ $estatisticas['produtores'] }},
                    {{ $estatisticas['analistas'] }},
                    {{ $estatisticas['administradores'] }}
                ],
                backgroundColor: [
                    '#28a745',
                    '#17a2b8',
                    '#ffc107'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Gráfico de Análises por Mês
    const analisesCtx = document.getElementById('analisesChart').getContext('2d');
    new Chart(analisesCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($graficoAnalises['labels']) !!},
            datasets: [{
                label: 'Análises Realizadas',
                data: {!! json_encode($graficoAnalises['data']) !!},
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});
</script>
@endsection

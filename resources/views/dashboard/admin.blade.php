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
                    <h3>{{ $estatisticas['total_usuarios'] ?? 0 }}</h3>
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
                    <h3>{{ $estatisticas['total_torras'] ?? 0 }}</h3>
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
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $estatisticas['total_analises'] ?? 0 }}</h3>
                    <p>Análises Realizadas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-microscope"></i>
                </div>
                <div class="small-box-footer">
                    <span class="text-white">Total</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $estatisticas['analises_pendentes'] ?? 0 }}</h3>
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

    <!-- Distribuição de Usuários -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-1"></i>
                        Distribuição de Usuários
                    </h3>
                </div>
                <div class="card-body">
                    @if($distribuicaoUsuarios && $distribuicaoUsuarios->count() > 0)
                        <div class="row">
                            <div class="col-md-8">
                                <canvas id="usuariosChart" height="150"></canvas>
                            </div>
                            <div class="col-md-4">
                                <ul class="list-unstyled">
                                    @foreach($distribuicaoUsuarios as $tipo)
                                        <li>
                                            <i class="fas fa-circle" style="color:
                                                @if($tipo->tipo == 'Administrador') #dc3545
                                                @elseif($tipo->tipo == 'Analista') #007bff
                                                @else #28a745 @endif"></i>
                                            {{ $tipo->tipo }}: <strong>{{ $tipo->total }}</strong>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @else
                        <div class="text-center text-muted">
                            <i class="fas fa-info-circle fa-2x mb-2"></i>
                            <p>Aguardando dados de usuários...</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle mr-1"></i>
                        Estatísticas por Tipo
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <div class="description-block border-right">
                                <h5 class="description-header text-success">{{ $estatisticas['produtores'] ?? 0 }}</h5>
                                <span class="description-text">PRODUTORES</span>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="description-block border-right">
                                <h5 class="description-header text-info">{{ $estatisticas['analistas'] ?? 0 }}</h5>
                                <span class="description-text">ANALISTAS</span>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="description-block">
                                <h5 class="description-header text-danger">{{ $estatisticas['administradores'] ?? 0 }}</h5>
                                <span class="description-text">ADMINS</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Usuários Recentes -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-plus mr-1"></i>
                        Usuários Recentes
                    </h3>
                </div>
                <div class="card-body">
                    @if($usuariosRecentes && $usuariosRecentes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Tipo</th>
                                        <th>Cadastro</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($usuariosRecentes as $usuario)
                                    <tr>
                                        <td>{{ $usuario->nome }} {{ $usuario->sobrenome }}</td>
                                        <td>
                                            <span class="badge badge-
                                                @if($usuario->tipo == 'Administrador') danger
                                                @elseif($usuario->tipo == 'Analista') info
                                                @else success @endif">
                                                {{ $usuario->tipo }}
                                            </span>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($usuario->criado_em)->format('d/m/Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted">
                            <i class="fas fa-users fa-2x mb-2"></i>
                            <p>Nenhum usuário recente encontrado</p>
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
                        <i class="fas fa-microscope mr-1"></i>
                        Análises Recentes
                    </h3>
                </div>
                <div class="card-body">
                    @if($analisesRecentes && $analisesRecentes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Torra</th>
                                        <th>Produtor</th>
                                        <th>Analista</th>
                                        <th>Data</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($analisesRecentes as $analise)
                                    <tr>
                                        <td>{{ $analise->torra_nome ?? 'N/A' }}</td>
                                        <td>{{ $analise->produtor_nome ?? 'N/A' }} {{ $analise->produtor_sobrenome ?? '' }}</td>
                                        <td>{{ $analise->analista_nome ?? 'N/A' }} {{ $analise->analista_sobrenome ?? '' }}</td>
                                        <td>{{ isset($analise->criado_em) ? \Carbon\Carbon::parse($analise->criado_em)->format('d/m/Y') : 'N/A' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted">
                            <i class="fas fa-flask fa-2x mb-2"></i>
                            <p>Aguardando dados de análises...</p>
                            <small class="text-muted">As análises aparecerão aqui quando o sistema estiver configurado</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if(($estatisticas['total_torras'] ?? 0) == 0 && ($estatisticas['total_analises'] ?? 0) == 0)
    <!-- Mensagem de Sistema Novo -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle mr-1"></i>
                        Sistema Iniciando
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h4>Bem-vindo ao Sistema de Análise de Café!</h4>
                            <p>Este é um sistema novo e está sendo configurado. Aqui estão os próximos passos:</p>
                            <ul>
                                <li><strong>Usuários:</strong> Você já tem {{ $estatisticas['total_usuarios'] ?? 0 }} usuário(s) cadastrado(s)</li>
                                <li><strong>Produtores:</strong> Podem cadastrar suas torras de café</li>
                                <li><strong>Analistas:</strong> Podem realizar análises sensoriais</li>
                                <li><strong>Sistema:</strong> Configuração automática em andamento</li>
                            </ul>
                        </div>
                        <div class="col-md-4 text-center">
                            <i class="fas fa-seedling fa-5x text-success"></i>
                            <h5 class="mt-2">Sistema Novo</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Scripts para gráficos -->
@if($distribuicaoUsuarios && $distribuicaoUsuarios->count() > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('usuariosChart').getContext('2d');
    const dados = @json($distribuicaoUsuarios);

    const labels = dados.map(item => item.tipo);
    const values = dados.map(item => item.total);
    const colors = labels.map(tipo => {
        switch(tipo) {
            case 'Administrador': return '#dc3545';
            case 'Analista': return '#007bff';
            default: return '#28a745';
        }
    });

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: colors,
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                display: false
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        const total = data.datasets[0].data.reduce((a, b) => a + b, 0);
                        const percentage = Math.round((data.datasets[0].data[tooltipItem.index] / total) * 100);
                        return data.labels[tooltipItem.index] + ': ' + percentage + '%';
                    }
                }
            }
        }
    });
});
</script>
@endif
@endsection

{{-- resources/views/usuarios/dashboard.blade.php --}}
@extends('master')

@section('title', 'Dashboard')
@section('breadcrumb-title', 'Dashboard')

@section('MainContent')
<div class="container-fluid">
    <!-- Mensagens de Feedback -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Erro de validação:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Boas-vindas -->
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info border-0 shadow-sm mb-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-coffee fa-2x text-primary me-3"></i>
                    <div>
                        <h4 class="alert-heading mb-1">Bem-vindo, {{ Auth::user()->nome }}!</h4>
                        <p class="mb-0">Gerencie suas torras e acompanhe suas análises sensoriais.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cards de Estatísticas -->
    <div class="row mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total de Torras
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $estatisticas['total_torras'] }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-primary">
                                <i class="fas fa-fire text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Torras Avaliadas
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $estatisticas['torras_avaliadas'] }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-success">
                                <i class="fas fa-check-circle text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Aguardando Avaliação
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $estatisticas['torras_aguardando'] }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-warning">
                                <i class="fas fa-hourglass-half text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Não Avaliadas
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $estatisticas['torras_nao_avaliadas'] }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-danger">
                                <i class="fas fa-clock text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Últimas Torras -->
        <div class="col-xl-6 col-lg-12 mb-4">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Últimas Torras</h3>
                        </div>
                        <div class="col text-end">
                            <a href="{{ route('torras.index') }}" class="btn btn-sm btn-primary">Ver Todas</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($ultimasTorras->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($ultimasTorras as $torra)
                                <div class="list-group-item px-0">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <div class="icon-shape bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fas fa-seedling"></i>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <h5 class="mb-1">{{ $torra->nome }}</h5>
                                            <p class="mb-1">
                                                <span class="variedade-sem-bg">{{ $torra->variedade }}</span> •
                                                {{ $torra->fermentacao }} •
                                                {{ $torra->finalidade }}
                                            </p>
                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($torra->criado_em)->format('d/m/Y H:i') }}
                                            </small>
                                        </div>
                                        <div class="col-auto text-end">
                                            @if($torra->status === 'avaliada' && $torra->nota_final)
                                                <div>
                                                    <span class="nota-final-destaque">{{ number_format($torra->nota_final, 1) }}</span>
                                                    <br>
                                                    <span class="badge bg-success">Avaliada</span>
                                                </div>
                                            @elseif($torra->status === 'aguardando_avaliacao')
                                                <span class="badge bg-info">Aguardando</span>
                                            @else
                                                <span class="badge bg-warning">Pendente</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-fire fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Nenhuma torra encontrada</h5>
                            <p class="text-muted">Crie sua primeira torra para começar!</p>
                            <a href="{{ route('torras.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Nova Torra
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Melhores Torras -->
        <div class="col-xl-6 col-lg-12 mb-4">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Melhores Avaliações</h3>
                        </div>
                        <div class="col text-end">
                            <a href="{{ route('torras.index', ['filtro_avaliacao' => 'avaliadas']) }}" class="btn btn-sm btn-outline-primary">Ver Histórico</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($melhoresTorras->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($melhoresTorras as $torra)
                                <div class="list-group-item px-0">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <div class="icon-shape bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fas fa-trophy"></i>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <h5 class="mb-1">{{ $torra->torra_nome }}</h5>
                                            <p class="mb-1">
                                                <span class="variedade-sem-bg">{{ $torra->variedade }}</span> •
                                                {{ $torra->fermentacao }}
                                            </p>
                                            <small class="text-muted">
                                                Avaliada em {{ \Carbon\Carbon::parse($torra->data_avaliacao)->format('d/m/Y') }}
                                            </small>
                                        </div>
                                        <div class="col-auto">
                                            <span class="nota-final-destaque">{{ number_format($torra->nota_final, 1) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-trophy fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Nenhuma avaliação encontrada</h5>
                            <p class="text-muted">Solicite análises para suas torras!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Solicitações Recentes -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Solicitações de Análise Recentes</h3>
                        </div>
                        <div class="col text-end">
                            <a href="{{ route('torras.solicitar-avaliacao') }}" class="btn btn-sm btn-success">
                                <i class="fas fa-plus"></i> Nova Solicitação
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($solicitacoesRecentes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Torra</th>
                                        <th>Analista</th>
                                        <th>Status</th>
                                        <th>Data Solicitação</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($solicitacoesRecentes as $solicitacao)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="icon-shape bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                                        <i class="fas fa-coffee" style="font-size: 14px;"></i>
                                                    </div>
                                                    <div>
                                                        <strong>{{ $solicitacao->torra_nome }}</strong>
                                                        <br>
                                                        <small class="text-muted">
                                                            <span class="variedade-sem-bg">{{ $solicitacao->variedade }}</span> •
                                                            {{ $solicitacao->finalidade }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <strong>{{ $solicitacao->analista_nome }} {{ $solicitacao->analista_sobrenome }}</strong>
                                            </td>
                                            <td>
                                                @if($solicitacao->status === 'Pendente')
                                                    <span class="badge bg-warning">
                                                        <i class="fas fa-clock"></i> Pendente
                                                    </span>
                                                @elseif($solicitacao->status === 'Em Análise')
                                                    <span class="badge bg-info">
                                                        <i class="fas fa-spinner"></i> Em Análise
                                                    </span>
                                                @elseif($solicitacao->status === 'Concluída')
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check"></i> Concluída
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-times"></i> Cancelada
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-success">
                                                    <i class="bi bi-calendar"></i>
                                                    {{ \Carbon\Carbon::parse($solicitacao->criado_em)->format('d/m/Y') }}
                                                </small>
                                                <br>
                                                <small class="text-muted">
                                                    {{ \Carbon\Carbon::parse($solicitacao->criado_em)->format('H:i') }}
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    @if($solicitacao->status === 'Concluída')
                                                        <a href="#" class="btn btn-sm btn-outline-primary" title="Ver Resultado">
                                                            Visualizar
                                                        </a>
                                                    @else
                                                        <span class="text-muted">Aguardando análise</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Nenhuma solicitação encontrada</h5>
                            <p class="text-muted">Solicite uma análise para suas torras!</p>
                            <a href="{{ route('torras.solicitar-avaliacao') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Nova Solicitação
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .card-stats {
        transition: transform 0.2s;
    }

    .card-stats:hover {
        transform: translateY(-2px);
    }

    .icon {
        width: 3rem;
        height: 3rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .icon-shape {
        display: inline-flex;
        padding: 12px;
        text-align: center;
        border-radius: 50%;
        align-items: center;
        justify-content: center;
    }

    .bg-gradient-green {
        background: linear-gradient(45deg, #28a745, #20c997);
    }

    .nota-final-destaque {
        font-size: 1.5rem;
        font-weight: bold;
        color: #28a745;
        background: rgba(40, 167, 69, 0.1);
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        border: 2px solid rgba(40, 167, 69, 0.2);
    }

    /* Novos estilos para cards melhorados */
    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }

    .border-left-success {
        border-left: 0.25rem solid #28a745 !important;
    }

    .border-left-warning {
        border-left: 0.25rem solid #ffc107 !important;
    }

    .border-left-danger {
        border-left: 0.25rem solid #dc3545 !important;
    }

    /* Cores personalizadas para os cards */
    .bg-success {
        background: linear-gradient(135deg, #28a745, #20c997) !important; /* Verde mais claro */
    }

    .bg-danger {
        background: linear-gradient(135deg, #dc3545, #fd7e14) !important; /* Vermelho claro */
    }

    .bg-warning {
        background: linear-gradient(135deg, #ffc107, #fd7e14) !important; /* Amarelo mantido */
    }

    .icon-circle {
        height: 2.5rem;
        width: 2.5rem;
        border-radius: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .text-xs {
        font-size: 0.75rem;
    }

    .text-gray-800 {
        color: #5a5c69 !important;
    }

    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .py-2 {
        padding-top: 0.5rem !important;
        padding-bottom: 0.5rem !important;
    }

    .no-gutters {
        margin-right: 0;
        margin-left: 0;
    }

    .no-gutters > .col,
    .no-gutters > [class*="col-"] {
        padding-right: 0;
        padding-left: 0;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/dashboard-produtor.js') }}"></script>
@endpush

@extends('master')

@section('title', 'Dashboard Analista - Michelangelo')
@section('breadcrumb-title', 'Dashboard Analista')

@section('MainContent')
<div class="container-fluid">
    <!-- Cards de Estatísticas -->
    <div class="row mb-4">
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">{{ $estatisticas['concluidas'] ?? 0 }}</h4>
                            <p class="mb-0">Concluídas</p>
                        </div>
                        <div class="card-icon">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">{{ $estatisticas['em_analise'] ?? 0 }}</h4>
                            <p class="mb-0">Em Análise</p>
                        </div>
                        <div class="card-icon">
                            <i class="fas fa-eye fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">{{ $estatisticas['pendentes'] ?? 0 }}</h4>
                            <p class="mb-0">Análises Pendentes</p>
                        </div>
                        <div class="card-icon">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Solicitações Pendentes -->
        <div class="col-12">
            <div class="card">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">
                            <i class="fas fa-tasks"></i> Solicitações Pendentes
                        </h3>
                        <a href="{{ route('analise.pendentes') }}" class="btn btn-primary">
                            Ver Todas
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if(isset($solicitacoesPendentes) && $solicitacoesPendentes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Torra</th>
                                        <th>Produtor</th>
                                        <th>Data Solicitação</th>
                                        <th>Status</th>
                                        <th class="text-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($solicitacoesPendentes as $solicitacao)
                                        <tr>
                                            <td>
                                                <strong>{{ $solicitacao->torra_nome }}</strong>
                                                <br>
                                                <small class="text-muted variedade-sem-bg">{{ $solicitacao->variedade }} - {{ $solicitacao->finalidade }}</small>
                                            </td>
                                            <td>{{ $solicitacao->produtor_nome }}</td>
                                            <td>{{ \Carbon\Carbon::parse($solicitacao->criado_em)->format('d/m/Y') }}</td>
                                            <td>
                                                <span class="badge bg-warning">{{ $solicitacao->status }}</span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('analise.analisar', $solicitacao->id) }}"
                                                   class="btn btn-sm btn-primary">
                                                    Analisar
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Nenhuma solicitação pendente no momento.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Análises Recentes -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">
                            <i class="fas fa-history"></i> Análises Recentes
                        </h3>
                        <a href="{{ route('analise.historico') }}" class="btn btn-outline-primary">
                            Ver Histórico Completo
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if(isset($analisesRecentes) && $analisesRecentes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Torra</th>
                                        <th>Produtor</th>
                                        <th>Nota Final</th>
                                        <th>Data Análise</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($analisesRecentes as $analise)
                                        <tr>
                                            <td>
                                                <strong>{{ $analise->torra_nome }}</strong>
                                                <br>
                                                <small class="text-muted variedade-sem-bg">{{ $analise->variedade }} - {{ $analise->finalidade }}</small>
                                            </td>
                                            <td>{{ $analise->produtor_nome }}</td>
                                            <td>
                                                <span class="nota-final-destaque">{{ number_format($analise->nota_final, 1) }}</span>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($analise->data_analise)->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <span class="badge bg-success">Concluída</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Nenhuma análise realizada ainda.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Scripts removidos - sem necessidade de gráfico -->
@endpush

@push('styles')
<style>
.card-icon {
    opacity: 0.8;
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

.nota-final-destaque {
    font-size: 1.1rem;
    font-weight: bold;
    color: #28a745;
    padding: 0.25rem 0.5rem;
    border: 2px solid #28a745;
    border-radius: 6px;
    background-color: rgba(40, 167, 69, 0.1);
}

.variedade-sem-bg {
    background: none !important;
    background-color: transparent !important;
    padding: 0 !important;
    border: none !important;
}

.table-hover tbody tr:hover {
    background-color: rgba(0,123,255,0.05);
}

.card {
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border: none;
    border-radius: 10px;
}

.card-header {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-radius: 10px 10px 0 0;
}
</style>
@endpush
@endsection

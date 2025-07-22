@extends('master')

@section('title', 'Histórico de Análises')
@section('breadcrumb-title', 'Histórico de Análises')

@section('MainContent')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card mt-5">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="card-title">
                                <i class="fas fa-history"></i> Análises Realizadas
                            </h3>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('analise.pendentes') }}" class="btn btn-primary">
                                <i class="fas fa-list"></i> Análises Pendentes
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($analises->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="border-0">Torra</th>
                                        <th class="border-0">Produtor</th>
                                        <th class="border-0">Variedade</th>
                                        <th class="border-0">Nota Final</th>
                                        <th class="border-0">Data Análise</th>
                                        <th class="border-0 text-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($analises as $analise)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-coffee text-success me-2"></i>
                                                    <strong>{{ $analise->torra_nome }}</strong>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-muted">
                                                    {{ $analise->produtor_nome }} {{ $analise->produtor_sobrenome }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-muted variedade-sem-bg">
                                                    {{ $analise->torra_variedade }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @php
                                                        $notaFinal = $analise->nota_final;
                                                    @endphp
                                                    <span class="nota-final-historico">
                                                        {{ number_format($notaFinal, 1) }}
                                                    </span>
                                                    @if($notaFinal >= 85)
                                                        <i class="fas fa-award text-warning ms-2" title="Café Especial"></i>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-muted">
                                                    <i class="fas fa-calendar-alt me-1"></i>
                                                    {{ $analise->data_analise->format('d/m/Y') }}
                                                    <br>
                                                    <small>{{ $analise->data_analise->format('H:i') }}</small>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <button type="button"
                                                            class="btn btn-outline-primary btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#detalhesModal{{ $analise->id }}">
                                                        Visualizar
                                                    </button>
                                                    <a href="{{ route('analise.analisar', $analise->solicitacao_id) }}"
                                                       class="btn btn-outline-warning btn-sm">
                                                        Editar
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer bg-white border-0">
                            {{ $analises->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-flask fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Nenhuma análise realizada ainda</h5>
                            <p class="text-muted">As análises realizadas aparecerão aqui.</p>
                            <a href="{{ route('analise.pendentes') }}" class="btn btn-primary">
                                <i class="fas fa-list me-2"></i>
                                Ver Análises Pendentes
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modais de Detalhes -->
@foreach($analises as $analise)
<div class="modal fade" id="detalhesModal{{ $analise->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-microscope me-2"></i>
                    Detalhes da Análise - {{ $analise->torra_nome }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Informações da Torra -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6><i class="fas fa-coffee"></i> Informações da Torra</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Nome:</strong></td>
                                <td>{{ $analise->torra_nome }}</td>
                            </tr>
                            <tr>
                                <td><strong>Produtor:</strong></td>
                                <td>{{ $analise->produtor_nome }} {{ $analise->produtor_sobrenome }}</td>
                            </tr>
                            <tr>
                                <td><strong>Variedade:</strong></td>
                                <td>{{ $analise->torra_variedade }}</td>
                            </tr>
                            <tr>
                                <td><strong>Fermentação:</strong></td>
                                <td>{{ $analise->torra_fermentacao }}</td>
                            </tr>
                            <tr>
                                <td><strong>Densidade:</strong></td>
                                <td>{{ number_format($analise->torra_densidade, 2) }} g/cm³</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-chart-bar"></i> Resultado da Análise</h6>
                        <div class="text-center">
                            @php
                                $notaFinal = $analise->nota_final;
                                $badgeClass = 'bg-secondary';
                                $classificacao = 'Comercial';
                                if ($notaFinal >= 85) {
                                    $badgeClass = 'bg-success';
                                    $classificacao = 'Especial';
                                } elseif ($notaFinal >= 80) {
                                    $badgeClass = 'bg-warning text-dark';
                                    $classificacao = 'Muito Bom';
                                } elseif ($notaFinal >= 70) {
                                    $badgeClass = 'bg-info text-dark';
                                    $classificacao = 'Bom';
                                } else {
                                    $badgeClass = 'bg-danger';
                                    $classificacao = 'Abaixo do Padrão';
                                }
                            @endphp
                            <div class="mb-3">
                                <span class="badge {{ $badgeClass }} fs-2 p-3">
                                    {{ number_format($notaFinal, 2) }}
                                </span>
                            </div>
                            <h5 class="text-muted">{{ $classificacao }}</h5>
                            @if($notaFinal >= 85)
                                <i class="fas fa-award text-warning fa-2x"></i>
                                <p class="text-success mt-2"><strong>Café Especial!</strong></p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Detalhes da Avaliação -->
                <h6><i class="fas fa-list"></i> Pontuações Detalhadas</h6>
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <td>Aroma do Pó:</td>
                                <td><strong>{{ number_format($analise->aroma_po, 1) }}</strong></td>
                            </tr>
                            <tr>
                                <td>Fragrância do Café:</td>
                                <td><strong>{{ number_format($analise->fragrancia_cafe, 1) }}</strong></td>
                            </tr>
                            <tr>
                                <td>Sabor:</td>
                                <td><strong>{{ number_format($analise->sabor, 1) }}</strong></td>
                            </tr>
                            <tr>
                                <td>Acidez:</td>
                                <td><strong>{{ number_format($analise->acidez, 1) }}</strong></td>
                            </tr>
                            <tr>
                                <td>Corpo:</td>
                                <td><strong>{{ number_format($analise->corpo, 1) }}</strong></td>
                            </tr>
                            <tr>
                                <td>Retrogosto:</td>
                                <td><strong>{{ number_format($analise->retro_gosto, 1) }}</strong></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <td>Equilíbrio:</td>
                                <td><strong>{{ number_format($analise->equilibrio, 1) }}</strong></td>
                            </tr>
                            <tr>
                                <td>Doçura:</td>
                                <td><strong>{{ number_format($analise->docura, 1) }}</strong></td>
                            </tr>
                            <tr>
                                <td>Uniformidade:</td>
                                <td><strong>{{ number_format($analise->uniformidade, 1) }}</strong></td>
                            </tr>
                            <tr>
                                <td>Defeitos:</td>
                                <td><strong>{{ number_format($analise->defeitos, 1) }}</strong></td>
                            </tr>
                            <tr>
                                <td>Balanceamento:</td>
                                <td><strong>{{ number_format($analise->balanceamento, 1) }}</strong></td>
                            </tr>
                            <tr class="table-success">
                                <td><strong>Nota Final:</strong></td>
                                <td><strong>{{ number_format($analise->nota_final, 2) }}</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="{{ route('analise.analisar', $analise->solicitacao_id) }}" class="btn btn-warning">
                    Editar Análise
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Fechar
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach

@push('styles')
<style>
.nota-final-historico {
    font-size: 1.2rem;
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
</style>
@endpush

@endsection

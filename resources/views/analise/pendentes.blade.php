@extends('master')

@section('title', 'Análises Pendentes')
@section('breadcrumb-title', 'Análises Pendentes')

@section('MainContent')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Análises Pendentes -->
            <div class="card mb-4 mt-5">
                <div class="card-header border-0">
                    <h3 class="card-title">
                        <i class="fas fa-clipboard-check"></i> Análises Pendentes
                    </h3>
                    <div class="card-tools">
                        <span class="badge bg-warning">{{ $solicitacoesPendentes->count() }} pendente(s)</span>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($solicitacoesPendentes->count() == 0)
                        <div class="alert alert-info" role="alert">
                            <h4 class="alert-heading">
                                <i class="fas fa-info-circle"></i> Nenhuma análise pendente
                            </h4>
                            <p>Não há solicitações de análise pendentes para você no momento.</p>
                        </div>
                    @else
                        <div class="table-responsive p-0">
                            <table class="table table-striped align-middle">
                                <thead>
                                    <tr>
                                        <th>Nome da Torra</th>
                                        <th>Produtor</th>
                                        <th>Características</th>
                                        <th>Status</th>
                                        <th>Data Solicitação</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                                                        @foreach($solicitacoesPendentes as $solicitacao)
                                    <tr>
                                        <td>
                                            <img
                                                src="{{ asset('images/icons/3-graos-de-cafe.png') }}"
                                                alt="{{ $solicitacao->torra_nome }}"
                                                class="rounded-circle img-size-32 me-2"
                                            />
                                            <strong>{{ $solicitacao->torra_nome }}</strong>
                                        </td>
                                        <td>
                                            <strong>{{ $solicitacao->produtor_nome }} {{ $solicitacao->produtor_sobrenome }}</strong>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <strong>Variedade:</strong> {{ $solicitacao->torra_variedade }}<br>
                                                <strong>Fermentação:</strong> {{ $solicitacao->torra_fermentacao }}<br>
                                                <strong>Finalidade:</strong> {{ $solicitacao->torra_finalidade }}
                                            </small>
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
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-success me-1">
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
                                                <a href="{{ route('analise.analisar', $solicitacao->id) }}"
                                                   class="btn btn-sm btn-primary"
                                                   title="Realizar análise">
                                                    <i class="fas fa-microscope"></i> Analisar
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

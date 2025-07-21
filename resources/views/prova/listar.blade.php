@extends('master')

@section('title', 'Solicitações de Prova')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-flask"></i> Solicitações em Análise
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('prova.solicitar') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Nova Solicitação
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($solicitacoes->isEmpty())
                        <div class="alert alert-info" role="alert">
                            <h4 class="alert-heading">
                                <i class="fas fa-info-circle"></i> Nenhuma solicitação em análise
                            </h4>
                            <p>Não há solicitações de prova sendo analisadas no momento.</p>
                            <hr>
                            <p class="mb-0">
                                <a href="{{ route('prova.solicitar') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Criar primeira solicitação
                                </a>
                            </p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Solicitante</th>
                                        <th>Analista</th>
                                        <th>Torra</th>
                                        <th>Produtor</th>
                                        <th>Notas</th>
                                        <th>Status</th>
                                        <th>Data Solicitação</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($solicitacoes as $solicitacao)
                                        <tr>
                                            <td>{{ $solicitacao->id }}</td>
                                            <td>{{ $solicitacao->solicitante_nome }} {{ $solicitacao->solicitante_sobrenome }}</td>
                                            <td>{{ $solicitacao->analista_nome }} {{ $solicitacao->analista_sobrenome }}</td>
                                            <td>
                                                <strong>{{ $solicitacao->torra_nome }}</strong><br>
                                                <small class="text-muted">{{ $solicitacao->torra_variedade }}</small>
                                            </td>
                                            <td>{{ $solicitacao->produtor_nome }} {{ $solicitacao->produtor_sobrenome }}</td>
                                            <td>
                                                @if($solicitacao->notas)
                                                    <span data-toggle="tooltip" data-placement="top"
                                                          title="{{ $solicitacao->notas }}" style="cursor: help;">
                                                        {{ Str::limit($solicitacao->notas, 30) }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @switch($solicitacao->status)
                                                    @case('Pendente')
                                                        <span class="badge bg-warning">{{ $solicitacao->status }}</span>
                                                        @break
                                                    @case('Em Análise')
                                                        <span class="badge bg-info">{{ $solicitacao->status }}</span>
                                                        @break
                                                    @case('Concluída')
                                                        <span class="badge bg-success">{{ $solicitacao->status }}</span>
                                                        @break
                                                    @case('Cancelada')
                                                        <span class="badge bg-danger">{{ $solicitacao->status }}</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-secondary">{{ $solicitacao->status }}</span>
                                                @endswitch
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($solicitacao->criado_em)->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                                            onclick="verDetalhes({{ $solicitacao->id }})"
                                                            title="Ver detalhes">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    @if($solicitacao->status === 'Pendente')
                                                        <button type="button" class="btn btn-sm btn-outline-success"
                                                                onclick="alterarStatus({{ $solicitacao->id }}, 'Em Análise')"
                                                                title="Iniciar análise">
                                                            <i class="fas fa-play"></i>
                                                        </button>
                                                    @endif
                                                    @if(in_array($solicitacao->status, ['Pendente', 'Em Análise']))
                                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                                onclick="alterarStatus({{ $solicitacao->id }}, 'Cancelada')"
                                                                title="Cancelar">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    @endif
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

<!-- Modal para Detalhes -->
<div class="modal fade" id="modalDetalhes" tabindex="-1" aria-labelledby="modalDetalhesLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetalhesLabel">Detalhes da Solicitação</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalDetalhesContent">
                <!-- Conteúdo carregado via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Inicializar tooltips
$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
});

function verDetalhes(id) {
    $('#modalDetalhesContent').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Carregando...</div>');
    $('#modalDetalhes').modal('show');

    // Simular carregamento de detalhes (implementar rota depois)
    setTimeout(function() {
        $('#modalDetalhesContent').html(`
            <div class="row">
                <div class="col-md-6">
                    <h6>Informações da Solicitação</h6>
                    <p><strong>ID:</strong> ${id}</p>
                    <p><strong>Status:</strong> <span class="badge bg-warning">Pendente</span></p>
                    <p><strong>Data:</strong> ${new Date().toLocaleDateString('pt-BR')}</p>
                </div>
                <div class="col-md-6">
                    <h6>Informações do Solicitante</h6>
                    <p><strong>Nome:</strong> Usuário Teste</p>
                    <p><strong>E-mail:</strong> teste@email.com</p>
                </div>
            </div>
            <hr>
            <h6>Notas Adicionais</h6>
            <p>Exemplo de notas da solicitação...</p>
        `);
    }, 1000);
}

function alterarStatus(id, novoStatus) {
    const confirmacao = confirm(`Tem certeza que deseja alterar o status para "${novoStatus}"?`);

    if (confirmacao) {
        // Implementar AJAX para alterar status
        showAlerta('Status alterado com sucesso!', 'success');

        // Recarregar página para atualizar dados
        setTimeout(function() {
            location.reload();
        }, 1500);
    }
}

function showAlerta(mensagem, tipo = 'info') {
    const alertaHtml = `
        <div class="alert alert-${tipo} alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999;">
            ${mensagem}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;

    $('body').append(alertaHtml);

    setTimeout(function() {
        $('.alert').alert('close');
    }, 3000);
}
</script>
@endsection

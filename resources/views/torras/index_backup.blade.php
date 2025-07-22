@extends('master')

@section('title', 'Minhas Torras')
@section('breadcrumb-title', 'Lista de Torras')

@section('MainContent')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Minhas Torras -->
            <div class="card mb-4 mt-5">
                <div class="card-header border-0">
                    <h3 class="card-title">
                        <i class="fas fa-coffee"></i> Minhas Torras
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('torras.iniciar') }}" class="btn btn-primary btn-sm ajax-link">
                            <i class="fas fa-plus"></i> Nova Torra
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Mensagem de sucesso -->
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <!-- Caixa de pesquisa e filtros -->
                    <form method="GET" action="{{ route('torras.index') }}" class="mb-3">
                        <div class="row g-2">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Pesquisar por nome da torra..." value="{{ request('search') }}">
                                    <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Pesquisar</button>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <select name="filtro_avaliacao" class="form-select" onchange="this.form.submit()">
                                    <option value="">📊 Todas as torras</option>
                                    <option value="avaliadas" {{ request('filtro_avaliacao') === 'avaliadas' ? 'selected' : '' }}>
                                        ✅ Torras avaliadas
                                    </option>
                                    <option value="nao_avaliadas" {{ request('filtro_avaliacao') === 'nao_avaliadas' ? 'selected' : '' }}>
                                        ⏳ Torras não avaliadas
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('torras.index') }}" class="btn btn-outline-secondary w-100">
                                    <i class="fas fa-times"></i> Limpar
                                </a>
                            </div>
                        </div>

                        @if(request('search') || request('filtro_avaliacao'))
                            <div class="mt-2">
                                <small class="text-muted">
                                    <i class="fas fa-filter"></i>
                                    Mostrando {{ $torras->count() }} torra(s)
                                    @if(request('search'))
                                        para "<strong>{{ request('search') }}</strong>"
                                    @endif
                                    @if(request('filtro_avaliacao') === 'avaliadas')
                                        - apenas <strong>avaliadas</strong>
                                    @elseif(request('filtro_avaliacao') === 'nao_avaliadas')
                                        - apenas <strong>não avaliadas</strong>
                                    @endif
                                </small>
                            </div>
                        @endif
                    </form>

                    <div class="table-responsive p-0">
                        @if($torras->count())
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>Nome da Torra</th>
                                    <th>Variedade</th>
                                    <th>Densidade</th>
                                    <th>Fermentação</th>
                                    <th>Finalidade</th>
                                    <th>Status</th>
                                    <th>Criada em</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($torras as $torra)
                                <tr>
                                    <td>{{ $torra->nome }}</td>
                                    <td>{{ $torra->variedade }}</td>
                                    <td>{{ $torra->densidade }}</td>
                                    <td>{{ $torra->fermentacao }}</td>
                                    <td>{{ $torra->finalidade }}</td>
                                    <td>
                                        @if($torra->avaliada)
                                            <span class="badge bg-success">
                                                <i class="fas fa-check"></i> Avaliada
                                            </span>
                                            @if($torra->avaliador_nome)
                                                <br><small class="text-muted">
                                                    por {{ $torra->avaliador_nome }} {{ $torra->avaliador_sobrenome }}
                                                </small>
                                            @endif
                                        @else
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock"></i> Não Avaliada
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($torra->criado_em)->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <button
                                            class="btn btn-sm btn-outline-primary me-1 btn-ver-detalhes"
                                            data-id="{{ $torra->id }}"
                                            data-nome="{{ $torra->nome }}"
                                            data-variedade="{{ $torra->variedade }}"
                                            data-densidade="{{ $torra->densidade }}"
                                            data-fermentacao="{{ $torra->fermentacao }}"
                                            data-finalidade="{{ $torra->finalidade }}"
                                            data-avaliada="{{ $torra->avaliada }}"
                                            data-avaliador="{{ $torra->avaliador_nome ? $torra->avaliador_nome . ' ' . $torra->avaliador_sobrenome : '' }}"
                                            data-criada="{{ \Carbon\Carbon::parse($torra->criado_em)->format('d/m/Y H:i') }}"
                                            title="Ver detalhes"
                                        >
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button
                                            class="btn btn-sm btn-outline-danger btn-excluir-torra"
                                            data-id="{{ $torra->id }}"
                                            data-nome="{{ $torra->nome }}"
                                            title="Excluir torra"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                            <div class="alert alert-info text-center">
                                <h4>
                                    <i class="fas fa-info-circle"></i>
                                    @if(request('filtro_avaliacao') === 'avaliadas')
                                        Nenhuma torra avaliada encontrada
                                    @elseif(request('filtro_avaliacao') === 'nao_avaliadas')
                                        Nenhuma torra não avaliada encontrada
                                    @elseif(request('search'))
                                        Nenhuma torra encontrada para "{{ request('search') }}"
                                    @else
                                        Nenhuma torra encontrada
                                    @endif
                                </h4>
                                <p>
                                    @if(request('filtro_avaliacao') === 'avaliadas')
                                        Você ainda não possui torras avaliadas no sistema.
                                    @elseif(request('filtro_avaliacao') === 'nao_avaliadas')
                                        Todas as suas torras já foram avaliadas!
                                    @elseif(request('search'))
                                        Tente alterar os termos da pesquisa ou limpar os filtros.
                                    @else
                                        Você ainda não possui torras cadastradas no sistema.
                                    @endif
                                </p>
                                <hr>
                                <p class="mb-0">
                                    @if(request('search') || request('filtro_avaliacao'))
                                        <a href="{{ route('torras.index') }}" class="btn btn-outline-primary me-2">
                                            <i class="fas fa-times"></i> Limpar filtros
                                        </a>
                                    @endif
                                    @if(!request('filtro_avaliacao') || request('filtro_avaliacao') !== 'nao_avaliadas')
                                        <a href="{{ route('torras.iniciar') }}" class="btn btn-primary ajax-link">
                                            <i class="fas fa-fire"></i>
                                            @if(request('filtro_avaliacao') === 'avaliadas')
                                                Criar nova torra
                                            @else
                                                Criar primeira torra
                                            @endif
                                        </a>
                                    @endif
                                </p>
                            </div>
                        @endif
                    </div>

                    @if($torras->count() > 0)
                    <!-- Estatísticas -->
                    <div class="row mt-4">
                        <div class="col-md-3">
                            <div class="info-box bg-info">
                                <span class="info-box-icon"><i class="fas fa-coffee"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total de Torras</span>
                                    <span class="info-box-number">{{ $torras->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fas fa-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Avaliadas</span>
                                    <span class="info-box-number">{{ $torras->where('avaliada', true)->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-warning">
                                <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Pendentes</span>
                                    <span class="info-box-number">{{ $torras->where('avaliada', false)->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-primary">
                                <span class="info-box-icon"><i class="fas fa-chart-line"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Taxa Avaliação</span>
                                    <span class="info-box-number">
                                        {{ $torras->count() > 0 ? round(($torras->where('avaliada', true)->count() / $torras->count()) * 100) : 0 }}%
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Ver Detalhes -->
<div class="modal fade" id="modalDetalhesTorra" tabindex="-1" aria-labelledby="modalDetalhesTorraLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetalhesTorraLabel">
                    <i class="fas fa-coffee text-primary"></i> Detalhes da Torra
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalDetalhesTorraContent">
                <!-- Conteúdo será preenchido via JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Confirmar Exclusão -->
<div class="modal fade" id="modalExcluirTorra" tabindex="-1" aria-labelledby="modalExcluirTorraLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalExcluirTorraLabel">
                    <i class="fas fa-exclamation-triangle text-danger"></i> Confirmar Exclusão
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir a torra <strong id="nometorraExcluir"></strong>?</p>
                <p class="text-danger"><small>Esta ação não pode ser desfeita.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="formExcluirTorra" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Excluir
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Gerenciar Solicitações -->
<div class="modal fade" id="modalGerenciarSolicitacoes" tabindex="-1" aria-labelledby="modalGerenciarSolicitacoesLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalGerenciarSolicitacoesLabel">
                    <i class="fas fa-tasks text-primary"></i> Gerenciar Solicitações
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalGerenciarSolicitacoesContent">
                <!-- Conteúdo será preenchido via JavaScript -->
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
$(document).ready(function() {
    // Ver detalhes da torra
    $('.btn-ver-detalhes').on('click', function(e) {
        e.preventDefault();

        const data = $(this).data();
        const content = `
            <div class="row">
                <div class="col-md-6">
                    <h6><i class="fas fa-tag text-primary"></i> Informações Básicas</h6>
                    <table class="table table-borderless">
                        <tr><td><strong>Nome:</strong></td><td>${data.nome}</td></tr>
                        <tr><td><strong>Variedade:</strong></td><td>${data.variedade}</td></tr>
                        <tr><td><strong>Densidade:</strong></td><td>${data.densidade}</td></tr>
                        <tr><td><strong>Fermentação:</strong></td><td>${data.fermentacao}</td></tr>
                        <tr><td><strong>Finalidade:</strong></td><td>${data.finalidade}</td></tr>
                        <tr><td><strong>Criada em:</strong></td><td>${data.criada}</td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6><i class="fas fa-clipboard-check text-success"></i> Status de Avaliação</h6>
                    <div class="card">
                        <div class="card-body">
                            ${data.avaliada === '1' ?
                                `<div class="text-success">
                                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                                    <h5>Torra Avaliada</h5>
                                    ${data.avaliador ? `<p>Avaliador: <strong>${data.avaliador}</strong></p>` : ''}
                                </div>` :
                                `<div class="text-warning">
                                    <i class="fas fa-clock fa-2x mb-2"></i>
                                    <h5>Aguardando Avaliação</h5>
                                    <p>Esta torra ainda não foi avaliada.</p>
                                </div>`
                            }
                        </div>
                    </div>
                </div>
            </div>
        `;

        $('#modalDetalhesTorraContent').html(content);
        $('#modalDetalhesTorra').modal('show');
    });

    // Excluir torra
    $('.btn-excluir-torra').on('click', function(e) {
        e.preventDefault();
        const torraId = $(this).data('id');
        const torraNome = $(this).data('nome');
        window.currentTorraId = torraId; // Armazenar para usar na função de gerenciar

        $('#nometorraExcluir').text(torraNome);
        $('#formExcluirTorra').attr('action', '/torras/' + torraId);
        $('#modalExcluirTorra').modal('show');
    });

    // Confirmar exclusão
    $('#formExcluirTorra').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                $('#modalExcluirTorra').modal('hide');
                showAlerta('Torra excluída com sucesso!', 'success');
                setTimeout(function() {
                    location.reload();
                }, 1500);
            },
            error: function(xhr) {
                $('#modalExcluirTorra').modal('hide');
                let errorMessage = 'Erro ao excluir torra. Tente novamente.';

                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMessage = xhr.responseJSON.error;

                    // Se o erro for sobre solicitações pendentes, mostrar opção de gerenciar
                    if (errorMessage.includes('solicitação(ões) de análise ativa(s)')) {
                        errorMessage += '<br><br><button class="btn btn-sm btn-info mt-2" onclick="gerenciarSolicitacoes(' + window.currentTorraId + ')">Gerenciar Solicitações</button>';
                    }
                }

                console.error('Erro de exclusão:', xhr.responseText);
                showAlerta(errorMessage, 'danger', 8000); // 8 segundos para dar tempo de ler
            }
        });
    });
});

function showAlerta(mensagem, tipo = 'info', timeout = 3000) {
    const alertaHtml = `
        <div class="alert alert-${tipo} alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999;">
            ${mensagem}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;

    $('body').append(alertaHtml);

    setTimeout(function() {
        $('.alert').alert('close');
    }, timeout);
}

function gerenciarSolicitacoes(torraId) {
    $('#modalGerenciarSolicitacoesContent').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Carregando solicitações...</div>');
    $('#modalGerenciarSolicitacoes').modal('show');

    $.ajax({
        url: `/torras/${torraId}/solicitacoes`,
        type: 'GET',
        success: function(response) {
            let content = `
                <div class="row mb-3">
                    <div class="col-12">
                        <h6><i class="fas fa-flask text-primary"></i> Torra: ${response.torra.nome}</h6>
                        <p class="text-muted mb-3">Criada em: ${response.torra.created_at_formatted}</p>
                    </div>
                </div>
            `;

            if (response.solicitacoes && response.solicitacoes.length > 0) {
                content += '<div class="row">';
                response.solicitacoes.forEach(function(solicitacao) {
                    const statusClass = solicitacao.status === 'pendente' ? 'warning' :
                                       solicitacao.status === 'em_andamento' ? 'info' : 'success';
                    const statusText = solicitacao.status === 'pendente' ? 'Pendente' :
                                      solicitacao.status === 'em_andamento' ? 'Em Andamento' : 'Concluída';

                    content += `
                        <div class="col-12 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="card-title mb-1">
                                                <i class="fas fa-vial text-primary"></i>
                                                Solicitação #${solicitacao.id}
                                            </h6>
                                            <p class="card-text mb-1">
                                                <small class="text-muted">
                                                    Solicitada em: ${solicitacao.created_at_formatted}
                                                </small>
                                            </p>
                                            ${solicitacao.analista ? `<p class="card-text mb-1"><small class="text-muted">Analista: ${solicitacao.analista.name}</small></p>` : ''}
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-${statusClass}">${statusText}</span>
                                            ${solicitacao.status === 'pendente' ?
                                                `<button class="btn btn-sm btn-outline-danger mt-1 d-block" onclick="cancelarSolicitacao(${torraId}, ${solicitacao.id})">
                                                    <i class="fas fa-times"></i> Cancelar
                                                </button>` : ''}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                content += '</div>';
            } else {
                content += '<div class="alert alert-info">Nenhuma solicitação encontrada para esta torra.</div>';
            }

            $('#modalGerenciarSolicitacoesContent').html(content);
        },
        error: function(xhr) {
            console.error('Erro ao carregar solicitações:', xhr.responseText);
            $('#modalGerenciarSolicitacoesContent').html('<div class="alert alert-danger">Erro ao carregar solicitações. Tente novamente.</div>');
        }
    });
}

function cancelarSolicitacao(torraId, solicitacaoId) {
    if (!confirm('Tem certeza que deseja cancelar esta solicitação?')) {
        return;
    }

    $.ajax({
        url: `/torras/${torraId}/solicitacoes/${solicitacaoId}/cancelar`,
        type: 'PUT',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            showAlerta('Solicitação cancelada com sucesso!', 'success');
            gerenciarSolicitacoes(torraId); // Recarregar a lista
        },
        error: function(xhr) {
            let errorMessage = 'Erro ao cancelar solicitação.';
            if (xhr.responseJSON && xhr.responseJSON.error) {
                errorMessage = xhr.responseJSON.error;
            }
            showAlerta(errorMessage, 'danger');
            console.error('Erro ao cancelar solicitação:', xhr.responseText);
        }
    });
}
</script>
@endsection

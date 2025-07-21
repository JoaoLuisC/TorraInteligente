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

                    <!-- Caixa de pesquisa -->
                    <form method="GET" action="{{ route('torras.index') }}" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Pesquisar por nome da torra..." value="{{ request('search') }}">
                            <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Pesquisar</button>
                        </div>
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
                                    <th>Status</th>
                                    <th>Criada em</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($torras as $torra)
                                <tr>
                                    <td>
                                        <img
                                            src="{{ asset('images/icons/torrador_de_cafe.png') }}"
                                            alt="{{ $torra->nome }}"
                                            class="rounded-circle img-size-32 me-2"
                                        />
                                        {{ $torra->nome }}
                                    </td>
                                    <td>{{ $torra->variedade }}</td>
                                    <td>{{ $torra->densidade ? $torra->densidade . ' g/cm³' : 'Não informado' }}</td>
                                    <td>{{ $torra->fermentacao ?? 'Não informado' }}</td>
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
                                            <i class="fas fa-eye"></i> Detalhes
                                        </button>

                                        @if(!$torra->avaliada)
                                            <a href="{{ route('prova.solicitar') }}?torra_id={{ $torra->id }}"
                                               class="btn btn-sm btn-outline-success me-1 ajax-link"
                                               title="Solicitar avaliação">
                                                <i class="fas fa-flask"></i> Solicitar Prova
                                            </a>
                                        @else
                                            <button class="btn btn-sm btn-outline-info me-1 btn-ver-avaliacao"
                                                    data-id="{{ $torra->id }}"
                                                    title="Ver avaliação">
                                                <i class="fas fa-star"></i> Ver Avaliação
                                            </button>
                                        @endif

                                        <button
                                            class="btn btn-sm btn-outline-danger btn-excluir-torra"
                                            data-id="{{ $torra->id }}"
                                            data-nome="{{ $torra->nome }}"
                                            title="Excluir torra"
                                        >
                                            <i class="fas fa-trash"></i> Excluir
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                            <div class="alert alert-info text-center m-0">
                                <h4 class="alert-heading">
                                    <i class="fas fa-info-circle"></i> Nenhuma torra encontrada
                                </h4>
                                <p>Você ainda não possui torras cadastradas no sistema.</p>
                                <hr>
                                <p class="mb-0">
                                    <a href="{{ route('torras.iniciar') }}" class="btn btn-primary ajax-link">
                                        <i class="fas fa-fire"></i> Criar primeira torra
                                    </a>
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

<!-- Modal para Detalhes da Torra -->
<div class="modal fade" id="modalDetalhesTorra" tabindex="-1" aria-labelledby="modalDetalhesTorraLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetalhesTorraLabel">
                    <i class="fas fa-coffee"></i> Detalhes da Torra
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
                    <h6><i class="fas fa-info-circle"></i> Informações Básicas</h6>
                    <p><strong>Nome:</strong> ${data.nome}</p>
                    <p><strong>Variedade:</strong> ${data.variedade}</p>
                    <p><strong>Densidade:</strong> ${data.densidade ? data.densidade + ' g/cm³' : 'Não informado'}</p>
                    <p><strong>Fermentação:</strong> ${data.fermentacao || 'Não informado'}</p>
                    <p><strong>Finalidade:</strong> ${data.finalidade || 'Não informado'}</p>
                </div>
                <div class="col-md-6">
                    <h6><i class="fas fa-chart-line"></i> Status e Avaliação</h6>
                    <p><strong>Status:</strong> ${data.avaliada ? '<span class="badge bg-success">Avaliada</span>' : '<span class="badge bg-warning">Não Avaliada</span>'}</p>
                    ${data.avaliador ? '<p><strong>Avaliado por:</strong> ' + data.avaliador + '</p>' : ''}
                    <p><strong>Criada em:</strong> ${data.criada}</p>
                </div>
            </div>
            <hr>
            <h6><i class="fas fa-flask"></i> Informações Técnicas</h6>
            <p>Aqui seriam exibidos gráficos e dados técnicos da torra quando disponíveis...</p>
        `;

        $('#modalDetalhesTorraContent').html(content);
        $('#modalDetalhesTorra').modal('show');
    });

    // Ver avaliação
    $('.btn-ver-avaliacao').on('click', function(e) {
        e.preventDefault();
        const torraId = $(this).data('id');
        showAlerta('Funcionalidade de visualização de avaliação será implementada em breve.', 'info');
    });

    // Excluir torra
    $('.btn-excluir-torra').on('click', function(e) {
        e.preventDefault();
        const torraId = $(this).data('id');
        const torraNome = $(this).data('nome');

        $('#nomeeTorraExcluir').text(torraNome);
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
                showAlerta('Erro ao excluir torra. Tente novamente.', 'danger');
            }
        });
    });
});

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

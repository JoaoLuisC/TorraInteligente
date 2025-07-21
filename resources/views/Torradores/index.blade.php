@extends('master')

@section('title', 'Meus Torradores')
@section('breadcrumb-title', 'Lista de Torradores')

@section('MainContent')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Avaliações de Torradores -->
            <div class="card mb-4 mt-5">
                <div class="card-header border-0">
                    <h3 class="card-title">Meus Torradores</h3>
                </div>
                <div class="card-body">
                    <!-- Mensagem de sucesso -->
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <!-- Caixa de pesquisa -->
                    <form method="GET" action="{{ route('torradores.index') }}" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Pesquisar por nome do torrador..." value="{{ request('search') }}">
                            <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Pesquisar</button>
                        </div>
                    </form>
                    <div class="table-responsive p-0">
                        @if($torradores->count())
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>Nome do Torrador</th>
                                    <th>Código de Conexão</th>
                                    <th>Criado em</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($torradores as $torrador)
                                <tr>
                                    <td>
                                        <img
                                            src="{{ asset('images/icons/torrador.png') }}"
                                            alt="{{ $torrador->nome }}"
                                            class="rounded-circle img-size-32 me-2"
                                        />
                                        {{ $torrador->nome }}
                                    </td>
                                    <td>{{ $torrador->codigo_conexao }}</td>
                                    <td>{{ \Carbon\Carbon::parse($torrador->criado_em)->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <button
                                            class="btn btn-sm btn-primary me-1 btn-editar-torrador"
                                            data-id="{{ $torrador->id }}"
                                            data-nome="{{ $torrador->nome }}"
                                            data-codigo="{{ $torrador->codigo_conexao }}"
                                        >
                                            <i class="bi bi-pencil"></i> Editar
                                        </button>
                                        <form action="{{ route('torradores.destroy', $torrador->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Tem certeza que deseja excluir este torrador?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i> Excluir
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                            <div class="alert alert-info text-center m-0">
                                Nenhum torrador cadastrado ainda.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Edição -->
<div class="modal fade" id="modalEditarTorrador" tabindex="-1" aria-labelledby="modalEditarTorradorLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formEditarTorrador">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalEditarTorradorLabel">Editar Torrador</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="edit_id" name="id">
          <div class="mb-3">
            <label for="edit_nome" class="form-label">Nome do Torrador</label>
            <input type="text" class="form-control" id="edit_nome" name="nome" required>
          </div>
          <div class="mb-3">
            <label for="edit_codigo_conexao" class="form-label">Código de Conexão</label>
            <input type="text" class="form-control" id="edit_codigo_conexao" name="codigo_conexao" required>
          </div>
          <div id="edit_msg" class="alert d-none"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Abrir modal e preencher campos
    $('.btn-editar-torrador').on('click', function(e) {
        e.preventDefault();
        $('#edit_id').val($(this).data('id'));
        $('#edit_nome').val($(this).data('nome'));
        $('#edit_codigo_conexao').val($(this).data('codigo'));
        $('#edit_msg').addClass('d-none').text('');
        $('#modalEditarTorrador').modal('show');
    });

    // Submeter edição via AJAX
    $('#formEditarTorrador').on('submit', function(e) {
        e.preventDefault();
        var id = $('#edit_id').val();
        var url = '/torradores/' + id + '/editar';
        var data = {
            _token: '{{ csrf_token() }}',
            _method: 'PUT',
            nome: $('#edit_nome').val(),
            codigo_conexao: $('#edit_codigo_conexao').val()
        };
        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            success: function(response) {
                location.reload(); // Recarrega a página para atualizar a tabela
            },
            error: function(xhr) {
                $('#edit_msg').removeClass('d-none alert-success').addClass('alert-danger').text('Erro ao atualizar torrador.');
            }
        });
    });
});
</script>
@endsection

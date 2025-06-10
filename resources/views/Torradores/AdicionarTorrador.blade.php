@extends('master')

@section('title', 'Adicionar Torradores')

@section('MainContent')
<div class="container d-flex justify-content-center">
    <div class="card card-warning card-outline mb-4" style="min-width:350px; max-width:500px; width:100%;">
        <div class="card-header">
            <div class="card-title">Adicionar Torrador</div>
        </div>
        <form id="formAdicionarTorrador" method="POST" action="{{ route('torradores.store') }}">
            @csrf
            <div class="card-body">
                <div class="row mb-3">
                    <label for="nome" class="col-sm-4 col-form-label">Nome do Torrador</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="nome" name="nome" required />
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="codigo_conexao" class="col-sm-4 col-form-label">Codigo de conexão</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="codigo_conexao" name="codigo_conexao" required />
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-warning" id="btnCadastrar">Cadastrar</button>
                <button href="{{ route('torradores.index') }}" type="button" class="btn float-end">Cancelar</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    $('#formAdicionarTorrador').on('submit', function(e) {
        e.preventDefault();
        $('#btnCadastrar').prop('disabled', true).text('Salvando...');
        $('#msg').removeClass('alert-success alert-danger').addClass('d-none').text('');

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#msg').removeClass('d-none alert-danger').addClass('alert-success').text('Torrador cadastrado com sucesso!');
                $('#formAdicionarTorrador')[0].reset();
            },
            error: function(xhr) {
                $('#msg').removeClass('d-none alert-success').addClass('alert-danger').text('Erro ao cadastrar. Verifique os campos.');
            },
            complete: function() {
                $('#btnCadastrar').prop('disabled', false).text('Cadastrar');
            }
        });
    });
});
</script>
@endsection

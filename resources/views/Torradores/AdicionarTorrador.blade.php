@extends('master')

@section('title', 'Adicionar Torrador')
@section('breadcrumb-title', 'Adicionar Novo Torrador')

@section('MainContent')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Adicionar Torrador -->
            <div class="card mb-4 mt-5">
                <div class="card-header border-0">
                    <h3 class="card-title">
                        <i class="fas fa-plus-circle me-2"></i>
                        Adicionar Novo Torrador
                    </h3>
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

                    <form id="formAdicionarTorrador" method="POST" action="{{ route('torradores.store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="nome" class="form-label">Nome do Torrador *</label>
                                    <input type="text"
                                           class="form-control @error('nome') is-invalid @enderror"
                                           id="nome"
                                           name="nome"
                                           value="{{ old('nome') }}"
                                           placeholder="Digite o nome do torrador"
                                           required />
                                    @error('nome')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="codigo_conexao" class="form-label">Código de Conexão *</label>
                                    <input type="text"
                                           class="form-control @error('codigo_conexao') is-invalid @enderror"
                                           id="codigo_conexao"
                                           name="codigo_conexao"
                                           value="{{ old('codigo_conexao') }}"
                                           placeholder="Digite o código de conexão"
                                           required />
                                    @error('codigo_conexao')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-white border-0 px-0">
                            <button type="submit" class="btn btn-primary btn-lg" id="btnCadastrar">
                                <i class="fas fa-save me-2"></i>
                                Cadastrar Torrador
                            </button>
                            <a href="{{ route('torradores.index') }}" class="btn btn-outline-secondary btn-lg ajax-link">
                                <i class="fas fa-arrow-left me-2"></i>
                                Voltar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Card informativo -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-info">
                        <div class="card-body">
                            <h5 class="card-title text-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Informações Importantes
                            </h5>
                            <ul class="mb-0">
                                <li>O <strong>Nome do Torrador</strong> deve ser único e descritivo</li>
                                <li>O <strong>Código de Conexão</strong> é usado para identificar o dispositivo na rede</li>
                                <li>Certifique-se de que o torrador esteja conectado antes de usar</li>
                                <li>Após o cadastro, você poderá gerenciar suas torras com este equipamento</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formAdicionarTorrador');
    const submitBtn = document.getElementById('btnCadastrar');

    if (form && submitBtn) {
        form.addEventListener('submit', function() {
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Cadastrando...';
            submitBtn.disabled = true;
        });
    }
});
</script>
@endsection

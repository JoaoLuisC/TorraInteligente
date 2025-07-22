{{-- resources/views/torras/solicitar-avaliacao.blade.php --}}
@extends('master')

@section('title', 'Solicitar Avaliação')
@section('breadcrumb-title', 'Solicitar Avaliação')

@section('MainContent')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-4 mt-5">
                <div class="card-header border-0">
                    <h3 class="card-title">
                        <i class="fas fa-flask text-success"></i> Solicitar Avaliação de Torra
                    </h3>
                </div>
                <div class="card-body">
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

                    @if($torras->count() > 0)
                        <form action="{{ route('torras.solicitar-avaliacao.processar') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="torra_id" class="form-label">
                                            <i class="fas fa-coffee"></i> Selecione a Torra
                                        </label>
                                        <select name="torra_id" id="torra_id" class="form-select" required>
                                            <option value="">Escolha uma torra...</option>
                                            @foreach($torras as $torra)
                                                <option value="{{ $torra->id }}"
                                                    {{ $torraId == $torra->id ? 'selected' : '' }}>
                                                    {{ $torra->nome }} - {{ $torra->variedade }} ({{ $torra->finalidade }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="analista_id" class="form-label">
                                            <i class="fas fa-user-tie"></i> Analista
                                        </label>
                                        <select name="analista_id" id="analista_id" class="form-select" required>
                                            <option value="">Escolha um analista...</option>
                                            @foreach($analistas as $analista)
                                                <option value="{{ $analista->id }}">
                                                    {{ $analista->nome }} {{ $analista->sobrenome }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="notas" class="form-label">
                                    <i class="fas fa-sticky-note"></i> Notas Adicionais (Opcional)
                                </label>
                                <textarea name="notas" id="notas" class="form-control" rows="4"
                                    placeholder="Adicione informações relevantes sobre a torra, objetivos da análise, etc."></textarea>
                                <div class="form-text">Máximo de 1000 caracteres</div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('torras.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left"></i> Voltar
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-paper-plane"></i> Enviar Solicitação
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-info text-center">
                            <h4><i class="fas fa-info-circle"></i> Nenhuma torra disponível</h4>
                            <p class="mb-3">Você não possui torras que podem ser enviadas para avaliação no momento.</p>
                            <p class="mb-0">
                                <a href="{{ route('torras.iniciar') }}" class="btn btn-primary">
                                    <i class="fas fa-fire"></i> Criar Nova Torra
                                </a>
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if($torras->count() > 0)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const torraSelect = document.getElementById('torra_id');
    const analistaSelect = document.getElementById('analista_id');

    // Auto-focus no select de torra se não houver torra pré-selecionada
    if (!torraSelect.value) {
        torraSelect.focus();
    }

    // Validação do formulário
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        if (!torraSelect.value || !analistaSelect.value) {
            e.preventDefault();
            alert('Por favor, selecione uma torra e um analista antes de enviar a solicitação.');
        }
    });
});
</script>
@endif
@endsection

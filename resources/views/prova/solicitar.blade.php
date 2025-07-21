@extends('master')

@section('title', 'Solicitar Prova - Michelangelo')
@section('breadcrumb-title', 'Solicitar Prova')

@section('MainContent')
@if(Auth::check())
<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="bi bi-file-earmark-text me-2"></i>
                            Solicitar Prova de Qualidade
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

                        @if($torrasNaoAvaliadas->isEmpty())
                            <div class="alert alert-info" role="alert">
                                <h4 class="alert-heading">
                                    <i class="fas fa-info-circle"></i> Nenhuma torra disponível
                                </h4>
                                <p>Você não possui torras não avaliadas no momento.</p>
                                <hr>
                                <p class="mb-0">
                                    <a href="{{ route('torras.iniciar') }}" class="btn btn-primary ajax-link">
                                        <i class="fas fa-fire"></i> Criar nova torra
                                    </a>
                                    <a href="{{ route('torras.index') }}" class="btn btn-outline-secondary ajax-link">
                                        <i class="fas fa-list"></i> Ver minhas torras
                                    </a>
                                </p>
                            </div>
                        @else

                        <form id="formSolicitarProva" action="{{ route('prova.solicitar.post') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="analista_id" class="form-label">Analista Responsável *</label>
                                        <select class="form-control @error('analista_id') is-invalid @enderror"
                                                id="analista_id" name="analista_id" required>
                                            <option value="">Selecione o analista</option>
                                            @foreach($analistas as $analista)
                                                <option value="{{ $analista->id }}" {{ old('analista_id') == $analista->id ? 'selected' : '' }}>
                                                    {{ $analista->nome }} {{ $analista->sobrenome }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('analista_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="torra_id" class="form-label">Torra para Avaliação *</label>
                                        <select class="form-control @error('torra_id') is-invalid @enderror"
                                                id="torra_id" name="torra_id" required>
                                            <option value="">Selecione a torra</option>
                                            @if($torrasNaoAvaliadas->isEmpty())
                                                <option value="" disabled>Nenhuma torra disponível para avaliação</option>
                                            @else
                                                @foreach($torrasNaoAvaliadas as $torra)
                                                    <option value="{{ $torra->id }}" {{ old('torra_id') == $torra->id ? 'selected' : '' }}>
                                                        {{ $torra->nome }} - {{ $torra->variedade }} ({{ $torra->fermentacao }}, {{ $torra->finalidade }})
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('torra_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <label for="notas" class="form-label">Notas Adicionais</label>
                                        <textarea class="form-control @error('notas') is-invalid @enderror"
                                                  id="notas" name="notas" rows="4"
                                                  placeholder="Adicione informações extras, observações ou instruções especiais para o analista..."
                                                  maxlength="500">{{ old('notas') }}</textarea>
                                        <small class="form-text text-muted">Máximo 500 caracteres (opcional)</small>
                                        @error('notas')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <h6><i class="bi bi-info-circle me-2"></i>Informações Importantes:</h6>
                                        <ul class="mb-0">
                                            <li>Selecione um analista disponível para realizar a avaliação</li>
                                            <li>Apenas torras ainda não avaliadas aparecem na lista</li>
                                            <li>A amostra deve ter no mínimo 300g de café</li>
                                            <li>O prazo para análise é de 3 a 5 dias úteis</li>
                                            <li>O analista selecionado será notificado da solicitação</li>
                                            <li>Entre em contato conosco para agendar a entrega da amostra</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                            <i class="bi bi-arrow-left me-1"></i> Voltar ao Dashboard
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-send me-1"></i> Enviar Solicitação
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Formulário de solicitar prova - AJAX somente se há sidebar
    const form = document.getElementById('formSolicitarProva');
    if (form) {
        form.onsubmit = function(e) {
            console.log('Submit do formulário de prova');

            // Verificar se há sidebar (indica que estamos no sistema)
            const hasSidebar = document.querySelector('.sidebar') ||
                             document.querySelector('.main-sidebar') ||
                             document.querySelector('[class*="sidebar"]');

            if (hasSidebar) {
                console.log('Enviando via AJAX');
                e.preventDefault();

                const btn = this.querySelector('button[type="submit"]');
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enviando...';
                }

                const formData = new FormData(this);

                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Resposta:', data);

                    if (btn) {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="bi bi-send me-1"></i> Enviar Solicitação';
                    }

                    if (data.success) {
                        alert(data.message);
                        // Limpar formulário
                        this.reset();
                    } else {
                        let errorMsg = data.message || 'Erro desconhecido';
                        if (data.errors) {
                            errorMsg += '\n\n' + Object.values(data.errors).flat().join('\n');
                        }
                        alert('Erro: ' + errorMsg);
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    if (btn) {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="bi bi-send me-1"></i> Enviar Solicitação';
                    }
                    alert('Erro ao enviar solicitação');
                });
            } else {
                console.log('Enviando tradicionalmente');
                // Deixa o envio normal acontecer
            }
        };
    }
});
</script>
@else
    <script>
        window.location.href = '{{ route('login') }}';
    </script>
@endif
@endsection

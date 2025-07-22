@extends('master')

@section('title', 'Configurar Torra')
@section('breadcrumb-title', 'Configurar Nova Torra')

@section('MainContent')
<div class="container-fluid">
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

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-fire me-2"></i>
                        Configurar Nova Torra
                    </h4>
                </div>
                <div class="card-body">
                    <form id="nova-torra-form" action="{{ route('torras.store') }}" method="POST" autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="nome" class="form-label">Nome da Torra *</label>
                                    <input type="text" class="form-control @error('nome') is-invalid @enderror"
                                           id="nome" name="nome" value="{{ old('nome') }}" required>
                                    @error('nome')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="densidade" class="form-label">Densidade (g/cm³) *</label>
                                    <input type="number" step="0.01" min="0"
                                           class="form-control @error('densidade') is-invalid @enderror"
                                           id="densidade" name="densidade" value="{{ old('densidade') }}" required>
                                    @error('densidade')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="variedade" class="form-label">Variedade *</label>
                                    <select class="form-control @error('variedade') is-invalid @enderror"
                                            id="variedade" name="variedade" required>
                                        <option value="">Selecione a variedade</option>
                                        <option value="Arábico" {{ old('variedade') == 'Arábico' ? 'selected' : '' }}>Arábico</option>
                                        <option value="Bourbon" {{ old('variedade') == 'Bourbon' ? 'selected' : '' }}>Bourbon</option>
                                    </select>
                                    @error('variedade')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="fermentacao" class="form-label">Fermentação *</label>
                                    <select class="form-control @error('fermentacao') is-invalid @enderror"
                                            id="fermentacao" name="fermentacao" required>
                                        <option value="">Selecione a fermentação</option>
                                        <option value="Natural" {{ old('fermentacao') == 'Natural' ? 'selected' : '' }}>Natural</option>
                                        <option value="Fermentado" {{ old('fermentacao') == 'Fermentado' ? 'selected' : '' }}>Fermentado</option>
                                        <option value="CD" {{ old('fermentacao') == 'CD' ? 'selected' : '' }}>CD</option>
                                    </select>
                                    @error('fermentacao')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="finalidade" class="form-label">Finalidade *</label>
                                    <select class="form-control @error('finalidade') is-invalid @enderror"
                                            id="finalidade" name="finalidade" required>
                                        <option value="">Selecione a finalidade</option>
                                        <option value="Espresso" {{ old('finalidade') == 'Espresso' ? 'selected' : '' }}>Espresso</option>
                                        <option value="Filtro" {{ old('finalidade') == 'Filtro' ? 'selected' : '' }}>Filtro</option>
                                        <option value="Amostra" {{ old('finalidade') == 'Amostra' ? 'selected' : '' }}>Amostra</option>
                                    </select>
                                    @error('finalidade')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-white border-0 px-0">
                            <button type="submit" class="btn btn-primary btn-lg" id="btn-configurar-torra">
                                <i class="fas fa-save me-2"></i>
                                Configurar Torra
                            </button>
                            <a href="{{ route('torras.index') }}" class="btn btn-outline-secondary btn-lg ajax-link">
                                <i class="fas fa-arrow-left me-2"></i>
                                Voltar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('nova-torra-form');
    const submitBtn = document.getElementById('btn-configurar-torra');

    if (form && submitBtn) {
        form.addEventListener('submit', function() {
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Configurando...';
            submitBtn.disabled = true;
        });
    }
});
</script>
@endsection

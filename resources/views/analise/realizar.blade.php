@extends('master')

@section('title', 'Realizar Análise Sensorial')
@section('breadcrumb-title', 'Análise Sensorial')

@section('MainContent')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Informações da Torra -->
            <div class="card mb-4 mt-5">
                <div class="card-header border-0">
                    <h3 class="card-title">
                        <i class="fas fa-microscope"></i> Análise Sensorial
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5><i class="fas fa-coffee"></i> {{ $solicitacao->torra_nome }}</h5>
                            <p class="text-muted mb-1">
                                <strong>Produtor:</strong> {{ $solicitacao->produtor_nome }} {{ $solicitacao->produtor_sobrenome }}
                            </p>
                            <p class="text-muted mb-1">
                                <strong>Variedade:</strong> {{ $solicitacao->torra_variedade }}
                            </p>
                            <p class="text-muted mb-1">
                                <strong>Fermentação:</strong> {{ $solicitacao->torra_fermentacao }}
                            </p>
                            <p class="text-muted mb-1">
                                <strong>Finalidade:</strong> {{ $solicitacao->torra_finalidade }}
                            </p>
                            <p class="text-muted">
                                <strong>Densidade:</strong> {{ number_format($solicitacao->torra_densidade, 2) }} g/cm³
                            </p>
                        </div>
                        <div class="col-md-6">
                            @if($solicitacao->notas)
                                <h6>Observações do Produtor:</h6>
                                <p class="text-muted">{{ $solicitacao->notas }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulário de Análise -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Avaliação Sensorial</h4>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($analiseExistente)
                        <div class="alert alert-warning" role="alert">
                            <i class="fas fa-info-circle"></i> Esta torra já possui uma análise sensorial realizada.
                            Você pode atualizar os valores abaixo.
                        </div>
                    @endif

                    <form method="POST" action="{{ route('analise.salvar', $solicitacao->id) }}">
                        @csrf

                        <!-- Grid organizado para melhor UX -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="aroma_po" class="form-label">Aroma do Pó (0-10)</label>
                                    <input type="number" id="aroma_po" name="aroma_po"
                                           class="form-control @error('aroma_po') is-invalid @enderror"
                                           step="0.1" min="0" max="10"
                                           value="{{ old('aroma_po', $analiseExistente->aroma_po ?? '') }}" required>
                                    @error('aroma_po')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="fragrancia_cafe" class="form-label">Fragrância do Café (0-10)</label>
                                    <input type="number" id="fragrancia_cafe" name="fragrancia_cafe"
                                           class="form-control @error('fragrancia_cafe') is-invalid @enderror"
                                           step="0.1" min="0" max="10"
                                           value="{{ old('fragrancia_cafe', $analiseExistente->fragrancia_cafe ?? '') }}" required>
                                    @error('fragrancia_cafe')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="sabor" class="form-label">Sabor (0-10)</label>
                                    <input type="number" id="sabor" name="sabor"
                                           class="form-control @error('sabor') is-invalid @enderror"
                                           step="0.1" min="0" max="10"
                                           value="{{ old('sabor', $analiseExistente->sabor ?? '') }}" required>
                                    @error('sabor')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="acidez" class="form-label">Acidez (0-10)</label>
                                    <input type="number" id="acidez" name="acidez"
                                           class="form-control @error('acidez') is-invalid @enderror"
                                           step="0.1" min="0" max="10"
                                           value="{{ old('acidez', $analiseExistente->acidez ?? '') }}" required>
                                    @error('acidez')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="corpo" class="form-label">Corpo (0-10)</label>
                                    <input type="number" id="corpo" name="corpo"
                                           class="form-control @error('corpo') is-invalid @enderror"
                                           step="0.1" min="0" max="10"
                                           value="{{ old('corpo', $analiseExistente->corpo ?? '') }}" required>
                                    @error('corpo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="retro_gosto" class="form-label">Retrogosto (0-10)</label>
                                    <input type="number" id="retro_gosto" name="retro_gosto"
                                           class="form-control @error('retro_gosto') is-invalid @enderror"
                                           step="0.1" min="0" max="10"
                                           value="{{ old('retro_gosto', $analiseExistente->retro_gosto ?? '') }}" required>
                                    @error('retro_gosto')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="equilibrio" class="form-label">Equilíbrio (0-10)</label>
                                    <input type="number" id="equilibrio" name="equilibrio"
                                           class="form-control @error('equilibrio') is-invalid @enderror"
                                           step="0.1" min="0" max="10"
                                           value="{{ old('equilibrio', $analiseExistente->equilibrio ?? '') }}" required>
                                    @error('equilibrio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="docura" class="form-label">Doçura (0-10)</label>
                                    <input type="number" id="docura" name="docura"
                                           class="form-control @error('docura') is-invalid @enderror"
                                           step="0.1" min="0" max="10"
                                           value="{{ old('docura', $analiseExistente->docura ?? '') }}" required>
                                    @error('docura')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="uniformidade" class="form-label">Uniformidade (0-10)</label>
                                    <input type="number" id="uniformidade" name="uniformidade"
                                           class="form-control @error('uniformidade') is-invalid @enderror"
                                           step="0.1" min="0" max="10"
                                           value="{{ old('uniformidade', $analiseExistente->uniformidade ?? '') }}" required>
                                    @error('uniformidade')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="defeitos" class="form-label">Defeitos (0-10)</label>
                                    <input type="number" id="defeitos" name="defeitos"
                                           class="form-control @error('defeitos') is-invalid @enderror"
                                           step="0.1" min="0" max="10"
                                           value="{{ old('defeitos', $analiseExistente->defeitos ?? '') }}" required>
                                    @error('defeitos')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="balanceamento" class="form-label">Balanceamento (0-10)</label>
                                    <input type="number" id="balanceamento" name="balanceamento"
                                           class="form-control @error('balanceamento') is-invalid @enderror"
                                           step="0.1" min="0" max="10"
                                           value="{{ old('balanceamento', $analiseExistente->balanceamento ?? '') }}" required>
                                    @error('balanceamento')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="nota_final" class="form-label">Nota Final (calculada automaticamente)</label>
                                    <input type="text" id="nota_final" class="form-control" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-white border-0 px-0">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-save me-2"></i>
                                {{ $analiseExistente ? 'Atualizar' : 'Salvar' }} Análise
                            </button>
                            <a href="{{ route('analise.pendentes') }}" class="btn btn-outline-secondary btn-lg">
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
    const inputs = document.querySelectorAll('input[type="number"]');
    const notaFinalInput = document.getElementById('nota_final');

    function calcularNotaTotal() {
        const aromaPo = parseFloat(document.getElementById('aroma_po').value) || 0;
        const fragranciaCafe = parseFloat(document.getElementById('fragrancia_cafe').value) || 0;
        const sabor = parseFloat(document.getElementById('sabor').value) || 0;
        const acidez = parseFloat(document.getElementById('acidez').value) || 0;
        const corpo = parseFloat(document.getElementById('corpo').value) || 0;
        const retroGosto = parseFloat(document.getElementById('retro_gosto').value) || 0;
        const equilibrio = parseFloat(document.getElementById('equilibrio').value) || 0;
        const docura = parseFloat(document.getElementById('docura').value) || 0;
        const uniformidade = parseFloat(document.getElementById('uniformidade').value) || 0;
        const defeitos = parseFloat(document.getElementById('defeitos').value) || 0;
        const balanceamento = parseFloat(document.getElementById('balanceamento').value) || 0;

        const aromaFinal = (aromaPo + fragranciaCafe) / 2;
        const notaTotal = aromaFinal + sabor + acidez + corpo + retroGosto + equilibrio + docura + uniformidade + defeitos + balanceamento;

        notaFinalInput.value = notaTotal.toFixed(2);
    }

    inputs.forEach(input => {
        input.addEventListener('input', calcularNotaTotal);
    });

    // Calcular na inicialização se houver valores
    calcularNotaTotal();
});
</script>
@endsection

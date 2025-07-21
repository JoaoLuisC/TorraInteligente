@extends('master')

@section('title', 'Editar Perfil - Michelangelo')
@section('breadcrumb-title', 'Editar Perfil')

@section('MainContent')
@if(Auth::check())
<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="bi bi-pencil me-2"></i>
                            Editar Perfil
                        </h3>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form id="formEditarPerfil" action="{{ route('perfil.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">
                                                <i class="bi bi-image me-2"></i>
                                                Foto do Perfil
                                            </h5>
                                        </div>
                                        <div class="card-body text-center">
                                            <div class="mb-3">
                                                @if(Auth::user()->imagem && file_exists(public_path('uploads/perfil/' . Auth::user()->imagem)))
                                                    <img id="preview-imagem" class="img-fluid img-circle mb-3"
                                                         src="{{ asset('uploads/perfil/' . Auth::user()->imagem) }}"
                                                         alt="Foto atual"
                                                         style="width: 120px; height: 120px; object-fit: cover;">
                                                @else
                                                    <i id="preview-icon" class="bi bi-person-circle mb-3" style="font-size: 120px; color: #6c757d;"></i>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label for="imagem" class="form-label">Alterar Foto</label>
                                                <input type="file" class="form-control @error('imagem') is-invalid @enderror"
                                                       id="imagem" name="imagem" accept="image/*">
                                                @error('imagem')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <small class="form-text text-muted">
                                                    Formatos aceitos: JPG, PNG, GIF. Tamanho máximo: 2MB
                                                </small>
                                                @if(Auth::user()->imagem)
                                                    <div class="mt-2">
                                                        <button type="button" id="btnRemoverImagem" class="btn btn-sm btn-outline-danger">
                                                            <i class="bi bi-trash me-1"></i>
                                                            Remover Foto Atual
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="nome" class="form-label">Nome *</label>
                                        <input type="text" class="form-control @error('nome') is-invalid @enderror"
                                               id="nome" name="nome" value="{{ old('nome', Auth::user()->nome) }}" required>
                                        @error('nome')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="sobrenome" class="form-label">Sobrenome *</label>
                                        <input type="text" class="form-control @error('sobrenome') is-invalid @enderror"
                                               id="sobrenome" name="sobrenome" value="{{ old('sobrenome', Auth::user()->sobrenome) }}" required>
                                        @error('sobrenome')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="email" class="form-label">E-mail *</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                               id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="tipo" class="form-label">Tipo de Usuário</label>
                                        <input type="text" class="form-control" id="tipo"
                                               value="{{ Auth::user()->tipo ?? 'Usuário' }}" readonly>
                                        <input type="hidden" name="tipo" value="{{ Auth::user()->tipo }}">
                                        <small class="form-text text-muted">Este campo não pode ser alterado</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg"></i> Salvar Alterações
                                </button>
                                <a href="{{ route('perfil') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-lg"></i> Cancelar
                                </a>
                            </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="alert alert-warning">
                    <h4><i class="bi bi-exclamation-triangle"></i> Acesso Negado</h4>
                    Você precisa estar logado para acessar esta página.
                    <a href="{{ route('login') }}" class="btn btn-primary ms-2">Fazer Login</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
console.log('Script carregado');

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM pronto');

    // Preview de imagem
    const imgInput = document.getElementById('imagem');
    if (imgInput) {
        imgInput.onchange = function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('preview-imagem');
                    const icon = document.getElementById('preview-icon');
                    if (preview) {
                        preview.src = e.target.result;
                    } else if (icon) {
                        icon.outerHTML = `<img id="preview-imagem" src="${e.target.result}"
                                               class="img-fluid img-circle mb-3"
                                               style="width: 120px; height: 120px; object-fit: cover;">`;
                    }
                };
                reader.readAsDataURL(file);
            }
        };
    }

    // Botão remover imagem
    const btnRemover = document.getElementById('btnRemoverImagem');
    if (btnRemover) {
        btnRemover.onclick = function() {
            if (confirm('Tem certeza que deseja remover a imagem?')) {
                this.disabled = true;
                this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Removendo...';

                const formData = new FormData();
                formData.append('_token', document.querySelector('input[name="_token"]').value);
                formData.append('_method', 'DELETE');

                fetch('/perfil/imagem', {
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
                    if (data.success) {
                        alert(data.message);
                        location.reload(); // Recarregar a página
                    } else {
                        alert('Erro: ' + data.message);
                        this.disabled = false;
                        this.innerHTML = '<i class="bi bi-trash me-1"></i>Remover Foto Atual';
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao remover imagem');
                    this.disabled = false;
                    this.innerHTML = '<i class="bi bi-trash me-1"></i>Remover Foto Atual';
                });
            }
        };
    }

    // Formulário principal - AJAX somente se há sidebar
    const form = document.getElementById('formEditarPerfil');
    if (form) {
        form.onsubmit = function(e) {
            console.log('Submit do formulário');

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
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Salvando...';
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
                        btn.innerHTML = '<i class="bi bi-check-lg"></i> Salvar Alterações';
                    }

                    if (data.success) {
                        alert(data.message);
                        // Redirecionar para perfil
                        const perfilLink = document.querySelector('a[href*="/perfil"]:not([href*="editar"])');
                        if (perfilLink) {
                            perfilLink.click();
                        } else {
                            window.location.href = '/perfil';
                        }
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
                        btn.innerHTML = '<i class="bi bi-check-lg"></i> Salvar Alterações';
                    }
                    alert('Erro ao salvar perfil');
                });
            } else {
                console.log('Enviando tradicionalmente');
                // Deixa o envio normal acontecer
            }
        };
    }
});
</script>
@endsection

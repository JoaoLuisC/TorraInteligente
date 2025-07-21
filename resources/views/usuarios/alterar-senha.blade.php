@extends('master')

@section('title', 'Alterar Senha - Michelangelo')
@section('breadcrumb-title', 'Alterar Senha')

@section('MainContent')
@if(Auth::check())
<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="bi bi-key me-2"></i>
                            Alterar Senha
                        </h3>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
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

                        <form action="{{ route('perfil.senha.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row justify-content-center">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="senha_atual" class="form-label">Senha Atual *</label>
                                        <input type="password" class="form-control @error('senha_atual') is-invalid @enderror"
                                               id="senha_atual" name="senha_atual" required>
                                        @error('senha_atual')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="nova_senha" class="form-label">Nova Senha *</label>
                                        <input type="password" class="form-control @error('nova_senha') is-invalid @enderror"
                                               id="nova_senha" name="nova_senha" required>
                                        @error('nova_senha')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">
                                            A senha deve ter pelo menos 8 caracteres
                                        </small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="nova_senha_confirmation" class="form-label">Confirmar Nova Senha *</label>
                                        <input type="password" class="form-control"
                                               id="nova_senha_confirmation" name="nova_senha_confirmation" required>
                                        <small class="form-text text-muted">
                                            Digite novamente a nova senha para confirmar
                                        </small>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Alterar Senha
                                        </button>
                                        <a href="{{ route('perfil') }}" class="btn btn-secondary ms-2">
                                            <i class="bi bi-x-circle me-1"></i>
                                            Cancelar
                                        </a>
                                    </div>
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

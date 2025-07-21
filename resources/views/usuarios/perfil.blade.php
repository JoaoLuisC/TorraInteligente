@extends('master')

@section('title', 'Perfil - Michelangelo')
@section('breadcrumb-title', 'Perfil do Usuário')

@section('MainContent')
@if(Auth::check())
<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="bi bi-person-circle me-2"></i>
                            Meu Perfil
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card card-primary card-outline">
                                    <div class="card-body box-profile">
                                        <div class="text-center">
                                            @if(Auth::user()->imagem && file_exists(public_path('uploads/perfil/' . Auth::user()->imagem)))
                                                <img class="profile-user-img img-fluid img-circle"
                                                     src="{{ asset('uploads/perfil/' . Auth::user()->imagem) }}"
                                                     alt="Foto do perfil"
                                                     style="width: 100px; height: 100px; object-fit: cover;">
                                            @else
                                                <i class="bi bi-person-circle" style="font-size: 80px; color: #6c757d;"></i>
                                            @endif
                                        </div>
                                        <h3 class="profile-username text-center">{{ Auth::user()->nome ?? 'Nome não informado' }}</h3>
                                        <p class="text-muted text-center">Usuário do Sistema</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Informações Pessoais</h3>
                                    </div>
                                    <div class="card-body">
                                        <form>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="name">Nome Completo</label>
                                                        <input type="text" class="form-control" id="name" value="{{ Auth::user()->nome ?? '' }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="sobrenome">Sobrenome</label>
                                                        <input type="text" class="form-control" id="sobrenome" value="{{ Auth::user()->sobrenome ?? '' }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="email">E-mail</label>
                                                        <input type="email" class="form-control" id="email" value="{{ Auth::user()->email ?? '' }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="tipo">Tipo de Usuário</label>
                                                        <input type="text" class="form-control" id="tipo" value="{{ Auth::user()->tipo ?? 'Usuário' }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="created_at">Membro desde</label>
                                                        <input type="text" class="form-control" id="created_at" value="{{ Auth::user()->criado_em ? \Carbon\Carbon::parse(Auth::user()->criado_em)->format('d/m/Y') : 'Data não disponível' }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="status">Status</label>
                                                        <input type="text" class="form-control" id="status" value="Ativo" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    @if(session('success'))
                                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                            {{ session('success') }}
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                                        </div>
                                                    @endif

                                                    <a href="{{ route('perfil.edit') }}" class="btn btn-primary ajax-link">
                                                        <i class="bi bi-pencil me-1"></i>
                                                        Editar Perfil
                                                    </a>
                                                    <a href="{{ route('perfil.alterar-senha') }}" class="btn btn-secondary ms-2 ajax-link">
                                                        <i class="bi bi-key me-1"></i>
                                                        Alterar Senha
                                                    </a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">
                                            <i class="bi bi-chat-dots me-2"></i>
                                            Mensagens do Sistema
                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="timeline">
                                            <div class="time-label">
                                                <span class="bg-info">Sistema</span>
                                            </div>
                                            <div>
                                                <i class="bi bi-person-check bg-success"></i>
                                                <div class="timeline-item">
                                                    <span class="time"><i class="bi bi-clock"></i> {{ now()->format('H:i') }}</span>
                                                    <h3 class="timeline-header">Bem-vindo ao Sistema</h3>
                                                    <div class="timeline-body">
                                                        Olá {{ Auth::user()->nome }}, seja bem-vindo ao sistema Michelangelo!
                                                        Você pode gerenciar seus torradores e acompanhar suas torras aqui.
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <i class="bi bi-info-circle bg-info"></i>
                                                <div class="timeline-item">
                                                    <span class="time"><i class="bi bi-clock"></i> Ontem</span>
                                                    <h3 class="timeline-header">Dica do Sistema</h3>
                                                    <div class="timeline-body">
                                                        Lembre-se de manter seus dados atualizados para uma melhor experiência.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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

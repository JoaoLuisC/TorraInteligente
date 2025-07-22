@extends('master')

@section('title', 'Gerenciar Usuários')
@section('breadcrumb-title', 'Administração - Usuários')

@section('MainContent')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card mt-5">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="card-title">
                                <i class="fas fa-users"></i> Gerenciar Usuários
                            </h3>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">
                                <i class="fas fa-chart-bar"></i> Dashboard
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($usuarios->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="border-0">Usuário</th>
                                        <th class="border-0">Email</th>
                                        <th class="border-0">Tipo</th>
                                        <th class="border-0">Cadastro</th>
                                        <th class="border-0 text-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($usuarios as $usuario)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($usuario->tipo === 'Administrador')
                                                        <i class="fas fa-crown text-warning me-2"></i>
                                                    @elseif($usuario->tipo === 'Analista')
                                                        <i class="fas fa-microscope text-info me-2"></i>
                                                    @else
                                                        <i class="fas fa-user text-success me-2"></i>
                                                    @endif
                                                    <div>
                                                        <strong>{{ $usuario->nome }} {{ $usuario->sobrenome }}</strong>
                                                        @if($usuario->id === Auth::id())
                                                            <span class="badge bg-primary ms-2">Você</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-muted">{{ $usuario->email }}</span>
                                            </td>
                                            <td>
                                                @if($usuario->tipo === 'Administrador')
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="fas fa-crown me-1"></i>
                                                        Administrador
                                                    </span>
                                                @elseif($usuario->tipo === 'Analista')
                                                    <span class="badge bg-info">
                                                        <i class="fas fa-microscope me-1"></i>
                                                        Analista
                                                    </span>
                                                @else
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-coffee me-1"></i>
                                                        Produtor
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="text-muted">
                                                    <i class="fas fa-calendar-alt me-1"></i>
                                                    {{ \Carbon\Carbon::parse($usuario->criado_em)->format('d/m/Y') }}
                                                    <br>
                                                    <small>{{ \Carbon\Carbon::parse($usuario->criado_em)->diffForHumans() }}</small>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @if($usuario->id !== Auth::id())
                                                    <div class="btn-group">
                                                        <button type="button"
                                                                class="btn btn-outline-info btn-sm"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#detalhesModal{{ $usuario->id }}"
                                                                title="Ver detalhes">
                                                            <i class="fas fa-eye me-1"></i>
                                                            Ver
                                                        </button>

                                                        @if($usuario->tipo !== 'Administrador')
                                                            <button type="button"
                                                                    class="btn btn-outline-danger btn-sm"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#excluirModal{{ $usuario->id }}"
                                                                    title="Excluir usuário">
                                                                <i class="fas fa-trash me-1"></i>
                                                                Excluir
                                                            </button>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-muted" title="Você não pode excluir sua própria conta">
                                                        <i class="fas fa-lock me-1"></i>
                                                        Protegido
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer bg-white border-0">
                            {{ $usuarios->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Nenhum usuário encontrado</h5>
                            <p class="text-muted">Os usuários cadastrados aparecerão aqui.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modais de Detalhes e Exclusão -->
@foreach($usuarios as $usuario)
<!-- Modal de Detalhes -->
<div class="modal fade" id="detalhesModal{{ $usuario->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user me-2"></i>
                    Detalhes do Usuário
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Nome Completo:</strong></td>
                                <td>{{ $usuario->nome }} {{ $usuario->sobrenome }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td>{{ $usuario->email }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tipo de Usuário:</strong></td>
                                <td>
                                    @if($usuario->tipo === 'Administrador')
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-crown me-1"></i>
                                            Administrador
                                        </span>
                                    @elseif($usuario->tipo === 'Analista')
                                        <span class="badge bg-info">
                                            <i class="fas fa-microscope me-1"></i>
                                            Analista
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            <i class="fas fa-coffee me-1"></i>
                                            Produtor
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Data de Cadastro:</strong></td>
                                <td>{{ \Carbon\Carbon::parse($usuario->criado_em)->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>
                    Fechar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Exclusão -->
@if($usuario->id !== Auth::id() && $usuario->tipo !== 'Administrador')
<div class="modal fade" id="excluirModal{{ $usuario->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Confirmar Exclusão
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="fas fa-user-times fa-3x text-danger mb-3"></i>
                    <h5>Tem certeza que deseja excluir este usuário?</h5>
                    <p class="text-muted">
                        <strong>{{ $usuario->nome }} {{ $usuario->sobrenome }}</strong><br>
                        {{ $usuario->email }}
                    </p>
                    <p class="text-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Esta ação não pode ser desfeita!
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>
                    Cancelar
                </button>
                <form method="POST" action="{{ route('admin.usuarios.excluir', $usuario->id) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>
                        Excluir Usuário
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endforeach
@endsection

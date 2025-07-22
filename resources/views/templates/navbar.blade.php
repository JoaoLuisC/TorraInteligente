<nav class="app-header navbar navbar-expand bg-body">

    <div class="container-fluid">
        <!--Navbar Links Esquerda-->
        <ul class="navbar-nav">
            <li class="nav-item"> <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button"> <i
                        class="bi bi-list"></i> </a></li>
            <li class="nav-item d-none d-md-block"><a href="{{ route('dashboard') }}" class="nav-link ajax-link">Dashboard</a></li>
        </ul>

        <!--Navbar Links Direita-->
        <ul class="navbar-nav ms-auto">

            <!--Fullscreen-->
            <li class="nav-item">
                <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                    <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                    <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
                </a>
            </li>

            <!--Notifications Dropdown Menu-->
            <li class="nav-item dropdown" id="notificationsDropdown">
                <a class="nav-link" data-bs-toggle="dropdown" href="#" id="notificationBell">
                    <i class="bi bi-bell-fill"></i>
                    <span class="navbar-badge badge text-bg-warning" id="notificationCount" style="display: none;">0</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end" id="notificationsContent">
                    <span class="dropdown-item dropdown-header" id="notificationHeader">Nenhuma notificação</span>
                    <div class="dropdown-divider"></div>
                    <div id="notificationList">
                        <div class="dropdown-item text-center text-muted">
                            <i class="bi bi-bell"></i><br>
                            Nenhuma notificação no momento
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer" onclick="marcarTodasLidas()">Marcar todas como lidas</a>
                </div>
            </li>


            <!--Usuario Dropdown Menu-->
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle user-dropdown-btn" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="d-flex align-items-center">
                        @if(Auth::check() && Auth::user()->imagem && file_exists(public_path('uploads/perfil/' . Auth::user()->imagem)))
                            <img src="{{ asset('uploads/perfil/' . Auth::user()->imagem) }}"
                                 class="rounded-circle me-2 profile-img" alt="Foto do usuário">
                        @else
                            <i class="bi bi-person-circle me-2" style="font-size: 24px;"></i>
                        @endif
                        <span class="d-none d-md-inline fw-medium">
                            {{ Auth::check() ? (Auth::user()->nome . ' ' . Auth::user()->sobrenome) : 'Usuário' }}
                        </span>
                        <i class="fas fa-chevron-down ms-1" style="font-size: 0.8em;"></i>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end modern-dropdown shadow-lg">
                    <li class="dropdown-header">
                        <div class="d-flex align-items-center p-2">
                            @if(Auth::check() && Auth::user()->imagem && file_exists(public_path('uploads/perfil/' . Auth::user()->imagem)))
                                <img src="{{ asset('uploads/perfil/' . Auth::user()->imagem) }}"
                                     class="rounded-circle me-3 profile-img-large" alt="Foto do usuário">
                            @else
                                <i class="bi bi-person-circle me-3" style="font-size: 50px; color: white;"></i>
                            @endif
                            <div>
                                <div class="fw-bold">
                                    {{ Auth::check() ? (Auth::user()->nome . ' ' . Auth::user()->sobrenome) : 'Usuário' }}
                                </div>
                                <small class="text-white-50">
                                    {{ Auth::check() ? Auth::user()->tipo : 'Tipo não disponível' }}
                                </small>
                                <br>
                                <small class="text-white-50">
                                    Membro desde
                                    @if(Auth::check() && Auth::user()->criado_em)
                                        {{ \Carbon\Carbon::parse(Auth::user()->criado_em)->format('d/m/Y') }}
                                    @else
                                        Data não disponível
                                    @endif
                                </small>
                            </div>
                        </div>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li class="dropdown-item-container">
                        <a href="{{ route('perfil') }}" class="dropdown-item d-flex align-items-center ajax-link">
                            <i class="fas fa-user me-2"></i>
                            <span>Meu Perfil</span>
                        </a>
                    </li>
                    <li class="dropdown-item-container">
                        <a href="{{ route('dashboard') }}" class="dropdown-item d-flex align-items-center ajax-link">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            <span>
                                @if(Auth::check())
                                    @if(Auth::user()->tipo === 'Administrador')
                                        Dashboard Admin
                                    @elseif(Auth::user()->tipo === 'Analista')
                                        Dashboard Analista
                                    @else
                                        Dashboard
                                    @endif
                                @else
                                    Dashboard
                                @endif
                            </span>
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li class="dropdown-item-container">
                        <form action="{{ route('logout') }}" method="POST" class="w-100">
                            @csrf
                            <button type="submit" class="dropdown-item d-flex align-items-center w-100 border-0 bg-transparent">
                                <i class="fas fa-sign-out-alt me-2"></i>
                                <span>Sair</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </li>

        </ul>
    </div>

</nav>

<script>
// Sistema de Notificações
let notificacoes = [];

// Verificar notificações a cada 30 segundos
setInterval(verificarNotificacoes, 30000);

// Verificar notificações ao carregar a página
$(document).ready(function() {
    verificarNotificacoes();
});

function verificarNotificacoes() {
    // Simular chamada AJAX para buscar notificações
    // Em produção, fazer chamada real para uma rota Laravel

    // Exemplo de notificações simuladas
    const novasNotificacoes = [
        {
            id: 1,
            tipo: 'avaliacao_concluida',
            titulo: 'Avaliação Concluída',
            mensagem: 'Sua torra "Bourbon Natural" foi avaliada pelo analista João',
            data: new Date(),
            lida: false
        },
        {
            id: 2,
            tipo: 'solicitacao_aceita',
            titulo: 'Solicitação Aceita',
            mensagem: 'Sua solicitação de prova foi aceita e está em análise',
            data: new Date(Date.now() - 3600000), // 1 hora atrás
            lida: false
        }
    ];

    // Atualizar notificações apenas se há novas
    if (novasNotificacoes.length !== notificacoes.length) {
        notificacoes = novasNotificacoes;
        atualizarInterfaceNotificacoes();
    }
}

function atualizarInterfaceNotificacoes() {
    const naoLidas = notificacoes.filter(n => !n.lida);
    const countElement = $('#notificationCount');
    const headerElement = $('#notificationHeader');
    const listElement = $('#notificationList');

    // Atualizar contador
    if (naoLidas.length > 0) {
        countElement.text(naoLidas.length).show();
        headerElement.text(`${naoLidas.length} nova${naoLidas.length > 1 ? 's' : ''} notificação${naoLidas.length > 1 ? 'ões' : ''}`);
    } else {
        countElement.hide();
        headerElement.text('Nenhuma notificação nova');
    }

    // Atualizar lista
    if (notificacoes.length > 0) {
        let html = '';
        notificacoes.forEach(notif => {
            const icone = getIconeNotificacao(notif.tipo);
            const tempo = getTempoRelativo(notif.data);
            const classe = notif.lida ? 'text-muted' : '';

            html += `
                <a href="#" class="dropdown-item ${classe}" onclick="marcarComoLida(${notif.id})">
                    <i class="${icone} me-2"></i> ${notif.titulo}
                    <span class="float-end text-secondary fs-7">${tempo}</span>
                    <div class="small">${notif.mensagem}</div>
                </a>
                <div class="dropdown-divider"></div>
            `;
        });
        listElement.html(html);
    } else {
        listElement.html(`
            <div class="dropdown-item text-center text-muted">
                <i class="bi bi-bell"></i><br>
                Nenhuma notificação no momento
            </div>
        `);
    }
}

function getIconeNotificacao(tipo) {
    switch(tipo) {
        case 'avaliacao_concluida': return 'bi bi-check-circle-fill text-success';
        case 'solicitacao_aceita': return 'bi bi-clock-fill text-info';
        case 'solicitacao_rejeitada': return 'bi bi-x-circle-fill text-danger';
        default: return 'bi bi-bell-fill text-primary';
    }
}

function getTempoRelativo(data) {
    const agora = new Date();
    const diff = agora - data;
    const segundos = Math.floor(diff / 1000);
    const minutos = Math.floor(segundos / 60);
    const horas = Math.floor(minutos / 60);
    const dias = Math.floor(horas / 24);

    if (dias > 0) return `${dias}d`;
    if (horas > 0) return `${horas}h`;
    if (minutos > 0) return `${minutos}m`;
    return 'agora';
}

function marcarComoLida(id) {
    const notificacao = notificacoes.find(n => n.id === id);
    if (notificacao) {
        notificacao.lida = true;
        atualizarInterfaceNotificacoes();

        // Ação baseada no tipo de notificação
        if (notificacao.tipo === 'avaliacao_concluida') {
            // Redirecionar para a página da torra ou mostrar detalhes
            showAlerta('Redirecionando para detalhes da avaliação...', 'info');
        }
    }
}

function marcarTodasLidas() {
    notificacoes.forEach(n => n.lida = true);
    atualizarInterfaceNotificacoes();
    showAlerta('Todas as notificações foram marcadas como lidas.', 'success');
}

// Função para mostrar alertas (deve estar disponível globalmente)
function showAlerta(mensagem, tipo = 'info') {
    const alertaHtml = `
        <div class="alert alert-${tipo} alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999;">
            ${mensagem}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;

    $('body').append(alertaHtml);

    setTimeout(function() {
        $('.alert').alert('close');
    }, 3000);
}
</script>

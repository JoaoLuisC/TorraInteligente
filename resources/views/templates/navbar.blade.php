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
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    @if(Auth::check() && Auth::user()->imagem && file_exists(public_path('uploads/perfil/' . Auth::user()->imagem)))
                        <img src="{{ asset('uploads/perfil/' . Auth::user()->imagem) }}"
                             class="user-image rounded-circle shadow" alt="Foto do usuário"
                             style="width: 25px; height: 25px; object-fit: cover;">
                    @else
                        <i class="bi bi-person-circle" style="font-size: 20px;"></i>
                    @endif
                    <span class="d-none d-md-inline">
                        {{ Auth::check() ? (Auth::user()->nome . ' ' . Auth::user()->sobrenome) : 'Usuário' }}
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <li class="user-header text-bg-primary">
                        @if(Auth::check() && Auth::user()->imagem && file_exists(public_path('uploads/perfil/' . Auth::user()->imagem)))
                            <img src="{{ asset('uploads/perfil/' . Auth::user()->imagem) }}"
                                 class="rounded-circle shadow" alt="Foto do usuário"
                                 style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                            <i class="bi bi-person-circle text-white" style="font-size: 80px;"></i>
                        @endif
                        <p>
                            {{ Auth::check() ? (Auth::user()->nome . ' ' . Auth::user()->sobrenome) : 'Usuário' }}
                            <small>
                                Membro desde {{ Auth::check() && Auth::user()->criado_em ? \Carbon\Carbon::parse(Auth::user()->criado_em)->format('d/m/Y') : 'Data não disponível' }}
                            </small>
                        </p>
                    </li>
                    <li class="user-footer">
                        <a href="{{ route('perfil') }}" class="btn btn-default btn-flat ajax-link">Perfil</a>
                        <button type="submit" form="logout-form" class="btn btn-default btn-flat float-end">
                            Sair
                        </button>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
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

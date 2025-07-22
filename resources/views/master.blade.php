<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Michelangelo')</title>
    <meta name="author" content="João Luís Cardoso" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta3/dist/css/adminlte.min.css" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css" integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css" integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css" integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4=" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ asset('css/Admin_Lte/adminlte.css') }}"/>
    @vite('resources/css/footer.css')

    <style>
        .sidebar-brand .brand-link {
            color: white !important;
            text-decoration: none !important;
        }

        .sidebar-brand .brand-link:hover {
            color: #f8f9fa !important;
            text-decoration: none !important;
        }

        .sidebar-brand h2 {
            color: white !important;
            font-weight: bold;
            margin: 0;
            padding: 0.5rem 0;
        }

        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1) !important;
            color: white !important;
        }

        .nav-treeview .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.05) !important;
        }

        .nav-arrow {
            transition: transform 0.3s ease;
        }

                .nav-item.menu-open .nav-arrow {
            transform: rotate(90deg);
        }

        /* Dropdown moderno para navbar */
        .user-dropdown-btn {
            border-radius: 8px;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem;
        }

        .user-dropdown-btn:hover {
            background-color: rgba(255,255,255,0.1);
            transform: translateY(-1px);
        }

        .modern-dropdown {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            padding: 0;
            margin-top: 0.5rem;
            min-width: 280px;
            backdrop-filter: blur(10px);
            background: rgba(108, 117, 125, 0.95); /* Cinza mais claro */
        }

        .dropdown-header {
            background: linear-gradient(135deg, #6c757d, #495057);
            color: white !important; /* Forçar texto branco */
            border-radius: 12px 12px 0 0;
            padding: 1rem;
            margin: 0;
        }

        .dropdown-header * {
            color: white !important; /* Garantir que todos os elementos filhos sejam brancos */
        }

        .dropdown-item-container .dropdown-item {
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            border: none;
            color: white !important; /* Texto branco para contraste com fundo cinza */
        }

        .dropdown-item-container .dropdown-item:hover {
            background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0.05));
            color: white !important;
            transform: translateX(5px);
        }

        .dropdown-item i {
            width: 16px;
            text-align: center;
            color: white !important; /* Ícones brancos */
        }

        .dropdown-divider {
            margin: 0.5rem 0;
            opacity: 0.3;
            border-color: rgba(255,255,255,0.3); /* Divider branco translúcido */
        }

        /* Estilo para foto de perfil com borda preta */
        .profile-img {
            border: 2px solid #000 !important;
            width: 32px;
            height: 32px;
            object-fit: cover;
        }

        .profile-img-large {
            border: 3px solid #000 !important;
            width: 50px;
            height: 50px;
            object-fit: cover;
        }

        /* Garantir que variedades não tenham background */
        .variedade-sem-bg, .variedade-sem-bg * {
            background: none !important;
            background-color: transparent !important;
            border: none !important;
            padding: 0 !important;
        }

        /* Estilo para nota final em destaque */
        .nota-final-destaque {
            font-size: 1.5rem;
            font-weight: bold;
            color: #28a745;
            background: rgba(40, 167, 69, 0.1);
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            border: 2px solid rgba(40, 167, 69, 0.2);
        }

        .nota-final-historico {
            font-size: 1.25rem;
            font-weight: bold;
            color: #28a745;
            background: rgba(40, 167, 69, 0.1);
            padding: 0.25rem 0.75rem;
            border-radius: 0.5rem;
            border: 1px solid rgba(40, 167, 69, 0.2);
        }
    </style>    @stack('styles')


</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">

        @include('templates.navbar')

        @include('templates.sidebar')

        <!--Conteudo Principal-->
        <main class="app-main">

            <!--HEADER CONTEUDO PRINCIPAL-->
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('dashboard') }}" class="ajax-link">
                                        <i class="bi bi-house-door me-1"></i>Home
                                    </a>
                                </li>
                                @if(request()->routeIs('dashboard'))
                                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                                @elseif(request()->routeIs('perfil'))
                                    <li class="breadcrumb-item active" aria-current="page">Perfil</li>
                                @elseif(request()->routeIs('perfil.edit'))
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('perfil') }}" class="ajax-link">Perfil</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">Editar Perfil</li>
                                @elseif(request()->routeIs('perfil.alterar-senha'))
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('perfil') }}" class="ajax-link">Perfil</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">Alterar Senha</li>
                                @elseif(request()->routeIs('torradores.*'))
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('torradores.index') }}" class="ajax-link">Torradores</a>
                                    </li>
                                    @if(request()->routeIs('torradores.index'))
                                        <li class="breadcrumb-item active" aria-current="page">Lista de Torradores</li>
                                    @elseif(request()->routeIs('torradores.adicionar-sensor'))
                                        <li class="breadcrumb-item active" aria-current="page">Adicionar Sensor</li>
                                    @elseif(request()->routeIs('torradores.edit'))
                                        <li class="breadcrumb-item active" aria-current="page">Editar Torrador</li>
                                    @elseif(request()->routeIs('torradores.show'))
                                        <li class="breadcrumb-item active" aria-current="page">Detalhes do Torrador</li>
                                    @endif
                                @elseif(request()->routeIs('torras.*'))
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('torras.iniciar') }}" class="ajax-link">Torras</a>
                                    </li>
                                    @if(request()->routeIs('torras.iniciar'))
                                        <li class="breadcrumb-item active" aria-current="page">Iniciar Torra</li>
                                    @endif
                                @else
                                    <li class="breadcrumb-item active" aria-current="page">
                                        @yield('breadcrumb-title', 'Página Atual')
                                    </li>
                                @endif
                            </ol>
                        </div>
                    </div>
                </div>


                <!-- Inserindo conteudo principal aqui -->
                @yield('MainContent')

                @push('scripts')
                <script>
                    // Exemplo de carregamento dinâmico via AJAX (Laravel API)
                    // Substitua '/api/torradores' pela sua rota real de API
                    $(document).ready(function() {
                        $.getJSON('/api/torradores', function(data) {
                            let html = '';
                            data.forEach(function(torrador) {
                                html += `
                                <div class="col-md-4">
                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <h3 class="card-title">${torrador.nome}</h3>
                                        </div>
                                        <div class="card-body">
                                            <p><strong>Código de Conexão:</strong> ${torrador.codigo_conexao}</p>
                                        </div>
                                    </div>
                                </div>
                                `;
                            });
                            $('.row').first().html(html);
                        });
                    });
                </script>
                @endpush


            </div>




            <!--AQUI EU CHAMO O CONTEUDO PRINCIPAL-->

        </main>

        <!--Footer-->
        <footer class="app-footer">
            <div class="float-end d-none d-sm-inline">Desenvolvido Por João Luís Cardoso</div>
            <strong> &copy; IFSULDEMINAS - Campus Machado | 2025&nbsp;</strong>
        </footer>

    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js" integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="{{ asset('js/Admin_Lte/adminlte.js') }}"></script>
    <script>
        const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
        const Default = {
            scrollbarTheme: 'os-theme-light',
            scrollbarAutoHide: 'leave',
            scrollbarClickScroll: true,
        };
        document.addEventListener('DOMContentLoaded', function () {
            const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
            if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
                OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                    scrollbars: {
                        theme: Default.scrollbarTheme,
                        autoHide: Default.scrollbarAutoHide,
                        clickScroll: Default.scrollbarClickScroll,
                    },
                });
            }
        });
    </script>
    <script>
        $(document).on('click', '.ajax-link', function(e) {
            var href = $(this).attr('href');
            // Só faz AJAX se for um link interno e diferente de '#'
            if (href && href !== '#' && !href.startsWith('http') && !href.startsWith('mailto')) {
                e.preventDefault();

                // Remove classe active de todos os links
                $('.sidebar-wrapper .nav-link').removeClass('active');
                // Adiciona classe active no link clicado
                $(this).addClass('active');

                // Carrega só o conteúdo principal da página de destino
                $('main.app-main').load(href + ' main.app-main > *', function(response, status) {
                    if (status === "error") {
                        $('main.app-main').html('<div class="alert alert-danger">Erro ao carregar conteúdo.</div>');
                    }
                });
                // Atualiza a URL no navegador
                window.history.pushState(null, '', href);
            }
        });

        // Para o título MICHELANGELO
        $(document).on('click', '.brand-link', function(e) {
            var href = $(this).attr('href');
            if (href && href !== '#' && !href.startsWith('http')) {
                e.preventDefault();

                // Remove classe active de todos os links
                $('.sidebar-wrapper .nav-link').removeClass('active');

                // Carrega o dashboard
                $('main.app-main').load(href + ' main.app-main > *', function(response, status) {
                    if (status === "error") {
                        $('main.app-main').html('<div class="alert alert-danger">Erro ao carregar conteúdo.</div>');
                    }
                });
                // Atualiza a URL no navegador
                window.history.pushState(null, '', href);
            }
        });

        // Suporte ao botão voltar/avançar do navegador
        window.onpopstate = function() {
            var href = location.pathname;
            $('main.app-main').load(href + ' main.app-main > *');
        };
    </script>

    @yield('scripts')

</body>
</html>

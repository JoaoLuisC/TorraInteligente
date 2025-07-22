<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Michelangelo')</title>
    <link rel="icon" href="{{ asset('images/graos-de-cafe.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/index_style/index.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta3/dist/css/adminlte.min.css" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css" integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css" integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css" integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4=" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ asset('css/Admin_Lte/adminlte.css') }}"/>
    @vite('resources/css/footer.css')

    @stack('styles')
    <style>
        .hero-header {
            background-image: url('/images/header-bg.jpeg');
            background-size: cover;
            background-position: center;
            min-height: 40vh;
            position: relative;
            box-shadow: 0 8px 32px rgba(0,0,0,0.25);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .hero-header::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.45);
            z-index: 1;
        }
        .hero-content {
            position: relative;
            z-index: 2;
        }

        /* Dropdown moderno */
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
            color: white !important;
            border-radius: 12px 12px 0 0;
            padding: 1rem;
            margin: 0;
        }

        .dropdown-header * {
            color: white !important;
        }

        .dropdown-item-container .dropdown-item {
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            border: none;
            color: white !important;
        }

        .dropdown-item-container .dropdown-item:hover {
            background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0.05));
            color: white !important;
            transform: translateX(5px);
        }

        .dropdown-item i {
            width: 16px;
            text-align: center;
            color: white !important;
        }

        .dropdown-divider {
            margin: 0.5rem 0;
            opacity: 0.3;
            border-color: rgba(255,255,255,0.3);
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
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="app-header navbar navbar-expand bg-body">
        <div class="container-fluid">
            <a href="{{ url('/') }}" class="navbar-brand d-flex align-items-center">
                <span class="ms-2 fw-bold">Michelangelo</span>
            </a>
            <ul class="navbar-nav ms-auto">
                @guest
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="nav-link">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('register') }}" class="nav-link">
                            <i class="fas fa-user-plus"></i> Registrar
                        </a>
                    </li>
                @else
                    <li class="nav-item dropdown user-menu">
                        <a href="#" class="nav-link dropdown-toggle user-dropdown-btn" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                @if(Auth::user()->imagem && file_exists(public_path('uploads/perfil/' . Auth::user()->imagem)))
                                    <img src="{{ asset('uploads/perfil/' . Auth::user()->imagem) }}"
                                         class="rounded-circle me-2 profile-img" alt="Foto do usuário">
                                @else
                                    <i class="bi bi-person-circle me-2" style="font-size: 24px;"></i>
                                @endif
                                <span class="d-none d-md-inline fw-medium">{{ Auth::user()->nome }} {{ Auth::user()->sobrenome }}</span>
                                <i class="fas fa-chevron-down ms-1" style="font-size: 0.8em;"></i>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end modern-dropdown shadow-lg">
                            <li class="dropdown-header">
                                <div class="d-flex align-items-center p-2">
                                    @if(Auth::user()->imagem && file_exists(public_path('uploads/perfil/' . Auth::user()->imagem)))
                                        <img src="{{ asset('uploads/perfil/' . Auth::user()->imagem) }}"
                                             class="rounded-circle me-3 profile-img-large" alt="Foto do usuário">
                                    @else
                                        <i class="bi bi-person-circle me-3" style="font-size: 50px; color: white;"></i>
                                    @endif
                                    <div>
                                        <div class="fw-bold">{{ Auth::user()->nome }} {{ Auth::user()->sobrenome }}</div>
                                        <small class="text-white-50">{{ Auth::user()->tipo }}</small>
                                        <br>
                                        <small class="text-white-50">
                                            Membro desde
                                            @if(Auth::user()->criado_em)
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
                                <a href="{{ route('perfil') }}" class="dropdown-item d-flex align-items-center">
                                    <i class="fas fa-user me-2"></i>
                                    <span>Meu Perfil</span>
                                </a>
                            </li>
                            @if(request()->routeIs('home'))
                                <li class="dropdown-item-container">
                                    <a href="{{ route('dashboard') }}" class="dropdown-item d-flex align-items-center">
                                        <i class="fas fa-tachometer-alt me-2"></i>
                                        <span>Dashboard</span>
                                    </a>
                                </li>
                            @endif
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
                @endguest
            </ul>
        </div>
    </nav>

    @yield('MainContent')

    <!-- Footer -->
    <footer class="app-footer">
        <div class="float-end d-none d-sm-inline">Desenvolvido Por João Luís Cardoso</div>
        <strong> &copy; IFSULDEMINAS - Campus Machado | 2025&nbsp;</strong>
    </footer>

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
        $(document).on('click', '.sidebar-wrapper a', function(e) {
            var href = $(this).attr('href');
            if (href && href !== '#' && !href.startsWith('http')) {
                e.preventDefault();
                $('main.app-main').load(href + ' main.app-main > *', function(response, status) {
                    if (status === "error") {
                        $('main.app-main').html('<div class="alert alert-danger">Erro ao carregar conteúdo.</div>');
                    }
                });
                window.history.pushState(null, '', href);
            }
        });
        window.onpopstate = function() {
            var href = location.pathname;
            $('main.app-main').load(href + ' main.app-main > *');
        };
    </script>
</body>
</html>

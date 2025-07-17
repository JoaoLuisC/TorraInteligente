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
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="d-none d-md-inline">{{ Auth::user()->name }} {{ Auth::user()->sobrenome }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <li class="user-header text-bg-primary text-center">
                                <img src="{{ asset('images/logoMichelangelo.png') }}" class="rounded-circle shadow mb-2"
                                    alt="User" style="width: 80px; height: 80px;" />
                                <p>
                                    {{ Auth::user()->name }} {{ Auth::user()->sobrenome }}
                                    <small>
                                        Membro desde
                                        @if(Auth::user()->criado_em)
                                            {{ Auth::user()->criado_em->format('Y/m/d') }}
                                        @else
                                            Data não disponível
                                        @endif
                                    </small>
                                </p>
                            </li>
                            <li class="user-footer d-flex justify-content-between">
                                <a href="#" class="btn btn-default btn-flat">Perfil</a>
                                <a href="{{ route('dashboard') }}" class="btn btn-default btn-flat">DashBoard</a>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-default btn-flat">Sair</button>
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

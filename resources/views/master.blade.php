<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Torra Inteligente')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    @vite('resources/css/footer.css')
    @vite('resources/css/navbar.css')
    @stack('styles')
    <style>
        
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('images/michelangeloTXT.png') }}" alt="Logo" style="height: 2.4em;">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav" style="font-size: 1.5em;">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/sobre') }}">Sobre</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/contato') }}">Contato</a></li>
                    <li class="nav-item mx-2 text-white" style="font-size: 1.5em;">|</li>
                    @guest
                        <li class="nav-item ml-1"><a class="nav-link" href="{{ url('/login') }}">Entrar</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ url('/register') }}">Cadastrar</a></li>
                    @endguest
                    @auth
                        <li class="nav-item ml-1">
                            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                                @csrf
                                <button class="nav-link btn btn-link" type="submit" style="padding:0; color:inherit;">Torras</button>
                                <button class="nav-link btn btn-link" type="submit" style="padding:0; color:inherit;">Sair</button>
                            </form>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main -->
    @yield('MainContent')


    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3">
        <p>{{ date('Y') }} Michelangelo. Todos os direitos reservados.</p>
        <p>&copy; IFSULDEMINAS - Campus Machado | João Luís Cardoso</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    @stack('scripts')
</body>
</html>

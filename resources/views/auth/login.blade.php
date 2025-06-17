<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Michelangelo | Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="{{ asset('images/graos-de-cafe.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/Admin_Lte/adminlte.css') }}" />
</head>
<body class="login-page bg-body-secondary">
    <div class="login-box">
        <div class="register-logo mb-3 text-center">
            <a href="{{ url('/') }}">
                <img src="{{ asset('images/logoMichelangelo.png') }}" alt="Michelangelo Logo" style="height: 20vh; margin-bottom: 3vh;">
            </a>
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Faça login para acessar o sistema</p>
                {{-- Exibe mensagens de erro --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                {{-- Exibe mensagem de sucesso --}}
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <form action="{{ route('login.post') }}" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="E-mail" required autofocus value="{{ old('email') }}">
                        <div class="input-group-text"><span class="bi bi-envelope"></span></div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Senha" required>
                        <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-8">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label" for="remember">Lembrar-me</label>
                            </div>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary w-100">Entrar</button>
                        </div>
                    </div>
                </form>
                <div class="text-center mb-2">
                    <a href="#">Esqueci minha senha</a>
                </div>
                <div class="text-center">
                    <a href="{{ route('register') }}">Registrar nova conta</a>
                </div>
            </div>
        </div>
    </div>
    <footer class="text-center mt-4 text-muted">
        <small>&copy; IFSULDEMINAS - Campus Machado | 2025</small>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

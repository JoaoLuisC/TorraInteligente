<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Registrar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="{{ asset('images/graos-de-cafe.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/Admin_Lte/adminlte.css') }}" />
</head>
<body class="register-page bg-body-secondary">
    <div class="register-box">
        <div class="register-logo mb-3 text-center">
            <a href="{{ url('/') }}">
                <img src="{{ asset('images/logoMichelangelo.png') }}" alt="Michelangelo Logo" style="height: 20vh; margin-bottom: 3vh;">
            </a>
        </div>
        <div class="card">
            <div class="card-body register-card-body">
                <p class="register-box-msg">Crie sua conta</p>
                <form action="{{ route('register') }}" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" name="firstName" class="form-control" placeholder="Nome" required autofocus>
                        <div class="input-group-text"><span class="bi bi-person"></span></div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" name="lastName" class="form-control" placeholder="Sobrenome" required>
                        <div class="input-group-text"><span class="bi bi-person"></span></div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="E-mail" required>
                        <div class="input-group-text"><span class="bi bi-envelope"></span></div>
                    </div>
                    <div class="input-group mb-3">
                        <select name="role" class="form-select" required>
                            <option value="" disabled selected>Selecione o tipo de registro</option>
                            <option value="analista">Analista</option>
                            <option value="administrador">Administrador</option>
                            <option value="produtor">Produtor</option>
                        </select>
                        <div class="input-group-text"><span class="bi bi-person-badge"></span></div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Senha" required>
                        <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirme a senha" required>
                        <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-8">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    Concordo com os <a href="#">termos</a>
                                </label>
                            </div>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary w-100">Registrar</button>
                        </div>
                    </div>
                </form>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="text-center mt-2">
                    <a href="{{ route('login') }}">Já tenho uma conta</a>
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

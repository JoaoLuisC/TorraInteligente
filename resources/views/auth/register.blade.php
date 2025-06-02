@extends('master')

@push('styles')
    @vite('resources/css/auth/register.css')
@endpush

@section('title', 'Cadastro')

@section('MainContent')
<main class="container-fluid vh-100">
    <div class="row h-100">
        <section class="col-md-6 d-flex align-items-center justify-content-center">
            <div class="text-center">
                <h1>A DEFINIR</h1>
            </div>
        </section>
        <section class="col-md-6 bg-green d-flex align-items-center justify-content-center">
            <div class="form-container">
                <header class="text-center mb-4">
                    <div class="image-placeholder mb-3">
                        <img src="{{ asset('images/imagem_login_page_cafe.png') }}" alt="Cadastro" />
                    </div>
                    <h2>Crie sua conta</h2>
                    <p>Junte-se ao mundo do café e tecnologia.</p>
                </header>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="firstName" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="firstName" name="firstName" value="{{ old('firstName') }}" required />
                        </div>
                        <div class="col-md-6">
                            <label for="lastName" class="form-label">Sobrenome</label>
                            <input type="text" class="form-control" id="lastName" name="lastName" value="{{ old('lastName') }}" required />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="email" class="form-label">Endereço de Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required />
                        </div>
                        <div class="col-md-6">
                            <label for="email_confirmation" class="form-label">Confirmação de Email</label>
                            <input type="email" class="form-control" id="email_confirmation" name="email_confirmation" required />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label">Senha</label>
                            <input type="password" class="form-control" id="password" name="password" required />
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Confirmação de Senha</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required />
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
                    <nav class="mt-3 text-center">
                        <a href="{{ url('/login') }}">Já tem uma conta? Faça seu login</a>
                    </nav>
                </form>
            </div>
        </section>
    </div>
</main>
@endsection

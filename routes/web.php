<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\TorradorController;
use App\Http\Controllers\TorrasController;
use App\Http\Controllers\PerfilController;

// Home - Redireciona para Dashboard
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
})->name('home');

// Cadastro (Register)
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Torradores
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () { return view('usuarios.dashboard'); })->name('dashboard');
    Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil');
    Route::get('/perfil/editar', [PerfilController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil', [PerfilController::class, 'update'])->name('perfil.update');
    Route::delete('/perfil/imagem', [PerfilController::class, 'removerImagem'])->name('perfil.remover-imagem');
    Route::get('/perfil/alterar-senha', [PerfilController::class, 'showAlterarSenha'])->name('perfil.alterar-senha');
    Route::put('/perfil/senha', [PerfilController::class, 'alterarSenha'])->name('perfil.senha.update');

    Route::prefix('torradores')->group(function () {
        Route::get('/', [TorradorController::class, 'index'])->name('torradores.index');
        Route::get('/adicionar-torrador', [TorradorController::class, 'create'])->name('torradores.adicionar-sensor');
        Route::post('/adicionar-torrador', [TorradorController::class, 'store'])->name('torradores.store');
        Route::get('/{id}', [TorradorController::class, 'show'])->name('torradores.show');
        Route::get('/{id}/editar', [TorradorController::class, 'edit'])->name('torradores.edit');
        Route::put('/{id}/editar', [TorradorController::class, 'update'])->name('torradores.update');
        Route::delete('/{id}', [TorradorController::class, 'destroy'])->name('torradores.destroy');
    });

    Route::prefix('torras')->name('torras.')->group(function () {
        Route::get('/', [TorrasController::class, 'index'])->name('index');
        Route::get('/iniciar', [TorrasController::class, 'iniciar'])->name('iniciar');
        Route::post('/iniciar', [TorrasController::class, 'store'])->name('store');
        Route::get('/monitoramento', [TorrasController::class, 'monitoramento'])->name('monitoramento');
        Route::delete('/{id}', [TorrasController::class, 'destroy'])->name('destroy');
        // Adicione outras rotas de torras aqui, se houver
    });

    // Rotas de Prova
    Route::get('/solicitar-prova', [App\Http\Controllers\ProvaController::class, 'index'])->name('prova.solicitar');
    Route::post('/solicitar-prova', [App\Http\Controllers\ProvaController::class, 'solicitar'])->name('prova.solicitar.post');
    Route::get('/solicitacoes-prova', [App\Http\Controllers\ProvaController::class, 'listar'])->name('prova.listar');

});


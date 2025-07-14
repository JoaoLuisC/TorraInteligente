<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\TorradorController;
use App\Http\Controllers\TorrasController;

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Cadastro (Register)
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Torradores
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () { return view('usuarios.dashboard'); })->name('dashboard');

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
        Route::get('/iniciar', [TorrasController::class, 'iniciar'])->name('iniciar');
        // Adicione outras rotas de torras aqui, se houver
    });

});


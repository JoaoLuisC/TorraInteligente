<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\TorradorController;

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
    Route::prefix('torradores')->group(function () {
        Route::get('/', [TorradorController::class, 'index'])->name('torradores.index');
        Route::get('/adicionar-torrador', [TorradorController::class, 'create'])->name('torradores.adicionar-sensor');
        Route::post('/adicionar-torrador', [TorradorController::class, 'store'])->name('torradores.store');
    });

    Route::get('/dashboard', function () { return view('usuarios.dashboard'); })->name('dashboard');


});

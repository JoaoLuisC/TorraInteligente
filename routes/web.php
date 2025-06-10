<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\TorradorController;

Route::get('/', [HomeController::class, 'index'])->name('home');
#cadastro
Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');

Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

Route::get('/torradores', [TorradorController::class, 'index'])->name('torradores.index');
Route::get('/torradores/adicionar-torrador', [TorradorController::class, 'create'])->name('torradores.adicionar-sensor');
Route::post('/torradores/adicionar-torrador', [TorradorController::class, 'store'])->name('torradores.store');

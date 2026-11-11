<?php

use App\Http\Controllers\AuthController;
use App\Http\Middleware\CheckAdmin;
use App\Http\Middleware\CheckAge;
use App\Http\Middleware\CheckAuth;
use Illuminate\Support\Facades\Route;

/*
| Zonas públicas: acceso libre.
*/
Route::view('/', 'home')->name('home');
Route::view('/login', 'login')->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::view('/register', 'register')->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');

/*
| Zonas privadas: requieren haber iniciado sesión.
*/
Route::middleware(CheckAuth::class)->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::view('/panel', 'panel-usuario')->name('panel');

    Route::view('/casino', 'casino')->middleware(CheckAge::class)->name('casino');

    Route::view('/admin', 'panel-admin')->middleware(CheckAdmin::class)->name('admin');
});

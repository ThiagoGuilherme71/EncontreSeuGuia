<?php

use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\SignupController;
use App\Http\Controllers\GuiaController;
use App\Http\Controllers\TrilhaController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\ClienteController;



//Login
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
//SignUp for
Route::get('/signup', [SignupController::class, 'showSignupForm'])->name('signup');
Route::post('/signup', [SignupController::class, 'signup'])->name('signup.submit');
Route::get('/signup-guia', [SignupController::class, 'showGuiaSignupForm'])->name('signup.guia');
Route::post('/signup-guia-submit', [SignupController::class, 'signupGuia'])->name('signup.guia.submit');
//guia-List
Route::get('/guia-dash', [GuiaController::class, 'index'])->name('guia-dash');
Route::get('/guias-list', [GuiaController::class, 'list'])->name('guia-list');

//Lading page
Route::get('/landing-page', [GuiaController::class, 'landingPage'])->name('landing-page');

//trilha
Route::get('/trilhaSelecionada', [TrilhaController::class, 'trilhaSelecionada'])->name('trilhaSelecionada');
Route::get('/buscar-trilha', [TrilhaController::class, 'buscar'])->name('trilhas.buscar');
Route::get('/trilhas/{nome}', [TrilhaController::class, 'exibir'])->name('trilhas.exibir');
Route::get('/trilhas/', [TrilhaController::class, 'create'])->name('trilhas.create');
Route::post('/trilhas/', [TrilhaController::class, 'store'])->name('trilhas.store');

//conta
Route::get('/conta', [ClienteController::class, 'index'])->name('conta.cliente');
Route::get('/conta-guia', [GuiaController::class, 'exibirConta'])->name('conta.guia');


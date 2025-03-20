<?php

use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\SignupController;
use App\Http\Controllers\GuiaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('main');
});


//Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
//SignUp for
Route::get('/signup', [SignupController::class, 'showSignupForm'])->name('signup');
Route::post('/signup', [SignupController::class, 'signup'])->name('signup.submit');
Route::get('/signup-guia', [SignupController::class, 'showGuiaSignupForm'])->name('signup.guia');
Route::post('/signup-guia', [SignupController::class, 'signupGuia'])->name('signup.guia.submit');
//Guia-List
Route::get('/guias-list', [GuiaController::class, 'index'])->name('guia-list');

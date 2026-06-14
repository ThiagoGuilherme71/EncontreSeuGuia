<?php

use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\SignupController;
use App\Http\Controllers\GuiaController;
use App\Http\Controllers\TrilhaController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\ClienteController;



//Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
//SignUp for
Route::get('/signup', [SignupController::class, 'showSignupForm'])->name('signup');
Route::post('/signup', [SignupController::class, 'signup'])->name('signup.submit');
Route::get('/signup-guia', [SignupController::class, 'showGuiaSignupForm'])->name('signup.guia');
Route::post('/signup-guia-submit', [SignupController::class, 'signupGuia'])->name('signup.guia.submit');
//guia
Route::get('/guia-dash', [GuiaController::class, 'index'])->middleware('auth:guia')->name('guia-dash');

Route::get('/', [ClienteController::class, 'landingPage'])->name('landing-page');

//trilha — público
Route::get('/buscar-trilha', [TrilhaController::class, 'buscar'])->name('trilhas.buscar');
Route::get('/get-all-trilhas/', [TrilhaController::class, 'getAllTrilhas'])->name('trilhas.getAll');
Route::get('/get-trilhas/{id}', [TrilhaController::class, 'getTrilha'])->name('trilhas.get');

//trilha — gestão (guia)
Route::middleware('auth:guia')->group(function () {
    Route::get('/trilhas/criar', [TrilhaController::class, 'create'])->name('trilhas.create');
    Route::post('/trilhas', [TrilhaController::class, 'store'])->name('trilhas.store');
    Route::post('/trilhas/{id}/inscrever', [TrilhaController::class, 'inscrever'])->whereNumber('id')->name('trilhas.inscrever');
    Route::get('/trilhas/{id}/editar', [TrilhaController::class, 'edit'])->whereNumber('id')->name('trilhas.edit');
    Route::post('/trilhas/{id}', [TrilhaController::class, 'update'])->whereNumber('id')->name('trilhas.update');
    Route::patch('/trilhas/{id}/congelar', [TrilhaController::class, 'congelar'])->whereNumber('id')->name('trilhas.congelar');
    Route::patch('/trilhas/{id}/reativar', [TrilhaController::class, 'reativar'])->whereNumber('id')->name('trilhas.reativar');
    Route::patch('/trilhas/{id}/inscricao', [TrilhaController::class, 'atualizarInscricao'])->whereNumber('id')->name('trilhas.inscricao');
});

Route::get('/trilhas/{id}', [TrilhaController::class, 'exibir'])->whereNumber('id')->name('trilhas.exibir');

//conta
Route::get('/conta', [ClienteController::class, 'index'])->middleware('auth:web')->name('conta.cliente');
Route::get('/conta-guia', [GuiaController::class, 'exibirConta'])->middleware('auth:guia')->name('conta.guia');

//agendamentos — trilheiro
Route::middleware('auth:web')->group(function () {
    Route::get('/agendamentos/criar', [\App\Http\Controllers\AgendamentoController::class, 'create'])->name('agendamentos.create');
    Route::post('/agendamentos', [\App\Http\Controllers\AgendamentoController::class, 'store'])->name('agendamentos.store');
    Route::get('/agendamentos/{id}/pagamento', [\App\Http\Controllers\AgendamentoController::class, 'payment'])->name('agendamentos.payment');
    Route::patch('/agendamentos/{id}/pagar', [\App\Http\Controllers\AgendamentoController::class, 'pay'])->name('agendamentos.pay');
});

//agendamentos — guia
Route::middleware('auth:guia')->group(function () {
    Route::patch('/agendamentos/{id}/aceitar', [\App\Http\Controllers\AgendamentoController::class, 'accept'])->name('agendamentos.accept');
    Route::patch('/agendamentos/{id}/rejeitar', [\App\Http\Controllers\AgendamentoController::class, 'reject'])->name('agendamentos.reject');
});

//avaliações — trilheiro
Route::post('/agendamentos/{id}/avaliar', [\App\Http\Controllers\AvaliacaoController::class, 'store'])
    ->middleware('auth:web')->whereNumber('id')->name('avaliacoes.store');

//agendamentos — ambos
Route::middleware('auth:web,guia')->group(function () {
    Route::get('/agendamentos/{id}', [\App\Http\Controllers\AgendamentoController::class, 'show'])->whereNumber('id')->name('agendamentos.show');
    Route::patch('/agendamentos/{id}/cancelar', [\App\Http\Controllers\AgendamentoController::class, 'cancel'])->name('agendamentos.cancel');
    Route::get('/agendamentos/{id}/recibo', [\App\Http\Controllers\AgendamentoController::class, 'receipt'])->name('agendamentos.receipt');

    //chat
    Route::get('/chat/{id}', [\App\Http\Controllers\ChatController::class, 'show'])->whereNumber('id')->name('chat.show');
    Route::post('/chat/{id}', [\App\Http\Controllers\ChatController::class, 'store'])->whereNumber('id')->name('chat.store');

    //perfil
    Route::get('/perfil/editar', [\App\Http\Controllers\PerfilController::class, 'edit'])->name('perfil.edit');
    Route::post('/perfil', [\App\Http\Controllers\PerfilController::class, 'update'])->name('perfil.update');

    //fotos da aventura
    Route::post('/agendamentos/{id}/fotos', [\App\Http\Controllers\FotoAventuraController::class, 'store'])->whereNumber('id')->name('fotos.store');
    Route::delete('/fotos-aventura/{id}', [\App\Http\Controllers\FotoAventuraController::class, 'destroy'])->whereNumber('id')->name('fotos.destroy');

    //notificações
    Route::get('/notificacoes', [\App\Http\Controllers\NotificacaoController::class, 'index'])->name('notificacoes.index');
    Route::patch('/notificacoes/ler-todas', [\App\Http\Controllers\NotificacaoController::class, 'markAllRead'])->name('notificacoes.lerTodas');
    Route::patch('/notificacoes/{id}/ler', [\App\Http\Controllers\NotificacaoController::class, 'markRead'])->name('notificacoes.ler');
});


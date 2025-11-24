<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;

Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);


Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');


Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');
Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
Route::view('/demo', 'demo');

Route::middleware(['auth'])->group(function () {
    Route::get('/', fn() => view('dashboard'))->name('dashboard');
    Route::get('/tracking', fn() => view('tracking'))->name('tracking');
    Route::get('/registration', fn() => view('registration'))->name('registration');
    Route::get('/profile', fn() => view('profile'))->name('profile');
    // MOCK para a questão 3: Agendamento de Coleta
    Route::get('/schedule', fn() => view('schedule'))->name('schedule');
});

// ROTA PARA EXIBIR A PÁGINA DE AGENDAMENTO
Route::get('/agendamento/criar', function () {
    // Apenas retorna a view que criamos anteriormente
    return view('scheduling.create');
})->middleware(['auth'])->name('schedule.create');

// ROTA PARA PROCESSAR O ENVIO DO FORMULÁRIO (AÇÃO DE AGENDAR)
Route::post('/agendamento', function () {
    return redirect()->route('dashboard')->with('success', 'Coleta agendada com sucesso!');
})->middleware(['auth'])->name('schedule.store');


require __DIR__.'/auth.php';

require __DIR__.'/auth.php';

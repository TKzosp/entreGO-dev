<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\PedidoController;
use App\Models\Rastreamento;

// ROTAS PÚBLICAS (SEM AUTH)

// Tela de login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Tela de cadastro
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);


// ROTAS PROTEGIDAS (COM AUTH)
Route::middleware(['auth'])->group(function () {
    // Dashboard principal
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Se quiser manter /dashboard também, redirecionando:
    Route::get('/dashboard', function () {
        return redirect()->route('dashboard');
    })->name('dashboard.redirect');

    // TRACKING – tela principal
    Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking');

    // RF04 – Gerar rota otimizada (chamada via AJAX pelo front)
    Route::post('/tracking/otimizar', [TrackingController::class, 'otimizar'])
        ->name('tracking.otimizar');

    // RF05 – Buscar posição atual de uma rota (AJAX)
    Route::get('/tracking/rotas/{rota}/posicao-atual', [TrackingController::class, 'posicaoAtual'])
        ->name('tracking.posicao-atual');

    // Registration e Profile como views simples
    Route::view('/registration', 'registration')->name('registration');
    Route::view('/profile', 'profile')->name('profile');

    // RF07 – Agendar coleta
    Route::post('/pedidos', [PedidoController::class, 'store'])->name('pedidos.store');

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking.index');
    Route::post('/tracking/otimizar', [TrackingController::class, 'otimizarRota'])->name('tracking.otimizar');
    // Rota para a atualização da posição em tempo real
Route::get('/tracking/rotas/{id}/posicao', function ($id) {
    // Busca o último registo de rastreamento para esta rota
    // Se não tiveres o Model, podes usar: DB::table('rastreamento')->where('rota_id', $id)->latest()->first();
    $ultimoRastro = Rastreamento::where('rota_id', $id)->orderBy('created_at', 'desc')->first();
    
    return response()->json([
        'latitude' => $ultimoRastro ? $ultimoRastro->latitude : null,
        'longitude' => $ultimoRastro ? $ultimoRastro->longitude : null,
    ]);
})->name('tracking.posicao');
});

// Se o seu projeto original tiver isso aqui no final e estiver dando conflito de rotas de auth,
// você pode comentar:
//
// require __DIR__.'/auth.php';

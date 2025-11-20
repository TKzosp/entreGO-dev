<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\PedidoController;
use App\Models\Rastreamento;

// ==============================================================================
// ROTAS PÚBLICAS (SEM AUTH)
// ==============================================================================

// Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Cadastro
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

// ==============================================================================
// ROTAS PROTEGIDAS (COM AUTH)
// ==============================================================================
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    // Redirecionamento para manter compatibilidade com links antigos
    Route::get('/dashboard', function () {
        return redirect()->route('dashboard');
    });

    // --------------------------------------------------------------------------
    // MÓDULO DE TRACKING
    // --------------------------------------------------------------------------
    
    // 1. Tela principal do mapa (usa o nome 'tracking' conforme navigation.blade.php)
    Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking');

    // 2. Otimização de rotas (AJAX)
    Route::post('/tracking/otimizar', [TrackingController::class, 'otimizar'])
        ->name('tracking.otimizar');

    // 3. API de Posição em Tempo Real (AJAX)
    // O frontend chama: /tracking/rotas/{id}/posicao-atual
    Route::get('/tracking/rotas/{id}/posicao-atual', function ($id) {
        $ultimoRastro = Rastreamento::where('rota_id', $id)
            ->orderBy('created_at', 'desc')
            ->first();
        
        return response()->json([
            'latitude' => $ultimoRastro ? $ultimoRastro->latitude : null,
            'longitude' => $ultimoRastro ? $ultimoRastro->longitude : null,
        ]);
    })->name('tracking.posicao-atual');

    // --------------------------------------------------------------------------
    // OUTROS
    // --------------------------------------------------------------------------

    // Views simples
    Route::view('/registration', 'registration')->name('registration');
    Route::view('/profile', 'profile')->name('profile');

    // Pedidos
    Route::post('/pedidos', [PedidoController::class, 'store'])->name('pedidos.store');

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
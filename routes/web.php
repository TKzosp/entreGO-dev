<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordController; // Importante para mudar senha
use App\Http\Controllers\Auth\EmailVerificationNotificationController; // Importante para o erro atual
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProfileController; // Controller do Perfil
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
    Route::get('/dashboard', function () {
        return redirect()->route('dashboard');
    })->name('dashboard.redirect');

    // --------------------------------------------------------------------------
    // MÓDULO DE PERFIL E SEGURANÇA
    // --------------------------------------------------------------------------
    
    // Exibir e Editar Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Alterar Senha (corrige erros no form de senha)
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    // Reenviar Email de Verificação (corrige o erro RouteNotFoundException)
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // --------------------------------------------------------------------------
    // MÓDULO DE TRACKING
    // --------------------------------------------------------------------------
    
    Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking');

    Route::post('/tracking/otimizar', [TrackingController::class, 'otimizar'])
        ->name('tracking.otimizar');

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
    // PEDIDOS E OUTROS
    // --------------------------------------------------------------------------

    Route::view('/registration', 'registration')->name('registration');
    Route::post('/pedidos', [PedidoController::class, 'store'])->name('pedidos.store');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
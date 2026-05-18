<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\WaypointController;
use App\Http\Controllers\MotoristaController;
use App\Http\Controllers\RotaController;
use App\Http\Controllers\PlanoAssinaturaController;

// ==============================================================================
// ROTAS PÚBLICAS
// ==============================================================================

// Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])
    ->middleware('throttle:10,1'); // 10 tentativas/minuto por IP (camada extra além do RateLimiter no controller)

// Cadastro
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);


// ==============================================================================
// ROTAS PROTEGIDAS
// ==============================================================================
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.redirect');

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Segurança
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // =========================================================
    // TRACKING
    // =========================================================
    Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking');

    Route::post('/tracking/otimizar', [TrackingController::class, 'otimizar'])
        ->middleware('throttle:30,1')
        ->name('tracking.otimizar');

    Route::get('/tracking/rotas/{rotaId}/posicao-atual', [TrackingController::class, 'posicaoAtual'])
        ->name('tracking.posicao-atual');

    Route::post('/tracking/rotas/{rotaId}/localizacao', [TrackingController::class, 'salvarLocalizacao'])
        ->name('tracking.salvar-localizacao');

    Route::patch('/tracking/rotas/{rotaId}/status', [TrackingController::class, 'avancarStatus'])
        ->name('tracking.avancar-status');


    // =========================================================
    // RF10 – WAYPOINTS (EDITOR DE ROTA)
    // =========================================================
    Route::get('/rotas/{rota}/waypoints', [WaypointController::class, 'index'])->name('waypoints.index');
    Route::post('/waypoints', [WaypointController::class, 'store'])->name('waypoints.store');
    Route::delete('/waypoints/{id}', [WaypointController::class, 'destroy'])->name('waypoints.destroy');
    Route::post('/waypoints/reorder', [WaypointController::class, 'reorder'])->name('waypoints.reorder');


    // =========================================================
    // SUPORTE
    // =========================================================
    Route::get('/faq', [SupportController::class, 'faq'])->name('support.faq');
    Route::get('/contato', [SupportController::class, 'create'])->name('support.contact');
    Route::post('/contato', [SupportController::class, 'store'])->name('support.contact.store');
    Route::get('/meus-chamados', [SupportController::class, 'tickets'])->name('support.tickets');


    // =========================================================
    // PEDIDOS
    // =========================================================
    Route::get('/motoristas', [MotoristaController::class, 'index'])->name('motoristas.index');

    Route::get('/rotas', [RotaController::class, 'index'])->name('rotas.index');
    Route::get('/rotas/{id}', [RotaController::class, 'show'])->name('rotas.show');
    Route::patch('/rotas/{id}', [RotaController::class, 'update'])->name('rotas.update');

    Route::get('/registration', [PedidoController::class, 'create'])->name('registration');
    Route::get('/pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
    Route::post('/pedidos', [PedidoController::class, 'store'])->name('pedidos.store');


    // =========================================================
    // RF03 – PLANOS DE ASSINATURA
    // =========================================================
    Route::get('/assinaturas', [PlanoAssinaturaController::class, 'index'])->name('assinaturas.index');
    Route::get('/minha-assinatura', [PlanoAssinaturaController::class, 'minhaAssinatura'])->name('assinaturas.minha');
    Route::get('/assinaturas/checkout/{plano}', [PlanoAssinaturaController::class, 'checkout'])->name('assinaturas.checkout');
    Route::post('/assinaturas/processar', [PlanoAssinaturaController::class, 'processar'])->name('assinaturas.processar')->middleware('throttle:10,1');
    Route::get('/assinaturas/comprovante/{pagamento}', [PlanoAssinaturaController::class, 'comprovante'])->name('assinaturas.comprovante');
    Route::post('/assinaturas/cancelar', [PlanoAssinaturaController::class, 'cancelar'])->name('assinaturas.cancelar');


    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

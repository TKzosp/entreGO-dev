<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Rate limiting: 5 tentativas falhas por minuto por (IP + email).
        // Protege contra brute force sem afetar o usuário legítimo.
        $throttleKey = $this->throttleKey($request, $credentials['email']);

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'email' => "Muitas tentativas. Tente novamente em {$seconds} segundos.",
            ]);
        }

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            // Log opcional — controlado por LOG_LEVEL no .env.
            // Em produção use LOG_LEVEL=warning para evitar I/O síncrono.
            Log::debug('Login bem-sucedido', ['user_id' => Auth::id()]);

            return redirect()->intended('/');
        }

        RateLimiter::hit($throttleKey, 60); // 60s de janela

        return back()->withErrors([
            'email' => 'Credenciais inválidas.',
        ])->withInput($request->only('email', 'remember'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Chave única por IP+email para o rate limiter.
     */
    private function throttleKey(Request $request, string $email): string
    {
        return strtolower($email) . '|' . $request->ip();
    }
}

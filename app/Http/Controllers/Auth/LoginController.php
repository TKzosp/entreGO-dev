<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // ajuste o caminho da view se necessário
    }

    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'senha' => ['required'],
    ]);

    // Tente autenticar
    if (Auth::attempt([
        'email' => $credentials['email'],
        'password' => $credentials['senha'],
    ], $request->filled('remember'))) {
        $request->session()->regenerate();
        return redirect()->intended('/teste');
    }

    // Se falhar, retorna com mensagem de erro
    return back()->withErrors([
        'email' => 'Credenciais inválidas.',
    ])->withInput();
}


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/teste');
    }
}
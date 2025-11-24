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
        return view('auth.login'); 
    }

    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'senha' => ['required'],
    ]);

    
    if (Auth::attempt([
        'email' => $credentials['email'],
        'password' => $credentials['senha'],
    ], $request->filled('remember'))) {
        $request->session()->regenerate();
        return redirect()->intended('/teste');
    }

    
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
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register'); 
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome'  => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:usuarios,email',
            'senha' => ['required', 'string', 'confirmed', Password::min(8)->letters()->numbers()],
        ], [
            'senha.min'      => 'A senha deve ter no mínimo 8 caracteres.',
            'senha.letters'  => 'A senha deve conter pelo menos uma letra.',
            'senha.numbers'  => 'A senha deve conter pelo menos um número.',
        ]);

        $usuario = Usuario::create([
            'nome'  => $request->nome,
            'email' => $request->email,
            'senha' => Hash::make($request->senha),
            'tipo'  => 'cliente',
            'ativo' => true,
        ]);

        Auth::login($usuario);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', 'Cadastro realizado com sucesso! Bem-vindo(a).');
    }
}
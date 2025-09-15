<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register'); // seu formulário de cadastro
    }

    public function store(Request $request)
    {
          $request->validate([
        'nome' => 'required|string|max:100',
        'email' => 'required|string|email|max:100|unique:usuarios,email',
        'senha' => 'required|string|min:6|confirmed',
    ]);

    $usuario = Usuario::create([
        'nome' => $request->nome,
        'email' => $request->email,
        'senha' => Hash::make($request->senha),
        'tipo' => $request->tipo ?? 'cliente',
        'ativo' => true,
    ]);

// // Login automático (opcional, remova se quiser que o usuário vá manualmente para o login)
// Auth::login($usuario);

return redirect('/login')->with('success', 'Cadastro realizado com sucesso! Faça o login.');
// dd($usuario);
    }
}
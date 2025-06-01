<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\Usuario;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'cpf' => 'required|string|unique:users',
            'tel_number' => 'required|string',
            'role' => 'required|string',
            'status' => 'nullable|string',
            'birthdate' => 'required|date',
        ]);

        $user = Usuario::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'cpf' => $request->cpf,
            'tel_number' => $request->tel_number,
            'role' => $request->role,
            'status' => $request->status ?? 'pendente',
            'birthdate' => $request->birthdate,
        ]);
        $usuario = Usuario::create([
        'nome' => $request->nome,
        'email' => $request->email,
        'senha' => Hash::make($request->senha),
        'tipo' => 'cliente',
    ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;

class DashboardController extends Controller
{
    /**
     * Página inicial pós-login.
     *
     * IMPORTANTE: até a refatoração anterior, este método executava
     * Usuario::query()->...->get() — carregando TODA a tabela em memória
     * e travando o login com tabelas grandes. Agora usa paginação.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search', ''));

        $usuarios = Usuario::query()
            // Só seleciona colunas necessárias para a listagem
            ->select(['id', 'nome', 'email', 'tipo', 'ativo'])
            // Aplica busca apenas se houver termo (>= 2 caracteres pra evitar varreduras inúteis)
            ->when(strlen($search) >= 2, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nome', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('dashboard', compact('usuarios', 'search'));
    }
}

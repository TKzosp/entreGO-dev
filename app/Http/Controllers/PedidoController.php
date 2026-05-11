<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Endereco;
use App\Models\Pedido;

class PedidoController extends Controller
{
    public function create()
    {
        return view('registration');
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            // Endereço de coleta
            'coleta_cep'         => 'required|string|max:10',
            'coleta_logradouro'  => 'required|string|max:100',
            'coleta_numero'      => 'nullable|string|max:10',
            'coleta_complemento' => 'nullable|string|max:50',
            'coleta_bairro'      => 'required|string|max:50',
            'coleta_cidade'      => 'required|string|max:50',
            'coleta_estado'      => 'required|string|size:2',

            // Endereço de entrega
            'entrega_cep'         => 'required|string|max:10',
            'entrega_logradouro'  => 'required|string|max:100',
            'entrega_numero'      => 'nullable|string|max:10',
            'entrega_complemento' => 'nullable|string|max:50',
            'entrega_bairro'      => 'required|string|max:50',
            'entrega_cidade'      => 'required|string|max:50',
            'entrega_estado'      => 'required|string|size:2',

            // Dados do pedido
            'descricao'    => 'nullable|string|max:255',
            'peso'         => 'nullable|numeric|min:0',
            'volume'       => 'nullable|numeric|min:0',
            'data_coleta'  => 'required|date|after_or_equal:today',
            'observacoes'  => 'nullable|string',
        ]);

        $usuarioId = Auth::id();

        $enderecoColeta = Endereco::create([
            'usuario_id'  => $usuarioId,
            'cep'         => $dados['coleta_cep'],
            'logradouro'  => $dados['coleta_logradouro'],
            'numero'      => $dados['coleta_numero'] ?? null,
            'complemento' => $dados['coleta_complemento'] ?? null,
            'bairro'      => $dados['coleta_bairro'],
            'cidade'      => $dados['coleta_cidade'],
            'estado'      => strtoupper($dados['coleta_estado']),
        ]);

        $enderecoEntrega = Endereco::create([
            'usuario_id'  => $usuarioId,
            'cep'         => $dados['entrega_cep'],
            'logradouro'  => $dados['entrega_logradouro'],
            'numero'      => $dados['entrega_numero'] ?? null,
            'complemento' => $dados['entrega_complemento'] ?? null,
            'bairro'      => $dados['entrega_bairro'],
            'cidade'      => $dados['entrega_cidade'],
            'estado'      => strtoupper($dados['entrega_estado']),
        ]);

        Pedido::create([
            'cliente_id'          => $usuarioId,
            'endereco_coleta_id'  => $enderecoColeta->id,
            'endereco_entrega_id' => $enderecoEntrega->id,
            'descricao'           => $dados['descricao'] ?? null,
            'peso'                => $dados['peso'] ?? null,
            'volume'              => $dados['volume'] ?? null,
            'data_coleta'         => $dados['data_coleta'],
            'status'              => 'pendente',
            'observacoes'         => $dados['observacoes'] ?? null,
        ]);

        return redirect()->route('registration')->with('success', 'Pedido cadastrado com sucesso! Aguarde a confirmação da coleta.');
    }
}

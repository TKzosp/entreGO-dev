<?php

namespace App\Http\Controllers;

use App\Models\Usuario;

class MotoristaController extends Controller
{
    public function index()
    {
        $motoristas = Usuario::with(['veiculos', 'rotasComoMotorista.pedido'])
            ->where('tipo', 'motorista')
            ->where('ativo', true)
            ->orderBy('nome')
            ->get()
            ->map(function (Usuario $motorista) {
                $rotas      = $motorista->rotasComoMotorista;
                $total      = $rotas->count();
                $entregues  = $rotas->filter(fn($r) => optional($r->pedido)->status === 'entregue')->count();
                $cancelados = $rotas->filter(fn($r) => optional($r->pedido)->status === 'cancelado')->count();
                $emAndamento = $rotas->filter(fn($r) => in_array($r->status, ['planejada', 'iniciada']))->count();
                $eficiencia = $total > 0 ? round(($entregues / $total) * 100, 1) : null;

                $veiculo = $motorista->veiculos->first();

                return [
                    'id'          => $motorista->id,
                    'nome'        => $motorista->nome,
                    'telefone'    => $motorista->telefone,
                    'veiculo'     => $veiculo?->tipo,
                    'placa'       => $veiculo?->placa,
                    'total'       => $total,
                    'entregues'   => $entregues,
                    'cancelados'  => $cancelados,
                    'em_andamento'=> $emAndamento,
                    'eficiencia'  => $eficiencia,
                ];
            });

        return view('motoristas.index', compact('motoristas'));
    }
}

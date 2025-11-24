<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Rastreamento;

class TrackingController extends Controller
{
    // Tela de tracking (front)

    // RF04 – Gerar rotas otimizadas
    public function otimizar(Request $request)
    {
        $origem = $request->input('origem');
        $destinos = $request->input('destinos', []); // lista de pontos

        if (!$origem || empty($destinos)) {
            return response()->json([
                'message' => 'Origem e pelo menos um destino são obrigatórios.'
            ], 422);
        }

        $apiKey = env('GOOGLE_MAPS_API_KEY');

        // último destino é o destino final
        $destinoFinal = end($destinos);

        $url = "https://maps.googleapis.com/maps/api/directions/json"
            . "?origin=" . urlencode($origem)
            . "&destination=" . urlencode($destinoFinal)
            . "&waypoints=optimize:true|" . implode('|', array_map('urlencode', $destinos))
            . "&key={$apiKey}";

        $response = Http::get($url)->json();

        return response()->json($response);
    }

    // RF05 – Posição atual da rota (usando dados de Rastreamento)
    public function posicaoAtual($rotaId)
    {
        $registro = Rastreamento::where('rota_id', $rotaId)
            ->latest('created_at')
            ->first();

        if (!$registro) {
            return response()->json(null, 204); // sem conteúdo
        }

        return response()->json([
            'latitude' => $registro->latitude,
            'longitude' => $registro->longitude,
            'atualizado_em' => $registro->created_at,
        ]);
    }

    public function index()
{
    // Retorna a view vazia para a tela "Geral" de geração de rotas
    return view('tracking', [
        'origem' => null,
        'destino' => null,
        'posicaoAtual' => null
    ]);
}

public function show($pedidoId)
{
    $pedido = Pedido::with('enderecoEntrega')->findOrFail($pedidoId);
    
    // Exemplo de dados para preencher o mapa
    $origem = ['lat' => -23.550520, 'lng' => -46.633308]; // Loja
    $destino = [
        'lat' => (float) $pedido->enderecoEntrega->latitude, 
        'lng' => (float) $pedido->enderecoEntrega->longitude
    ];
    
    // Simulação de posição atual (pegar da tabela 'rastreamento')
    $posicaoAtual = ['lat' => -23.559, 'lng' => -46.640];

    return view('tracking', compact('pedido', 'origem', 'destino', 'posicaoAtual'));
}
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Rastreamento;

class TrackingController extends Controller
{
    /**
     * Tela de tracking (front).
     */
    public function index()
    {
        return view('tracking', [
            'rotaId' => 1,
            'origem' => null,
            'destino' => null,
            'posicaoAtual' => null,
        ]);
    }

    /**
     * RF04 – Gerar rotas otimizadas via Google Maps Directions API.
     */
    public function otimizar(Request $request)
    {
        $origem = $request->input('origem');
        $destinos = $request->input('destinos', []);

        if (!$origem || empty($destinos)) {
            return response()->json([
                'message' => 'Origem e pelo menos um destino são obrigatórios.',
            ], 422);
        }

        $apiKey = env('GOOGLE_MAPS_API_KEY');

        if (!$apiKey) {
            Log::error('GOOGLE_MAPS_API_KEY não configurada no .env.');
            return response()->json([
                'message' => 'GOOGLE_MAPS_API_KEY não configurada no .env.',
            ], 500);
        }

        // Último destino é o destino final
        $destinoFinal = end($destinos);

        $url = "https://maps.googleapis.com/maps/api/directions/json"
            . "?origin=" . urlencode($origem)
            . "&destination=" . urlencode($destinoFinal)
            . "&waypoints=optimize:true|" . implode('|', array_map('urlencode', $destinos))
            . "&key={$apiKey}";

        $response = Http::get($url)->json();

        return response()->json($response);
    }

    /**
     * RF05 – Posição atual da rota (último registro de Rastreamento).
     */
    public function posicaoAtual($rotaId)
    {
        $registro = Rastreamento::where('rota_id', $rotaId)
            ->latest('created_at')
            ->first();

        if (!$registro) {
            return response()->json(null, 204);
        }

        return response()->json([
            'latitude' => $registro->latitude,
            'longitude' => $registro->longitude,
            'atualizado_em' => $registro->created_at,
        ]);
    }

    /**
     * Salva uma nova coordenada do motorista para a rota.
     */
    public function salvarLocalizacao(Request $request, $rotaId)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $novoRastreamento = Rastreamento::create([
            'rota_id' => $rotaId,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'data_hora' => now(),
        ]);

        return response()->json([
            'message' => 'Coordenadas registradas com sucesso.',
            'rastreamento' => $novoRastreamento,
            'previsao_entrega' => null,
        ], 201);
    }
}

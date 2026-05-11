<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Rota;
use App\Models\Rastreamento;

class TrackingController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();

        // Prioridade: rota ativa do próprio motorista logado
        $rota = null;
        if ($usuario->tipo === 'motorista') {
            $rota = Rota::with(['pedido.enderecoColeta', 'pedido.enderecoEntrega', 'veiculo'])
                ->where('motorista_id', $usuario->id)
                ->whereIn('status', ['planejada', 'iniciada'])
                ->latest()
                ->first();
        }

        // Fallback: qualquer rota ativa no sistema (para clientes e demo)
        if (!$rota) {
            $rota = Rota::with(['pedido.enderecoColeta', 'pedido.enderecoEntrega', 'veiculo', 'motorista'])
                ->whereIn('status', ['planejada', 'iniciada'])
                ->latest()
                ->first();
        }

        // Último recurso: rota mais recente independente de status
        if (!$rota) {
            $rota = Rota::with(['pedido.enderecoColeta', 'pedido.enderecoEntrega', 'veiculo', 'motorista'])
                ->latest()
                ->first();
        }

        $posicaoAtual = $rota
            ? Rastreamento::where('rota_id', $rota->id)->latest('data_hora')->first()
            : null;

        return view('tracking', [
            'rota'         => $rota,
            'rotaId'       => $rota?->id,
            'posicaoAtual' => $posicaoAtual,
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

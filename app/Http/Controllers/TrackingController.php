<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Rota;
use App\Models\Rastreamento;

class TrackingController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();
        $rota = null;

        if ($usuario->tipo === 'admin') {
            // Admin pode visualizar a rota ativa mais recente do sistema.
            $rota = Rota::with(['pedido.enderecoColeta', 'pedido.enderecoEntrega', 'veiculo', 'motorista'])
                ->whereIn('status', ['planejada', 'iniciada'])
                ->latest()
                ->first();
        } elseif ($usuario->tipo === 'motorista') {
            $rota = Rota::with(['pedido.enderecoColeta', 'pedido.enderecoEntrega', 'veiculo'])
                ->where('motorista_id', $usuario->id)
                ->whereIn('status', ['planejada', 'iniciada'])
                ->latest()
                ->first();
        } else {
            // Cliente vê a rota mais recente vinculada aos seus pedidos.
            $rota = Rota::with(['pedido.enderecoColeta', 'pedido.enderecoEntrega', 'veiculo', 'motorista'])
                ->whereHas('pedido', fn ($q) => $q->where('cliente_id', $usuario->id))
                ->whereIn('status', ['planejada', 'iniciada'])
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

        $apiKey = config('services.google.maps_key');

        if (!$apiKey) {
            Log::error('services.google.maps_key não configurada.');
            return response()->json([
                'message' => 'Servico de rotas indisponivel no momento.',
            ], 503);
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
    public function posicaoAtual(int $rotaId)
    {
        $rota = Rota::findOrFail($rotaId);
        Gate::authorize('view', $rota);

        $registro = Rastreamento::where('rota_id', $rota->id)
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
     * Avança o status da rota: planejada → iniciada → concluida.
     */
    public function avancarStatus(Request $request, int $rotaId)
    {
        $rota = Rota::with('pedido')->findOrFail($rotaId);
        Gate::authorize('track', $rota);

        $proximo = match($rota->status) {
            'planejada' => 'iniciada',
            'iniciada'  => 'concluida',
            default     => null,
        };

        if (!$proximo) {
            return response()->json(['message' => 'Rota já está concluída ou cancelada.'], 422);
        }

        $rota->status = $proximo;

        if ($proximo === 'iniciada') {
            $rota->data_inicio = now();
        } elseif ($proximo === 'concluida') {
            $rota->data_fim = now();
            optional($rota->pedido)->update(['status' => 'entregue']);
        }

        $rota->save();

        return response()->json([
            'message'    => 'Status atualizado.',
            'novo_status' => $proximo,
        ]);
    }

    /**
     * Salva uma nova coordenada do motorista para a rota.
     */
    public function salvarLocalizacao(Request $request, int $rotaId)
    {
        $rota = Rota::findOrFail($rotaId);
        Gate::authorize('track', $rota);

        $dados = $request->validate([
            'latitude'  => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);

        $novoRastreamento = Rastreamento::create([
            'rota_id'   => $rota->id,
            'latitude'  => $dados['latitude'],
            'longitude' => $dados['longitude'],
            'data_hora' => now(),
        ]);

        return response()->json([
            'message' => 'Coordenadas registradas com sucesso.',
            'rastreamento' => $novoRastreamento,
            'previsao_entrega' => null,
        ], 201);
    }
}

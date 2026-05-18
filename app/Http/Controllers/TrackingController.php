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
        $dados = $request->validate([
            'origem'      => ['required', 'string', 'max:255'],
            'destinos'    => ['required', 'array', 'min:1', 'max:25'],
            'destinos.*'  => ['required', 'string', 'max:255'],
        ], [
            'destinos.max' => 'Maximo de 25 destinos por rota.',
        ]);

        $apiKey = config('services.google.maps_key');

        if (!$apiKey) {
            Log::error('services.google.maps_key não configurada.');
            return response()->json([
                'message' => 'Servico de rotas indisponivel no momento.',
            ], 503);
        }

        $origem = $dados['origem'];
        $destinos = $dados['destinos'];
        $destinoFinal = end($destinos);

        $url = "https://maps.googleapis.com/maps/api/directions/json"
            . "?origin=" . urlencode($origem)
            . "&destination=" . urlencode($destinoFinal)
            . "&waypoints=optimize:true|" . implode('|', array_map('urlencode', $destinos))
            . "&key={$apiKey}";

        $response = Http::get($url)->json();

        // Resposta enxuta — nao repassamos status/error_message do Google nem
        // chaves internas. Repassa apenas o que a UI consome.
        if (($response['status'] ?? null) !== 'OK' || empty($response['routes'][0] ?? null)) {
            Log::warning('Directions API sem rotas', ['status' => $response['status'] ?? null]);
            return response()->json(['message' => 'Nenhuma rota encontrada.'], 422);
        }

        $rotaPrincipal = $response['routes'][0];

        return response()->json([
            'polyline'          => $rotaPrincipal['overview_polyline']['points'] ?? null,
            'bounds'            => $rotaPrincipal['bounds'] ?? null,
            'waypoint_order'    => $rotaPrincipal['waypoint_order'] ?? [],
            'legs'              => collect($rotaPrincipal['legs'] ?? [])->map(fn ($leg) => [
                'distance'      => $leg['distance'] ?? null,
                'duration'      => $leg['duration'] ?? null,
                'start_address' => $leg['start_address'] ?? null,
                'end_address'   => $leg['end_address'] ?? null,
            ])->all(),
        ]);
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

<?php

namespace App\Http\Controllers;

use App\Models\Rota;
use App\Models\Waypoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class WaypointController extends Controller
{
    public function index(int $rotaId)
    {
        $rota = Rota::findOrFail($rotaId);
        Gate::authorize('view', $rota);

        return Waypoint::where('rota_id', $rota->id)
            ->orderBy('ordem')
            ->get();
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'rota_id'   => ['required', 'integer', 'exists:rotas,id'],
            'latitude'  => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'ordem'     => ['nullable', 'integer', 'min:0'],
        ]);

        $rota = Rota::findOrFail($dados['rota_id']);
        Gate::authorize('update', $rota);

        $wp = Waypoint::create([
            'rota_id'   => $rota->id,
            'latitude'  => $dados['latitude'],
            'longitude' => $dados['longitude'],
            'ordem'     => $dados['ordem'] ?? 0,
        ]);

        return response()->json($wp, 201);
    }

    public function destroy(int $id)
    {
        $waypoint = Waypoint::findOrFail($id);
        $rota = Rota::findOrFail($waypoint->rota_id);
        Gate::authorize('update', $rota);

        $waypoint->delete();

        return response()->json(['ok' => true]);
    }

    public function reorder(Request $request)
    {
        $dados = $request->validate([
            'waypoints'   => ['required', 'array', 'min:1'],
            'waypoints.*' => ['integer', 'distinct'],
        ]);

        $ids = $dados['waypoints'];

        $rotaIds = Waypoint::whereIn('id', $ids)->pluck('rota_id')->unique();

        abort_if($rotaIds->count() !== 1, 422, 'Waypoints precisam pertencer a uma unica rota.');
        abort_if($rotaIds->count() === 1 && Waypoint::whereIn('id', $ids)->count() !== count($ids), 404);

        $rota = Rota::findOrFail($rotaIds->first());
        Gate::authorize('update', $rota);

        foreach ($ids as $index => $waypointId) {
            Waypoint::where('id', $waypointId)
                ->where('rota_id', $rota->id)
                ->update(['ordem' => $index]);
        }

        return response()->json(['ok' => true]);
    }
}

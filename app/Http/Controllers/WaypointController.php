<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Waypoint;

class WaypointController extends Controller
{
    public function index($rotaId)
    {
        return Waypoint::where('rota_id', $rotaId)
            ->orderBy('ordem')
            ->get();
    }

    public function store(Request $request)
    {
        $wp = Waypoint::create($request->all());
        return response()->json($wp);
    }

    public function destroy($id)
    {
        Waypoint::findOrFail($id)->delete();
        return response()->json(['ok' => true]);
    }

    public function reorder(Request $request)
    {
        foreach ($request->waypoints as $index => $id) {
            Waypoint::where('id', $id)->update([
                'ordem' => $index
            ]);
        }

        return response()->json(['ok' => true]);
    }
}
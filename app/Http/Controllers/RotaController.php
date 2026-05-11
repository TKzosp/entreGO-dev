<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rota;
use App\Models\Usuario;
use App\Models\Veiculo;

class RotaController extends Controller
{
    public function index(Request $request)
    {
        $status    = $request->input('status', 'todos');
        $motorista = $request->input('motorista_id', 'todos');

        $query = Rota::with(['pedido.enderecoColeta', 'pedido.enderecoEntrega', 'motorista', 'veiculo'])
            ->latest();

        if ($status !== 'todos') {
            $query->where('status', $status);
        }

        if ($motorista !== 'todos') {
            $query->where('motorista_id', $motorista);
        }

        $rotas      = $query->paginate(15)->withQueryString();
        $motoristas = Usuario::where('tipo', 'motorista')->where('ativo', true)->orderBy('nome')->get();

        return view('rotas.index', compact('rotas', 'motoristas', 'status', 'motorista'));
    }

    public function show(int $id)
    {
        $rota = Rota::with([
            'pedido.enderecoColeta',
            'pedido.enderecoEntrega',
            'pedido.cliente',
            'motorista',
            'veiculo',
            'waypoints',
            'rastreamentos',
        ])->findOrFail($id);

        $motoristas = Usuario::where('tipo', 'motorista')->where('ativo', true)->orderBy('nome')->get();

        return view('rotas.show', compact('rota', 'motoristas'));
    }

    public function update(Request $request, int $id)
    {
        $rota = Rota::with('pedido')->findOrFail($id);

        $acao = $request->input('acao');

        if ($acao === 'cancelar') {
            if (in_array($rota->status, ['concluida', 'cancelada'])) {
                return back()->with('error', 'Não é possível cancelar uma rota já concluída ou cancelada.');
            }
            $rota->status = 'cancelada';
            $rota->save();
            optional($rota->pedido)->update(['status' => 'cancelado']);

            return back()->with('success', 'Rota cancelada com sucesso.');
        }

        if ($acao === 'reatribuir') {
            if ($rota->status !== 'planejada') {
                return back()->with('error', 'Só é possível reatribuir rotas com status planejada.');
            }

            $request->validate([
                'motorista_id' => 'required|exists:usuarios,id',
                'veiculo_id'   => 'required|exists:veiculos,id',
            ]);

            $rota->motorista_id = $request->motorista_id;
            $rota->veiculo_id   = $request->veiculo_id;
            $rota->save();

            return back()->with('success', 'Rota reatribuída com sucesso.');
        }

        return back()->with('error', 'Ação inválida.');
    }
}

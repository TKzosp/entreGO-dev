<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use App\Models\Rota;
use App\Models\Usuario;

class RotaController extends Controller
{
    public function index(Request $request)
    {
        $status    = $request->input('status', 'todos');
        $motorista = $request->input('motorista_id', 'todos');

        $usuario = Auth::user();

        $query = Rota::with(['pedido.enderecoColeta', 'pedido.enderecoEntrega', 'motorista', 'veiculo'])
            ->latest();

        // Escopo por perfil: admin ve tudo, motorista as proprias, cliente as suas.
        if ($usuario->tipo === 'motorista') {
            $query->where('motorista_id', $usuario->id);
        } elseif ($usuario->tipo !== 'admin') {
            $query->whereHas('pedido', fn ($q) => $q->where('cliente_id', $usuario->id));
        }

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

        Gate::authorize('view', $rota);

        $motoristas = Usuario::where('tipo', 'motorista')->where('ativo', true)->orderBy('nome')->get();

        return view('rotas.show', compact('rota', 'motoristas'));
    }

    public function update(Request $request, int $id)
    {
        $rota = Rota::with('pedido')->findOrFail($id);
        Gate::authorize('update', $rota);

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

            $dados = $request->validate([
                'motorista_id' => [
                    'required',
                    Rule::exists('usuarios', 'id')->where(fn ($q) => $q->where('tipo', 'motorista')->where('ativo', true)),
                ],
                'veiculo_id'   => [
                    'required',
                    Rule::exists('veiculos', 'id')->where(fn ($q) => $q->where('usuario_id', $request->input('motorista_id'))),
                ],
            ], [
                'motorista_id.exists' => 'Motorista inválido ou inativo.',
                'veiculo_id.exists'   => 'Veículo inválido ou não pertence ao motorista selecionado.',
            ]);

            $rota->motorista_id = $dados['motorista_id'];
            $rota->veiculo_id   = $dados['veiculo_id'];
            $rota->save();

            return back()->with('success', 'Rota reatribuída com sucesso.');
        }

        return back()->with('error', 'Ação inválida.');
    }
}

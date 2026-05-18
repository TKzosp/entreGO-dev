<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Endereco;
use App\Models\Pedido;
use App\Models\Rota;
use App\Models\Usuario;
use App\Models\Veiculo;
use App\Support\Ufs;

class PedidoController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::with(['enderecoColeta', 'enderecoEntrega', 'rota'])
            ->where('cliente_id', Auth::id())
            ->orderByDesc('data_coleta')
            ->paginate(10);

        return view('pedidos.index', compact('pedidos'));
    }

    public function create()
    {
        return view('registration');
    }

    public function store(Request $request)
    {
        // Strip de mascara em CEPs e normalizacao de UF antes da validacao.
        $request->merge([
            'coleta_cep'     => preg_replace('/\D/', '', (string) $request->input('coleta_cep')),
            'entrega_cep'    => preg_replace('/\D/', '', (string) $request->input('entrega_cep')),
            'coleta_estado'  => strtoupper((string) $request->input('coleta_estado')),
            'entrega_estado' => strtoupper((string) $request->input('entrega_estado')),
        ]);

        $ufs = Ufs::list();

        $dados = $request->validate([
            // Endereço de coleta
            'coleta_cep'         => ['required', 'string', 'regex:/^\d{8}$/'],
            'coleta_logradouro'  => ['required', 'string', 'max:100'],
            'coleta_numero'      => ['nullable', 'string', 'max:10'],
            'coleta_complemento' => ['nullable', 'string', 'max:50'],
            'coleta_bairro'      => ['required', 'string', 'max:50'],
            'coleta_cidade'      => ['required', 'string', 'max:50'],
            'coleta_estado'      => ['required', 'string', 'size:2', Rule::in($ufs)],

            // Endereço de entrega
            'entrega_cep'         => ['required', 'string', 'regex:/^\d{8}$/'],
            'entrega_logradouro'  => ['required', 'string', 'max:100'],
            'entrega_numero'      => ['nullable', 'string', 'max:10'],
            'entrega_complemento' => ['nullable', 'string', 'max:50'],
            'entrega_bairro'      => ['required', 'string', 'max:50'],
            'entrega_cidade'      => ['required', 'string', 'max:50'],
            'entrega_estado'      => ['required', 'string', 'size:2', Rule::in($ufs)],

            // Dados do pedido
            'descricao'    => ['nullable', 'string', 'max:255'],
            'peso'         => ['nullable', 'numeric', 'min:0'],
            'volume'       => ['nullable', 'numeric', 'min:0'],
            'data_coleta'  => ['required', 'date', 'after_or_equal:today'],
            'observacoes'  => ['nullable', 'string'],
        ], [
            'coleta_cep.regex'     => 'CEP de coleta inválido. Use 8 dígitos.',
            'entrega_cep.regex'    => 'CEP de entrega inválido. Use 8 dígitos.',
            'coleta_estado.in'     => 'Estado de coleta inválido.',
            'entrega_estado.in'    => 'Estado de entrega inválido.',
        ]);

        $usuarioId = Auth::id();

        $enderecoColeta = Endereco::create([
            'usuario_id'  => $usuarioId,
            'cep'         => $dados['coleta_cep'],
            'logradouro'  => $dados['coleta_logradouro'],
            'numero'      => $dados['coleta_numero'] ?? null,
            'complemento' => $dados['coleta_complemento'] ?? null,
            'bairro'      => $dados['coleta_bairro'],
            'cidade'      => $dados['coleta_cidade'],
            'estado'      => strtoupper($dados['coleta_estado']),
        ]);

        $enderecoEntrega = Endereco::create([
            'usuario_id'  => $usuarioId,
            'cep'         => $dados['entrega_cep'],
            'logradouro'  => $dados['entrega_logradouro'],
            'numero'      => $dados['entrega_numero'] ?? null,
            'complemento' => $dados['entrega_complemento'] ?? null,
            'bairro'      => $dados['entrega_bairro'],
            'cidade'      => $dados['entrega_cidade'],
            'estado'      => strtoupper($dados['entrega_estado']),
        ]);

        $motorista = $this->selecionarMotorista();

        $pedido = Pedido::create([
            'cliente_id'          => $usuarioId,
            'endereco_coleta_id'  => $enderecoColeta->id,
            'endereco_entrega_id' => $enderecoEntrega->id,
            'descricao'           => $dados['descricao'] ?? null,
            'peso'                => $dados['peso'] ?? null,
            'volume'              => $dados['volume'] ?? null,
            'data_coleta'         => $dados['data_coleta'],
            'status'              => $motorista ? 'aceito' : 'pendente',
            'observacoes'         => $dados['observacoes'] ?? null,
        ]);

        if ($motorista) {
            $veiculo = Veiculo::where('usuario_id', $motorista->id)->first();

            Rota::create([
                'pedido_id'    => $pedido->id,
                'motorista_id' => $motorista->id,
                'veiculo_id'   => $veiculo?->id,
                'status'       => 'planejada',
                'data_inicio'  => $dados['data_coleta'],
            ]);

            $mensagem = "Pedido cadastrado! Motorista {$motorista->nome} foi designado para a coleta.";
        } else {
            $mensagem = 'Pedido cadastrado com sucesso! Nenhum motorista disponível no momento — você será notificado em breve.';
        }

        return redirect()->route('registration')->with('success', $mensagem);
    }

    private function selecionarMotorista(): ?Usuario
    {
        return Usuario::where('tipo', 'motorista')
            ->where('ativo', true)
            ->has('veiculos')
            ->withCount(['rotasComoMotorista as rotas_ativas' => fn($q) =>
                $q->whereIn('status', ['planejada', 'iniciada'])
            ])
            ->orderBy('rotas_ativas')
            ->first();
    }
}

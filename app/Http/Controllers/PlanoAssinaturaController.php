<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Assinatura;
use App\Models\Pagamento;
use App\Models\PagamentoLog;
use App\Models\Endereco;
use App\Models\Plano;
use App\Services\PagamentoSimuladoService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PlanoAssinaturaController extends Controller
{
    public function index()
    {
        $planos = Plano::where('ativo', true)->orderBy('valor')->get();

        $assinaturaAtual = Assinatura::with('plano')
            ->where('usuario_id', Auth::id())
            ->where('status', 'ativa')
            ->latest('id')
            ->first();

        return view('assinaturas.index', compact('planos', 'assinaturaAtual'));
    }

    public function minhaAssinatura()
    {
        $assinatura = Assinatura::with('plano')
            ->where('usuario_id', Auth::id())
            ->where('status', 'ativa')
            ->latest('id')
            ->first();

        $pagamentos = Pagamento::with('plano')
            ->where('usuario_id', Auth::id())
            ->where('status', 'aprovado')
            ->latest()
            ->limit(10)
            ->get();

        return view('assinaturas.minha', compact('assinatura', 'pagamentos'));
    }

    public function checkout(Plano $plano)
    {
        abort_if(!$plano->ativo, 404);

        $usuario = Auth::user();

        // Já tem este plano ativo
        $assinaturaAtual = Assinatura::where('usuario_id', $usuario->id)
            ->where('status', 'ativa')
            ->where('plano_id', $plano->id)
            ->latest('id')
            ->first();

        if ($assinaturaAtual) {
            return redirect()->route('assinaturas.minha')
                ->with('info', 'Você já possui o plano ' . $plano->nome . ' ativo.');
        }

        $enderecoFaturamento = $usuario->enderecoFaturamento;

        return view('assinaturas.checkout', compact('plano', 'usuario', 'enderecoFaturamento'));
    }

    public function processar(CheckoutRequest $request, PagamentoSimuladoService $servico)
    {
        $usuario = Auth::user();

        // Busca o plano do banco — nunca confia no valor do form
        $plano = Plano::where('id', $request->plano_id)
            ->where('ativo', true)
            ->firstOrFail();

        // Grava log ANTES da transaction para garantir evidência mesmo em falha
        $logIniciado = [
            'plano_id'   => $plano->id,
            'plano_nome' => $plano->nome,
            'valor'      => $plano->valor,
            'usuario_id' => $usuario->id,
        ];

        DB::transaction(function () use ($request, $usuario, $plano, $servico, $logIniciado) {

            // Proteção contra double-submit com lock pessimista
            $pendente = Pagamento::where('usuario_id', $usuario->id)
                ->where('status', 'pendente')
                ->lockForUpdate()
                ->exists();

            abort_if($pendente, 409, 'Existe um pagamento em processamento. Aguarde.');

            // Salva/atualiza CPF no perfil
            $cpfLimpo = preg_replace('/\D/', '', $request->cpf_cnpj);
            $usuario->cpf_cnpj = $cpfLimpo;
            $usuario->save();

            // Salva/atualiza endereço de faturamento dedicado (sem clobberar
            // enderecos de coleta/entrega vinculados a pedidos).
            $payloadEndereco = [
                'usuario_id'  => $usuario->id,
                'cep'         => preg_replace('/\D/', '', $request->cep),
                'logradouro'  => $request->logradouro,
                'numero'      => $request->numero,
                'complemento' => $request->complemento,
                'bairro'      => $request->bairro,
                'cidade'      => $request->cidade,
                'estado'      => strtoupper($request->estado),
            ];

            if ($usuario->endereco_faturamento_id) {
                Endereco::where('id', $usuario->endereco_faturamento_id)->update($payloadEndereco);
            } else {
                $enderecoFaturamento = Endereco::create($payloadEndereco);
                $usuario->endereco_faturamento_id = $enderecoFaturamento->id;
                $usuario->save();
            }

            // Cria o registro de pagamento (status: pendente)
            $pagamento = Pagamento::create([
                'usuario_id' => $usuario->id,
                'plano_id'   => $plano->id,
                'valor'      => $plano->valor, // snapshot do valor atual
                'status'     => 'pendente',
                'metodo'     => 'simulado',
            ]);

            PagamentoLog::registrar($pagamento, 'iniciado', $logIniciado, request()->ip(), request()->userAgent());
            PagamentoLog::registrar($pagamento, 'processando', ['metodo' => 'simulado'], request()->ip(), request()->userAgent());

            // Chama o serviço de simulação
            $resultado = $servico->processar($pagamento);

            if ($resultado['status'] === 'aprovado') {
                $pagamento->update([
                    'status'             => 'aprovado',
                    'referencia_externa' => $resultado['referencia_externa'],
                ]);

                PagamentoLog::registrar($pagamento, 'aprovado', [
                    'referencia_externa' => $resultado['referencia_externa'],
                    'mensagem'           => $resultado['mensagem'],
                ], request()->ip(), request()->userAgent());

                // Cancela assinatura(s) ativa(s) existente(s)
                Assinatura::where('usuario_id', $usuario->id)
                    ->where('status', 'ativa')
                    ->update([
                        'status'              => 'cancelada',
                        'data_fim'            => now(),
                        'renovacao_automatica' => false,
                    ]);

                // Cria nova assinatura com prazo de 30 dias
                $assinatura = Assinatura::create([
                    'usuario_id'          => $usuario->id,
                    'plano_id'            => $plano->id,
                    'status'              => 'ativa',
                    'data_inicio'         => now(),
                    'data_fim'            => now()->addDays(30),
                    'renovacao_automatica' => true,
                ]);

                // Vincula o pagamento à assinatura criada
                $pagamento->update(['assinatura_id' => $assinatura->id]);

                // Armazena o id do pagamento aprovado para redirecionar após o commit
                session(['pagamento_aprovado_id' => $pagamento->id]);

            } else {
                $pagamento->update(['status' => 'recusado']);

                PagamentoLog::registrar($pagamento, 'recusado', [
                    'mensagem' => $resultado['mensagem'] ?? 'Pagamento recusado.',
                ], request()->ip(), request()->userAgent());

                session(['pagamento_recusado' => true]);
            }
        });

        if (session()->pull('pagamento_aprovado_id')) {
            $pagamentoId = Pagamento::where('usuario_id', Auth::id())
                ->where('status', 'aprovado')
                ->latest()
                ->value('id');

            return redirect()->route('assinaturas.comprovante', $pagamentoId);
        }

        return redirect()->route('assinaturas.checkout', $request->plano_id)
            ->withErrors(['pagamento' => 'Pagamento recusado. Tente novamente.']);
    }

    public function comprovante(Pagamento $pagamento)
    {
        // Garante que o comprovante pertence ao usuário autenticado
        abort_if($pagamento->usuario_id !== Auth::id(), 403);
        abort_if($pagamento->status !== 'aprovado', 404);

        $pagamento->load(['plano', 'assinatura']);
        $usuario = Auth::user();
        $enderecoFaturamento = $usuario->enderecoFaturamento;

        return view('assinaturas.comprovante', compact('pagamento', 'usuario', 'enderecoFaturamento'));
    }

    public function cancelar()
    {
        $assinatura = Assinatura::where('usuario_id', Auth::id())
            ->where('status', 'ativa')
            ->latest('id')
            ->first();

        if ($assinatura) {
            $assinatura->update([
                'status'              => 'cancelada',
                'data_fim'            => now(),
                'renovacao_automatica' => false,
            ]);

            // Cancela pagamentos pendentes vinculados ao usuário
            Pagamento::where('usuario_id', Auth::id())
                ->where('status', 'pendente')
                ->update(['status' => 'recusado']);
        }

        return redirect()->route('assinaturas.minha')
            ->with('success', 'Assinatura cancelada com sucesso.');
    }
}

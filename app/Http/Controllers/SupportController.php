<?php

namespace App\Http\Controllers;

use App\Models\Chamado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    public function faq()
    {
        $faqs = [
            [
                'pergunta' => 'Como faço para acompanhar uma rota no sistema?',
                'resposta' => 'Acesse a área de Tracking no menu principal. Nessa tela você pode iniciar o rastreamento e visualizar a posição em tempo real.'
            ],
            [
                'pergunta' => 'Como cadastro um pedido?',
                'resposta' => 'Acesse a área de cadastro correspondente no sistema e preencha os dados obrigatórios do pedido, incluindo origem, destino e informações do cliente.'
            ],
            [
                'pergunta' => 'Quem pode abrir chamados?',
                'resposta' => 'Usuários autenticados no sistema podem abrir chamados técnicos ou comerciais diretamente pela página de contato.'
            ],
            [
                'pergunta' => 'Como sei se meu chamado foi registrado?',
                'resposta' => 'Após o envio, o sistema exibirá uma mensagem de sucesso e o chamado ficará visível em "Meus Chamados".'
            ],
            [
                'pergunta' => 'Qual a diferença entre chamado técnico e comercial?',
                'resposta' => 'Chamados técnicos servem para relatar erros, falhas e problemas no sistema. Chamados comerciais servem para dúvidas sobre serviços, atendimento ou negociação.'
            ],
        ];

        return view('support.faq', compact('faqs'));
    }

    public function create()
    {
        $usuario = Auth::user();

        return view('support.contact', compact('usuario'));
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'assunto'    => ['required', 'string', 'max:150'],
            'categoria'  => ['required', 'in:assistencia_tecnica,comercial'],
            'prioridade' => ['required', 'in:baixa,media,alta'],
            'mensagem'   => ['required', 'string', 'min:10'],
        ]);

        $usuario = Auth::user();

        Chamado::create([
            'usuario_id' => $usuario->id,
            'nome'       => $usuario->nome,
            'email'      => $usuario->email,
            'telefone'   => $usuario->telefone,
            'assunto'    => $dados['assunto'],
            'categoria'  => $dados['categoria'],
            'prioridade' => $dados['prioridade'],
            'mensagem'   => $dados['mensagem'],
            'status'     => 'aberto',
        ]);

        return redirect()
            ->route('support.tickets')
            ->with('success', 'Chamado aberto com sucesso.');
    }

    public function tickets()
    {
        $chamados = Chamado::where('usuario_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        return view('support.tickets', compact('chamados'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Plano;
use App\Models\Assinatura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            ->latest('id')
            ->first();

        return view('assinaturas.minha', compact('assinatura'));
    }

    public function assinar(Request $request)
    {
        $request->validate([
            'plano_id' => 'required|exists:planos,id',
        ]);

        $usuario = Auth::user();

        Assinatura::where('usuario_id', $usuario->id)
            ->where('status', 'ativa')
            ->update([
                'status' => 'cancelada',
                'data_fim' => now(),
                'renovacao_automatica' => false,
            ]);

        Assinatura::create([
            'usuario_id' => $usuario->id,
            'plano_id' => $request->plano_id,
            'status' => 'ativa',
            'data_inicio' => now(),
            'renovacao_automatica' => true,
        ]);

        return redirect()->route('assinaturas.minha')
            ->with('success', 'Plano assinado com sucesso.');
    }

    public function cancelar()
    {
        $assinatura = Assinatura::where('usuario_id', Auth::id())
            ->where('status', 'ativa')
            ->latest('id')
            ->first();

        if ($assinatura) {
            $assinatura->update([
                'status' => 'cancelada',
                'data_fim' => now(),
                'renovacao_automatica' => false,
            ]);
        }

        return redirect()->route('assinaturas.minha')
            ->with('success', 'Assinatura cancelada com sucesso.');
    }
}
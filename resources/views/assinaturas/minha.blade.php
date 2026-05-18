@extends('layouts.app')

@section('title', 'Minha Assinatura')

@section('content')
<div class="container mx-auto px-4 sm:px-6 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">Minha assinatura</h1>
            <p class="text-slate-500 mt-2">
                Visualize e gerencie o plano vinculado à sua conta.
            </p>
        </div>

        @if(session('success'))
            <div role="status" class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if($assinatura)
            @php
                $beneficios = $assinatura->plano->beneficios;
                if (is_string($beneficios)) {
                    $beneficios = json_decode($beneficios, true);
                }

                $statusBadge = match($assinatura->status) {
                    'ativa'     => 'bg-emerald-100 text-emerald-800 ring-emerald-200',
                    'cancelada' => 'bg-slate-200 text-slate-700 ring-slate-300',
                    'expirada'  => 'bg-red-100 text-red-800 ring-red-200',
                    default     => 'bg-slate-100 text-slate-700 ring-slate-200',
                };
            @endphp

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                        <div>
                            <h2 class="text-xl font-semibold text-slate-900">
                                {{ $assinatura->plano->nome }}
                            </h2>
                            <p class="mt-1 text-slate-600">
                                {{ $assinatura->plano->descricao }}
                            </p>
                        </div>

                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ring-1 ring-inset {{ $statusBadge }} self-start">
                            {{ ucfirst($assinatura->status) }}
                        </span>
                    </div>

                    <dl class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div class="bg-slate-50 rounded-lg p-3">
                            <dt class="text-xs font-medium text-slate-500 uppercase tracking-wider">Valor mensal</dt>
                            <dd class="mt-1 text-lg font-semibold text-entrego-blue">
                                R$ {{ number_format($assinatura->plano->valor, 2, ',', '.') }}
                            </dd>
                        </div>
                        <div class="bg-slate-50 rounded-lg p-3">
                            <dt class="text-xs font-medium text-slate-500 uppercase tracking-wider">Início</dt>
                            <dd class="mt-1 text-slate-700">
                                {{ \Carbon\Carbon::parse($assinatura->data_inicio)->format('d/m/Y') }}
                            </dd>
                        </div>
                        <div class="bg-slate-50 rounded-lg p-3 sm:col-span-2">
                            <dt class="text-xs font-medium text-slate-500 uppercase tracking-wider">Renovação automática</dt>
                            <dd class="mt-1 text-slate-700 flex items-center gap-2">
                                @if($assinatura->renovacao_automatica)
                                    <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Ativada
                                @else
                                    <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    Desativada
                                @endif
                            </dd>
                        </div>
                    </dl>

                    @if(!empty($beneficios) && is_array($beneficios))
                        <div class="mt-6 pt-6 border-t border-slate-100">
                            <h3 class="font-semibold text-slate-900 mb-3">Benefícios do plano</h3>
                            <ul class="space-y-2">
                                @foreach($beneficios as $beneficio)
                                    <li class="text-sm text-slate-700 flex items-start gap-2">
                                        <svg class="w-4 h-4 text-emerald-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>{{ $beneficio }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                @if($assinatura->status === 'ativa')
                    <div class="bg-slate-50 border-t border-slate-100 px-6 py-4 flex flex-col sm:flex-row gap-3 sm:justify-end"
                         x-data="{ confirmarCancel: false }">
                        <a href="{{ route('assinaturas.index') }}"
                           class="inline-flex items-center justify-center min-h-[44px] rounded-lg border border-slate-300 px-4 py-2 text-slate-700 font-medium hover:bg-white focus:outline-none focus:ring-2 focus:ring-entrego-blue focus:ring-offset-2 transition-colors">
                            Trocar plano
                        </a>
                        {{-- "Trocar plano" leva ao index, que então redireciona para checkout --}}

                        {{-- Botão que abre confirmação --}}
                        <button type="button" @click="confirmarCancel = true"
                                class="inline-flex items-center justify-center min-h-[44px] rounded-lg bg-red-600 text-white px-4 py-2 font-medium hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2 transition-colors">
                            Cancelar assinatura
                        </button>

                        {{-- Modal de confirmação --}}
                        <div x-show="confirmarCancel" x-cloak
                             x-transition.opacity
                             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50"
                             @keydown.escape.window="confirmarCancel = false"
                             role="dialog" aria-modal="true" aria-labelledby="cancelar-titulo">
                            <div @click.outside="confirmarCancel = false"
                                 class="bg-white rounded-xl shadow-lg max-w-md w-full p-6">
                                <h3 id="cancelar-titulo" class="text-lg font-semibold text-slate-900">Cancelar assinatura?</h3>
                                <p class="mt-2 text-sm text-slate-600">
                                    Você perderá o acesso aos benefícios do plano <strong>{{ $assinatura->plano->nome }}</strong>.
                                    Esta ação não pode ser desfeita.
                                </p>
                                <div class="mt-5 flex flex-col-reverse sm:flex-row sm:justify-end gap-2">
                                    <button type="button" @click="confirmarCancel = false"
                                            class="inline-flex items-center justify-center min-h-[44px] rounded-lg border border-slate-300 px-4 py-2 text-slate-700 font-medium hover:bg-slate-50 transition-colors">
                                        Manter assinatura
                                    </button>
                                    <form action="{{ route('assinaturas.cancelar') }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="w-full sm:w-auto inline-flex items-center justify-center min-h-[44px] rounded-lg bg-red-600 text-white px-4 py-2 font-medium hover:bg-red-700 transition-colors">
                                            Sim, cancelar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @else
            {{-- Estado vazio --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-10 text-center">
                <svg class="mx-auto w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-lg font-medium text-slate-900">Nenhuma assinatura ativa</h3>
                <p class="mt-1 text-sm text-slate-500">Escolha um plano e desbloqueie todos os recursos.</p>
                <a href="{{ route('assinaturas.index') }}"
                   class="inline-flex items-center justify-center mt-5 min-h-[44px] rounded-lg bg-entrego-blue text-white px-5 py-2.5 font-medium hover:bg-entrego-blue-600 focus:outline-none focus:ring-2 focus:ring-entrego-blue focus:ring-offset-2 transition-colors">
                    Ver planos disponíveis
                </a>
            </div>
        @endif

        {{-- Histórico de pagamentos --}}
        @if(isset($pagamentos) && $pagamentos->isNotEmpty())
            <div class="mt-8">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Histórico de pagamentos</h2>
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <table class="min-w-full divide-y divide-slate-100 text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Referência</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Plano</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Valor</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Data</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Comprovante</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($pagamentos as $pagamento)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-4 py-3 font-mono text-xs text-slate-400">{{ $pagamento->referencia_externa }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ $pagamento->plano->nome }}</td>
                                    <td class="px-4 py-3 text-slate-700">R$ {{ number_format($pagamento->valor, 2, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-slate-600 whitespace-nowrap">{{ $pagamento->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-4 py-3">
                                        <a href="{{ route('assinaturas.comprovante', $pagamento->id) }}"
                                           class="text-entrego-blue hover:underline text-xs font-medium">
                                            Ver
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Comprovante de Pagamento')

@section('content')
<div class="container mx-auto px-4 sm:px-6 py-8">
    <div class="max-w-2xl mx-auto">

        {{-- Header de sucesso --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-emerald-100 mb-4">
                <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-slate-900">Assinatura confirmada!</h1>
            <p class="mt-1 text-slate-500 text-sm">Seu pagamento foi processado e seu plano já está ativo.</p>
        </div>

        {{-- Card do comprovante --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden" id="comprovante">

            {{-- Cabeçalho --}}
            <div class="bg-entrego-blue px-6 py-4">
                <div class="flex items-center justify-between">
                    <span class="text-white font-semibold text-sm">Comprovante de pagamento</span>
                    <span class="inline-flex items-center rounded-full bg-emerald-400/20 text-emerald-100 text-xs font-medium px-2.5 py-0.5 ring-1 ring-emerald-400/30">
                        Aprovado
                    </span>
                </div>
                <p class="text-blue-200 text-xs mt-1">Referência: {{ $pagamento->referencia_externa }}</p>
            </div>

            {{-- Detalhes da transação --}}
            <div class="p-6 space-y-5">

                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Data</dt>
                        <dd class="mt-1 text-slate-800 font-medium">
                            {{ $pagamento->created_at->format('d/m/Y \à\s H:i') }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Método</dt>
                        <dd class="mt-1 text-slate-800 font-medium capitalize">{{ $pagamento->metodo }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Plano</dt>
                        <dd class="mt-1 text-slate-800 font-medium">{{ $pagamento->plano->nome }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Vigência</dt>
                        <dd class="mt-1 text-slate-800 font-medium">
                            @if($pagamento->assinatura)
                                até {{ $pagamento->assinatura->data_fim?->format('d/m/Y') ?? '—' }}
                            @else
                                —
                            @endif
                        </dd>
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-4 flex justify-between items-center">
                    <span class="text-sm font-semibold text-slate-700">Valor cobrado</span>
                    <span class="text-xl font-bold text-entrego-blue">
                        R$ {{ number_format($pagamento->valor, 2, ',', '.') }}
                    </span>
                </div>

                {{-- Dados do titular --}}
                <div class="border-t border-slate-100 pt-4 space-y-2">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Dados do titular</p>
                    <div class="text-sm text-slate-700 space-y-0.5">
                        <p>{{ $usuario->nome }}</p>
                        <p>{{ $usuario->email }}</p>
                        @if($usuario->cpf_cnpj)
                            <p>CPF/CNPJ: {{ $usuario->cpf_cnpj }}</p>
                        @endif
                        @if($enderecoFaturamento)
                            <p class="text-slate-500 text-xs mt-1">
                                {{ $enderecoFaturamento->logradouro }}, {{ $enderecoFaturamento->numero ?? 's/n' }}
                                @if($enderecoFaturamento->complemento) — {{ $enderecoFaturamento->complemento }}@endif
                                <br>
                                {{ $enderecoFaturamento->bairro }}, {{ $enderecoFaturamento->cidade }}/{{ $enderecoFaturamento->estado }}
                                — CEP {{ $enderecoFaturamento->cep }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Ações --}}
        <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center">
            <button onclick="window.print()"
                    class="inline-flex items-center justify-center gap-2 min-h-[44px] rounded-lg border border-slate-300 px-5 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a1 1 0 001-1v-5H8v5a1 1 0 001 1zm1-9V4a1 1 0 00-1-1H9a1 1 0 00-1 1v4h8z"/>
                </svg>
                Imprimir comprovante
            </button>
            <a href="{{ route('assinaturas.minha') }}"
               class="inline-flex items-center justify-center min-h-[44px] rounded-lg bg-entrego-blue text-white px-5 py-2 text-sm font-medium hover:bg-entrego-blue-600 transition-colors">
                Ver minha assinatura
            </a>
        </div>

    </div>
</div>
@endsection

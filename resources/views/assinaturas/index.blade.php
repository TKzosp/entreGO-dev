@extends('layouts.app')

@section('title', 'Planos de Assinatura')

@section('content')
<div class="container mx-auto px-4 sm:px-6 py-8">
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">Planos de assinatura</h1>
        <p class="text-slate-500 mt-2">
            Escolha o pacote ideal para gerenciar seus benefícios na plataforma.
        </p>
    </div>

    @if(session('success'))
        <div role="status" class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($assinaturaAtual)
        <div class="mb-6 rounded-xl border border-blue-100 bg-blue-50 p-4">
            <p class="text-sm text-blue-900">
                Seu plano atual: <strong>{{ $assinaturaAtual->plano->nome }}</strong>
                — R$ {{ number_format($assinaturaAtual->plano->valor, 2, ',', '.') }}
            </p>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach($planos as $plano)
            @php
                $beneficios = $plano->beneficios;
                if (is_string($beneficios)) {
                    $beneficios = json_decode($beneficios, true);
                }
                $isAtual = $assinaturaAtual && $assinaturaAtual->plano_id === $plano->id;
            @endphp

            <div @class([
                'bg-white rounded-xl shadow-sm p-6 flex flex-col justify-between transition-shadow hover:shadow-md',
                'border-2 border-entrego-blue ring-1 ring-entrego-blue' => $isAtual,
                'border border-slate-200' => !$isAtual,
            ])>
                <div>
                    <div class="flex items-start justify-between gap-2">
                        <h2 class="text-xl font-semibold text-slate-900">{{ $plano->nome }}</h2>
                        @if($isAtual)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-entrego-blue text-white">
                                Plano atual
                            </span>
                        @endif
                    </div>

                    <p class="mt-2 text-slate-500">{{ $plano->descricao }}</p>

                    <div class="mt-4">
                        <span class="text-3xl font-bold text-entrego-blue">
                            R$ {{ number_format($plano->valor, 2, ',', '.') }}
                        </span>
                        <span class="text-slate-500">/mês</span>
                    </div>

                    @if(!empty($beneficios) && is_array($beneficios))
                        <ul class="mt-6 space-y-2">
                            @foreach($beneficios as $beneficio)
                                <li class="text-sm text-slate-700 flex items-start gap-2">
                                    <svg class="w-4 h-4 text-emerald-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>{{ $beneficio }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div class="mt-6">
                    @if($isAtual)
                        <button type="button" disabled
                                class="w-full rounded-lg bg-slate-100 text-slate-400 py-3 font-medium cursor-not-allowed">
                            Você já tem este plano
                        </button>
                    @else
                        <a href="{{ route('assinaturas.checkout', $plano->id) }}"
                           class="w-full inline-flex items-center justify-center min-h-[44px] rounded-lg bg-entrego-blue text-white py-3 font-medium hover:bg-entrego-blue-600 focus:outline-none focus:ring-2 focus:ring-entrego-blue focus:ring-offset-2 transition-colors">
                            {{ $assinaturaAtual ? 'Trocar para este plano' : 'Escolher plano' }}
                        </a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

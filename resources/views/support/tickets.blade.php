@extends('layouts.app')

@section('title', 'Meus Chamados')

@section('content')
@php
    // Helpers de exibição – encapsulados aqui pra não poluir o controller.
    $categoriaLabel = fn($c) => match($c) {
        'assistencia_tecnica' => 'Assistência técnica',
        'comercial'           => 'Comercial',
        default               => ucfirst(str_replace('_', ' ', $c ?? '')),
    };

    $prioridadeBadge = fn($p) => match($p) {
        'alta'  => 'bg-red-100 text-red-800 ring-red-200',
        'media' => 'bg-amber-100 text-amber-800 ring-amber-200',
        'baixa' => 'bg-slate-100 text-slate-700 ring-slate-200',
        default => 'bg-slate-100 text-slate-700 ring-slate-200',
    };

    $statusBadge = fn($s) => match($s) {
        'aberto'      => 'bg-blue-100 text-blue-800 ring-blue-200',
        'em_andamento'=> 'bg-amber-100 text-amber-800 ring-amber-200',
        'resolvido'   => 'bg-emerald-100 text-emerald-800 ring-emerald-200',
        'fechado'     => 'bg-slate-200 text-slate-700 ring-slate-300',
        default       => 'bg-slate-100 text-slate-700 ring-slate-200',
    };
@endphp

<div class="container mx-auto px-4 sm:px-6 py-8">
    <div class="max-w-6xl mx-auto">
        {{-- Cabeçalho --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">Meus Chamados</h1>
                <p class="text-slate-500 mt-1">Acompanhe os chamados abertos no sistema.</p>
            </div>

            <a href="{{ route('support.contact') }}"
               class="inline-flex items-center justify-center gap-2 min-h-[44px] rounded-lg bg-entrego-blue px-4 py-2.5 text-white font-medium hover:bg-entrego-blue-600 focus:outline-none focus:ring-2 focus:ring-entrego-blue focus:ring-offset-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Novo chamado
            </a>
        </div>

        @if (session('success'))
            <div role="status" class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if ($chamados->isEmpty())
            {{-- Estado vazio --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-10 text-center">
                <svg class="mx-auto w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <h3 class="text-lg font-medium text-slate-900">Nenhum chamado ainda</h3>
                <p class="mt-1 text-sm text-slate-500">Quando você abrir um chamado, ele aparecerá aqui.</p>
                <a href="{{ route('support.contact') }}"
                   class="inline-flex items-center mt-4 text-sm font-medium text-entrego-blue hover:underline">
                    Abrir primeiro chamado →
                </a>
            </div>
        @else
            {{-- ============================================ --}}
            {{-- Mobile: cards (até md)                       --}}
            {{-- ============================================ --}}
            <div class="md:hidden space-y-3">
                @foreach ($chamados as $chamado)
                    <article class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0 flex-1">
                                <p class="text-xs text-slate-500">#{{ $chamado->id }}</p>
                                <h3 class="text-base font-semibold text-slate-900 truncate">
                                    {{ $chamado->assunto }}
                                </h3>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ring-1 ring-inset {{ $statusBadge($chamado->status) }}">
                                {{ ucfirst(str_replace('_', ' ', $chamado->status)) }}
                            </span>
                        </div>

                        <dl class="mt-3 grid grid-cols-2 gap-y-2 gap-x-4 text-sm">
                            <div>
                                <dt class="text-xs text-slate-500">Categoria</dt>
                                <dd class="text-slate-700">{{ $categoriaLabel($chamado->categoria) }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-slate-500">Prioridade</dt>
                                <dd>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ring-1 ring-inset {{ $prioridadeBadge($chamado->prioridade) }}">
                                        {{ ucfirst($chamado->prioridade) }}
                                    </span>
                                </dd>
                            </div>
                            <div class="col-span-2">
                                <dt class="text-xs text-slate-500">Aberto em</dt>
                                <dd class="text-slate-700">{{ $chamado->created_at?->format('d/m/Y H:i') }}</dd>
                            </div>
                        </dl>
                    </article>
                @endforeach
            </div>

            {{-- ============================================ --}}
            {{-- Desktop: tabela (a partir de md)             --}}
            {{-- ============================================ --}}
            <div class="hidden md:block bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left font-semibold text-slate-700">ID</th>
                            <th scope="col" class="px-4 py-3 text-left font-semibold text-slate-700">Assunto</th>
                            <th scope="col" class="px-4 py-3 text-left font-semibold text-slate-700">Categoria</th>
                            <th scope="col" class="px-4 py-3 text-left font-semibold text-slate-700">Prioridade</th>
                            <th scope="col" class="px-4 py-3 text-left font-semibold text-slate-700">Status</th>
                            <th scope="col" class="px-4 py-3 text-left font-semibold text-slate-700">Aberto em</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($chamados as $chamado)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 text-slate-500 font-mono text-xs">#{{ $chamado->id }}</td>
                                <td class="px-4 py-3 text-slate-900 font-medium">{{ $chamado->assunto }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $categoriaLabel($chamado->categoria) }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ring-1 ring-inset {{ $prioridadeBadge($chamado->prioridade) }}">
                                        {{ ucfirst($chamado->prioridade) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ring-1 ring-inset {{ $statusBadge($chamado->status) }}">
                                        {{ ucfirst(str_replace('_', ' ', $chamado->status)) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-slate-600 whitespace-nowrap">
                                    {{ $chamado->created_at?->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Paginação (se vier paginado do controller) --}}
            @if (method_exists($chamados, 'links'))
                <div class="mt-4">{{ $chamados->links() }}</div>
            @endif
        @endif
    </div>
</div>
@endsection

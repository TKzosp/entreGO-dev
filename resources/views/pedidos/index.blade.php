@extends('layouts.app')

@section('title', 'Meus Pedidos')

@section('content')
<div class="container mx-auto p-6 space-y-6">
    <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Meus Pedidos</h1>
            <p class="mt-1 text-sm text-slate-500">Histórico completo de pedidos e status de entrega.</p>
        </div>
        <a href="{{ route('registration') }}"
           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition">
            + Novo Pedido
        </a>
    </header>

    @if($pedidos->isEmpty())
        <div class="bg-amber-50 border border-amber-200 rounded-lg p-6 text-center text-amber-800">
            <p class="font-medium">Nenhum pedido encontrado.</p>
            <p class="text-sm mt-1">Crie seu primeiro pedido clicando em "Novo Pedido".</p>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">#</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Descrição</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Coleta</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Entrega</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Data Coleta</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($pedidos as $pedido)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 text-slate-400 font-mono text-xs">{{ $pedido->id }}</td>
                        <td class="px-6 py-4 text-slate-700 max-w-xs truncate">
                            {{ $pedido->descricao ?: '—' }}
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            @if($pedido->enderecoColeta)
                                {{ $pedido->enderecoColeta->logradouro }}, {{ $pedido->enderecoColeta->numero ?? 's/n' }}<br>
                                <span class="text-xs text-slate-400">{{ $pedido->enderecoColeta->cidade }}/{{ $pedido->enderecoColeta->estado }}</span>
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            @if($pedido->enderecoEntrega)
                                {{ $pedido->enderecoEntrega->logradouro }}, {{ $pedido->enderecoEntrega->numero ?? 's/n' }}<br>
                                <span class="text-xs text-slate-400">{{ $pedido->enderecoEntrega->cidade }}/{{ $pedido->enderecoEntrega->estado }}</span>
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-600 whitespace-nowrap">
                            {{ $pedido->data_coleta?->format('d/m/Y') ?? '—' }}
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $badgeClass = match($pedido->status) {
                                    'entregue'  => 'bg-green-100 text-green-700',
                                    'transito'  => 'bg-blue-100 text-blue-700',
                                    'coletado'  => 'bg-indigo-100 text-indigo-700',
                                    'aceito'    => 'bg-yellow-100 text-yellow-700',
                                    'cancelado' => 'bg-red-100 text-red-700',
                                    default     => 'bg-gray-100 text-gray-600',
                                };
                                $statusLabel = match($pedido->status) {
                                    'entregue'  => 'Entregue',
                                    'transito'  => 'Em trânsito',
                                    'coletado'  => 'Coletado',
                                    'aceito'    => 'Aceito',
                                    'cancelado' => 'Cancelado',
                                    default     => 'Pendente',
                                };
                            @endphp
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $badgeClass }}">
                                {{ $statusLabel }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($pedidos->hasPages())
            <div class="flex justify-center">
                {{ $pedidos->links() }}
            </div>
        @endif
    @endif
</div>
@endsection

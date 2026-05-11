@extends('layouts.app')

@section('title', 'Gestão de Rotas')

@section('content')
<div class="container mx-auto p-6 space-y-6">

    <header>
        <h1 class="text-2xl font-semibold text-slate-900">Gestão de Rotas</h1>
        <p class="mt-1 text-sm text-slate-500">Visualize, filtre e gerencie todas as rotas do sistema.</p>
    </header>

    {{-- Filtros --}}
    <form method="GET" action="{{ route('rotas.index') }}" class="flex flex-wrap gap-3 items-end">
        <div class="flex flex-col">
            <label class="text-xs font-medium text-slate-500 mb-1">Status</label>
            <select name="status" onchange="this.form.submit()"
                    class="rounded-lg border-slate-200 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="todos"     {{ $status === 'todos'     ? 'selected' : '' }}>Todos</option>
                <option value="planejada" {{ $status === 'planejada' ? 'selected' : '' }}>Planejada</option>
                <option value="iniciada"  {{ $status === 'iniciada'  ? 'selected' : '' }}>Iniciada</option>
                <option value="concluida" {{ $status === 'concluida' ? 'selected' : '' }}>Concluída</option>
                <option value="cancelada" {{ $status === 'cancelada' ? 'selected' : '' }}>Cancelada</option>
            </select>
        </div>
        <div class="flex flex-col">
            <label class="text-xs font-medium text-slate-500 mb-1">Motorista</label>
            <select name="motorista_id" onchange="this.form.submit()"
                    class="rounded-lg border-slate-200 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="todos">Todos</option>
                @foreach($motoristas as $m)
                    <option value="{{ $m->id }}" {{ $motorista == $m->id ? 'selected' : '' }}>
                        {{ $m->nome }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg px-4 py-3 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-lg px-4 py-3 text-sm text-red-800">
            {{ session('error') }}
        </div>
    @endif

    @if($rotas->isEmpty())
        <div class="bg-amber-50 border border-amber-200 rounded-lg p-6 text-center text-amber-800">
            <p class="font-medium">Nenhuma rota encontrada para os filtros selecionados.</p>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase">#</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Pedido</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Motorista</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Veículo</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Início</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Status</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($rotas as $rota)
                    @php
                        $badgeClass = match($rota->status) {
                            'iniciada'  => 'bg-blue-100 text-blue-700',
                            'concluida' => 'bg-green-100 text-green-700',
                            'cancelada' => 'bg-red-100 text-red-700',
                            default     => 'bg-yellow-100 text-yellow-700',
                        };
                        $statusLabel = match($rota->status) {
                            'iniciada'  => 'Iniciada',
                            'concluida' => 'Concluída',
                            'cancelada' => 'Cancelada',
                            default     => 'Planejada',
                        };
                    @endphp
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-5 py-4 text-slate-400 font-mono text-xs">{{ $rota->id }}</td>
                        <td class="px-5 py-4 text-slate-700 max-w-xs">
                            <p class="truncate">{{ $rota->pedido?->descricao ?: 'Sem descrição' }}</p>
                            <p class="text-xs text-slate-400">
                                {{ $rota->pedido?->enderecoColeta?->cidade }} →
                                {{ $rota->pedido?->enderecoEntrega?->cidade }}
                            </p>
                        </td>
                        <td class="px-5 py-4 text-slate-600">{{ $rota->motorista?->nome ?? '—' }}</td>
                        <td class="px-5 py-4 text-slate-600">
                            @if($rota->veiculo)
                                @php $tl = ['moto'=>'Moto','carro'=>'Carro','caminhao'=>'Caminhão','van'=>'Van']; @endphp
                                {{ $tl[$rota->veiculo->tipo] ?? ucfirst($rota->veiculo->tipo) }}
                                <span class="text-xs text-slate-400 font-mono ml-1">{{ $rota->veiculo->placa }}</span>
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-slate-600 whitespace-nowrap text-xs">
                            {{ $rota->data_inicio?->format('d/m/Y H:i') ?? '—' }}
                        </td>
                        <td class="px-5 py-4 text-center">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $badgeClass }}">
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <a href="{{ route('rotas.show', $rota->id) }}"
                               class="text-blue-600 hover:underline text-xs font-medium">
                                Detalhes
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($rotas->hasPages())
            <div class="flex justify-center">
                {{ $rotas->links() }}
            </div>
        @endif
    @endif

</div>
@endsection

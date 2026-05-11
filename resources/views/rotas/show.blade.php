@extends('layouts.app')

@section('title', 'Detalhe da Rota #' . $rota->id)

@section('content')
<div class="container mx-auto p-6 space-y-6">

    <header class="flex items-center justify-between">
        <div>
            <a href="{{ route('rotas.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Voltar</a>
            <h1 class="mt-1 text-2xl font-semibold text-slate-900">Rota #{{ $rota->id }}</h1>
        </div>
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
        <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-semibold {{ $badgeClass }}">
            {{ $statusLabel }}
        </span>
    </header>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg px-4 py-3 text-sm text-green-800">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-lg px-4 py-3 text-sm text-red-800">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Coluna principal --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Endereços --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5">
                <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-4">Endereços</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="block text-xs font-medium text-slate-500 mb-1">Coleta</span>
                        @if($rota->pedido?->enderecoColeta)
                            <p class="text-slate-800">{{ $rota->pedido->enderecoColeta->logradouro }}, {{ $rota->pedido->enderecoColeta->numero }}</p>
                            <p class="text-xs text-slate-500">{{ $rota->pedido->enderecoColeta->bairro }} — {{ $rota->pedido->enderecoColeta->cidade }}/{{ $rota->pedido->enderecoColeta->estado }}</p>
                        @else
                            <p class="text-slate-400">—</p>
                        @endif
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-slate-500 mb-1">Entrega</span>
                        @if($rota->pedido?->enderecoEntrega)
                            <p class="text-slate-800">{{ $rota->pedido->enderecoEntrega->logradouro }}, {{ $rota->pedido->enderecoEntrega->numero }}</p>
                            <p class="text-xs text-slate-500">{{ $rota->pedido->enderecoEntrega->bairro }} — {{ $rota->pedido->enderecoEntrega->cidade }}/{{ $rota->pedido->enderecoEntrega->estado }}</p>
                        @else
                            <p class="text-slate-400">—</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Waypoints --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5">
                <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-4">
                    Waypoints <span class="text-slate-400 font-normal">({{ $rota->waypoints->count() }})</span>
                </h2>
                @if($rota->waypoints->isEmpty())
                    <p class="text-sm text-slate-400">Nenhum waypoint registrado.</p>
                @else
                    <ol class="space-y-2">
                        @foreach($rota->waypoints as $wp)
                        <li class="flex items-center gap-3 text-sm">
                            <span class="w-6 h-6 flex items-center justify-center rounded-full bg-blue-100 text-blue-700 font-bold text-xs flex-shrink-0">
                                {{ $wp->ordem + 1 }}
                            </span>
                            <span class="text-slate-700 font-mono text-xs">
                                {{ number_format($wp->latitude, 5) }}, {{ number_format($wp->longitude, 5) }}
                            </span>
                        </li>
                        @endforeach
                    </ol>
                @endif
            </div>

            {{-- Histórico de rastreamento --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5">
                <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-4">
                    Histórico de Posições <span class="text-slate-400 font-normal">({{ $rota->rastreamentos->count() }})</span>
                </h2>
                @if($rota->rastreamentos->isEmpty())
                    <p class="text-sm text-slate-400">Nenhuma posição registrada.</p>
                @else
                    <div class="overflow-y-auto max-h-64">
                        <table class="min-w-full text-xs divide-y divide-slate-100">
                            <thead>
                                <tr>
                                    <th class="py-2 text-left font-semibold text-slate-500">Data/Hora</th>
                                    <th class="py-2 text-left font-semibold text-slate-500">Latitude</th>
                                    <th class="py-2 text-left font-semibold text-slate-500">Longitude</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($rota->rastreamentos as $r)
                                <tr>
                                    <td class="py-1.5 text-slate-600">{{ $r->data_hora?->format('d/m H:i:s') ?? '—' }}</td>
                                    <td class="py-1.5 font-mono text-slate-600">{{ number_format($r->latitude, 5) }}</td>
                                    <td class="py-1.5 font-mono text-slate-600">{{ number_format($r->longitude, 5) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- Coluna lateral --}}
        <div class="space-y-6">

            {{-- Info da rota --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 space-y-3 text-sm">
                <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wide">Informações</h2>
                <div>
                    <span class="block text-xs text-slate-500">Motorista</span>
                    <p class="text-slate-800 font-medium">{{ $rota->motorista?->nome ?? '—' }}</p>
                </div>
                <div>
                    <span class="block text-xs text-slate-500">Veículo</span>
                    @if($rota->veiculo)
                        @php $tl = ['moto'=>'Moto','carro'=>'Carro','caminhao'=>'Caminhão','van'=>'Van']; @endphp
                        <p class="text-slate-800">{{ $tl[$rota->veiculo->tipo] ?? ucfirst($rota->veiculo->tipo) }} — {{ $rota->veiculo->placa }}</p>
                    @else
                        <p class="text-slate-400">—</p>
                    @endif
                </div>
                <div>
                    <span class="block text-xs text-slate-500">Início</span>
                    <p class="text-slate-800">{{ $rota->data_inicio?->format('d/m/Y H:i') ?? '—' }}</p>
                </div>
                <div>
                    <span class="block text-xs text-slate-500">Conclusão</span>
                    <p class="text-slate-800">{{ $rota->data_fim?->format('d/m/Y H:i') ?? '—' }}</p>
                </div>
                <div>
                    <span class="block text-xs text-slate-500">Cliente</span>
                    <p class="text-slate-800">{{ $rota->pedido?->cliente?->nome ?? '—' }}</p>
                </div>
            </div>

            {{-- Ações --}}
            @if(!in_array($rota->status, ['concluida', 'cancelada']))
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 space-y-4">
                <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wide">Ações</h2>

                {{-- Cancelar --}}
                <form method="POST" action="{{ route('rotas.update', $rota->id) }}"
                      onsubmit="return confirm('Cancelar esta rota? O pedido também será marcado como cancelado.')">
                    @csrf @method('PATCH')
                    <input type="hidden" name="acao" value="cancelar">
                    <button type="submit"
                            class="w-full px-4 py-2 bg-red-600 text-white text-xs font-semibold rounded-lg hover:bg-red-700 transition uppercase tracking-wide">
                        Cancelar Rota
                    </button>
                </form>

                {{-- Reatribuir (só planejada) --}}
                @if($rota->status === 'planejada')
                <form method="POST" action="{{ route('rotas.update', $rota->id) }}" class="space-y-3">
                    @csrf @method('PATCH')
                    <input type="hidden" name="acao" value="reatribuir">

                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">Novo Motorista</label>
                        <select name="motorista_id" required
                                class="w-full rounded-lg border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                                onchange="atualizarVeiculos(this.value)">
                            <option value="">Selecione...</option>
                            @foreach($motoristas as $m)
                                <option value="{{ $m->id }}" {{ $rota->motorista_id == $m->id ? 'selected' : '' }}>
                                    {{ $m->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">Veículo</label>
                        <select name="veiculo_id" id="select-veiculo" required
                                class="w-full rounded-lg border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                            @if($rota->veiculo)
                                <option value="{{ $rota->veiculo->id }}" selected>
                                    {{ $rota->veiculo->placa }} — {{ $rota->veiculo->modelo }}
                                </option>
                            @else
                                <option value="">Selecione um motorista primeiro</option>
                            @endif
                        </select>
                    </div>

                    <button type="submit"
                            class="w-full px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700 transition uppercase tracking-wide">
                        Reatribuir
                    </button>
                </form>
                @endif
            </div>
            @endif

        </div>
    </div>
</div>

<script>
    const veiculosPorMotorista = @json(
        \App\Models\Usuario::where('tipo', 'motorista')
            ->with('veiculos')
            ->get()
            ->mapWithKeys(fn($m) => [
                $m->id => $m->veiculos->map(fn($v) => ['id' => $v->id, 'label' => $v->placa . ' — ' . $v->modelo])
            ])
    );

    function atualizarVeiculos(motoristaId) {
        const select = document.getElementById('select-veiculo');
        select.innerHTML = '';
        const veiculos = veiculosPorMotorista[motoristaId] || [];
        if (veiculos.length === 0) {
            select.innerHTML = '<option value="">Sem veículos cadastrados</option>';
            return;
        }
        veiculos.forEach(v => {
            const opt = document.createElement('option');
            opt.value = v.id;
            opt.textContent = v.label;
            select.appendChild(opt);
        });
    }
</script>
@endsection

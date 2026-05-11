@extends('layouts.app')

@section('title', 'Motoristas')

@section('content')
<div class="container mx-auto p-6 space-y-6">

    <header>
        <h1 class="text-2xl font-semibold text-slate-900">Motoristas</h1>
        <p class="mt-1 text-sm text-slate-500">Listagem de motoristas ativos e métricas de desempenho.</p>
    </header>

    @if($motoristas->isEmpty())
        <div class="bg-amber-50 border border-amber-200 rounded-lg p-6 text-center text-amber-800">
            <p class="font-medium">Nenhum motorista ativo encontrado.</p>
        </div>
    @else
        {{-- Cards de resumo --}}
        <section class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Total de motoristas</span>
                <p class="mt-1 text-3xl font-bold text-slate-900">{{ $motoristas->count() }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Rotas em andamento</span>
                <p class="mt-1 text-3xl font-bold text-blue-600">{{ $motoristas->sum('em_andamento') }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Eficiência média</span>
                @php
                    $comDados = $motoristas->filter(fn($m) => $m['eficiencia'] !== null);
                    $eficienciaMedia = $comDados->count() > 0
                        ? number_format($comDados->avg('eficiencia'), 1)
                        : '—';
                @endphp
                <p class="mt-1 text-3xl font-bold text-green-600">{{ $eficienciaMedia }}{{ $comDados->count() > 0 ? '%' : '' }}</p>
            </div>
        </section>

        {{-- Tabela --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Motorista</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Veículo</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide">Total</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide">Entregues</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide">Em andamento</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide">Cancelados</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide">Eficiência</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($motoristas as $motorista)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <p class="font-medium text-slate-900">{{ $motorista['nome'] }}</p>
                            @if($motorista['telefone'])
                                <p class="text-xs text-slate-400">{{ $motorista['telefone'] }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            @if($motorista['veiculo'])
                                @php
                                    $tipoLabel = ['moto' => 'Moto', 'carro' => 'Carro', 'caminhao' => 'Caminhão', 'van' => 'Van'];
                                @endphp
                                <span>{{ $tipoLabel[$motorista['veiculo']] ?? ucfirst($motorista['veiculo']) }}</span>
                                <p class="text-xs text-slate-400 font-mono">{{ $motorista['placa'] }}</p>
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center font-semibold text-slate-700">{{ $motorista['total'] }}</td>
                        <td class="px-6 py-4 text-center text-green-600 font-semibold">{{ $motorista['entregues'] }}</td>
                        <td class="px-6 py-4 text-center text-blue-600 font-semibold">{{ $motorista['em_andamento'] }}</td>
                        <td class="px-6 py-4 text-center text-red-500 font-semibold">{{ $motorista['cancelados'] }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($motorista['eficiencia'] !== null)
                                @php
                                    $cor = $motorista['eficiencia'] >= 80 ? 'text-green-600' : ($motorista['eficiencia'] >= 50 ? 'text-yellow-600' : 'text-red-500');
                                @endphp
                                <span class="font-bold {{ $cor }}">{{ $motorista['eficiencia'] }}%</span>
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</div>
@endsection

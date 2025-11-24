@extends('layouts.app')

@section('title', 'Relatórios de Desempenho')

@section('content')
    <div class="container mx-auto p-6 space-y-6">
        {{-- Cabeçalho e filtros --}}
        <header class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">
                    Relatórios de desempenho
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    Acompanhe a eficiência das rotas, coletas realizadas, tempo médio de entrega e falhas processuais.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                {{-- Filtro de período --}}
                <div class="flex flex-col">
                    <label class="text-xs font-medium text-slate-500 mb-1">
                        Período
                    </label>
                    <select
                        class="rounded-lg border-slate-200 text-sm shadow-sm focus:border-entrego-blue focus:ring-entrego-blue"
                        name="periodo"
                    >
                        <option value="7d">Últimos 7 dias</option>
                        <option value="30d" selected>Últimos 30 dias</option>
                        <option value="mes_atual">Mês atual</option>
                        <option value="90d">Últimos 90 dias</option>
                    </select>
                </div>

                {{-- Filtro de categoria de veículo --}}
                <div class="flex flex-col">
                    <label class="text-xs font-medium text-slate-500 mb-1">
                        Categoria de veículo
                    </label>
                    <select
                        class="rounded-lg border-slate-200 text-sm shadow-sm focus:border-entrego-blue focus:ring-entrego-blue"
                        name="categoria_veiculo"
                    >
                        <option value="todos" selected>Todos</option>
                        <option value="moto">Moto</option>
                        <option value="carro">Carro</option>
                        <option value="caminhao_leve">Caminhão leve</option>
                        <option value="caminhao_pesado">Caminhão pesado</option>
                    </select>
                </div>
            </div>
        </header>

        {{-- Cards de resumo (RF08) --}}
        <section class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
            {{-- Eficiência das rotas --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 flex flex-col gap-2">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-slate-500 uppercase tracking-wide">
                        Eficiência média das rotas
                    </span>
                    <span class="material-icons text-emerald-500 text-base">trending_up</span>
                </div>
                <p class="text-2xl font-semibold text-slate-900">
                    {{ number_format($resumo['eficiencia_rotas'] ?? 0, 1, ',', '.') }}%
                </p>
                <p class="text-xs text-emerald-600">
                    {{ $resumo['eficiencia_rotas_texto'] ?? 'Comparado ao período anterior' }}
                </p>
            </div>

            {{-- Número de coletas --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 flex flex-col gap-2">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-slate-500 uppercase tracking-wide">
                        Coletas realizadas
                    </span>
                    <span class="material-icons text-blue-500 text-base">local_shipping</span>
                </div>
                <p class="text-2xl font-semibold text-slate-900">
                    {{ $resumo['total_coletas'] ?? 0 }}
                </p>
                <p class="text-xs text-slate-500">
                    {{ $resumo['total_coletas_texto'] ?? 'Total de coletas concluídas no período' }}
                </p>
            </div>

            {{-- Tempo médio de entrega --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 flex flex-col gap-2">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-slate-500 uppercase tracking-wide">
                        Tempo médio de entrega
                    </span>
                    <span class="material-icons text-amber-500 text-base">schedule</span>
                </div>
                <p class="text-2xl font-semibold text-slate-900">
                    {{ $resumo['tempo_medio_entrega_formatado'] ?? '—' }}
                </p>
                <p class="text-xs text-slate-500">
                    {{ $resumo['tempo_medio_entrega_texto'] ?? 'Média entre coleta e entrega' }}
                </p>
            </div>

            {{-- Falhas processuais --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 flex flex-col gap-2">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-slate-500 uppercase tracking-wide">
                        Falhas processuais
                    </span>
                    <span class="material-icons text-rose-500 text-base">error_outline</span>
                </div>
                <p class="text-2xl font-semibold text-slate-900">
                    {{ $resumo['falhas_processuais'] ?? 0 }}
                </p>
                <p class="text-xs {{ ($resumo['falhas_processuais'] ?? 0) > 0 ? 'text-rose-500' : 'text-emerald-600' }}">
                    {{ $resumo['falhas_processuais_texto'] ?? 'Falhas registradas no período' }}
                </p>
            </div>
        </section>

        {{-- Gráficos principais (RF09) --}}
        <section class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            {{-- Tempo médio de entrega por período --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 xl:col-span-2 flex flex-col gap-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-800">
                            Tempo médio de entrega por período
                        </h2>
                        <p class="text-xs text-slate-500 mt-1">
                            Visualize a evolução do tempo médio de entrega no intervalo selecionado.
                        </p>
                    </div>
                    <span class="text-xs text-slate-400">
                        em minutos
                    </span>
                </div>

                <div class="h-72">
                    <canvas id="chartTempoEntrega"></canvas>
                </div>
            </div>

            {{-- Coletas por categoria de veículo --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 flex flex-col gap-4">
                <div>
                    <h2 class="text-sm font-semibold text-slate-800">
                        Coletas por categoria de veículo
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Distribuição das coletas entre motos, carros e caminhões.
                    </p>
                </div>

                <div class="h-72">
                    <canvas id="chartColetasVeiculo"></canvas>
                </div>
            </div>
        </section>

        {{-- Detalhamento + gráfico de falhas (RF08 + RF09) --}}
        <section class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            {{-- Tabela: eficiência por rota --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 xl:col-span-2 flex flex-col gap-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-800">
                            Detalhamento por rota
                        </h2>
                        <p class="text-xs text-slate-500 mt-1">
                            Eficiência das rotas, número de coletas e falhas processuais por rota.
                        </p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-left text-xs font-medium text-slate-500 uppercase tracking-wide">
                            <tr>
                                <th class="px-4 py-3">Rota</th>
                                <th class="px-4 py-3">Veículo</th>
                                <th class="px-4 py-3">Coletas</th>
                                <th class="px-4 py-3">Tempo médio</th>
                                <th class="px-4 py-3">Eficiência</th>
                                <th class="px-4 py-3">Falhas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($rotas ?? [] as $rota)
                                <tr class="hover:bg-slate-50/80">
                                    <td class="px-4 py-2 text-slate-700">
                                        {{ $rota['nome'] ?? '—' }}
                                    </td>
                                    <td class="px-4 py-2 text-slate-600">
                                        {{ $rota['veiculo'] ?? '—' }}
                                    </td>
                                    <td class="px-4 py-2 text-slate-700">
                                        {{ $rota['coletas'] ?? 0 }}
                                    </td>
                                    <td class="px-4 py-2 text-slate-700">
                                        {{ $rota['tempo_medio'] ?? '—' }}
                                    </td>
                                    <td class="px-4 py-2">
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                            @if(($rota['eficiencia'] ?? 0) >= 90)
                                                bg-emerald-50 text-emerald-700
                                            @elseif(($rota['eficiencia'] ?? 0) >= 75)
                                                bg-amber-50 text-amber-700
                                            @else
                                                bg-rose-50 text-rose-700
                                            @endif
                                        ">
                                            {{ number_format($rota['eficiencia'] ?? 0, 1, ',', '.') }}%
                                        </span>
                                    </td>
                                    <td class="px-4 py-2">
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                            {{ ($rota['falhas'] ?? 0) > 0 ? 'bg-rose-50 text-rose-700' : 'bg-emerald-50 text-emerald-700' }}
                                        ">
                                            {{ $rota['falhas'] ?? 0 }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-sm text-slate-400">
                                        Nenhuma rota encontrada para o filtro atual.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Falhas por tipo --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 flex flex-col gap-4">
                <div>
                    <h2 class="text-sm font-semibold text-slate-800">
                        Falhas por tipo
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Visualize quais etapas do processo concentram mais falhas.
                    </p>
                </div>

                <div class="h-72">
                    <canvas id="chartFalhasTipo"></canvas>
                </div>
            </div>
        </section>

        {{-- Elemento escondido para enviar dados ao JS --}}
        <div
            id="dashboardData"
            class="hidden"
            data-tempo-entrega-labels='@json($seriesTempoEntrega["labels"] ?? [])'
            data-tempo-entrega-data='@json($seriesTempoEntrega["values"] ?? [])'
            data-coletas-veiculo-labels='@json($seriesColetasVeiculo["labels"] ?? [])'
            data-coletas-veiculo-data='@json($seriesColetasVeiculo["values"] ?? [])'
            data-falhas-tipo-labels='@json($seriesFalhasTipo["labels"] ?? [])'
            data-falhas-tipo-data='@json($seriesFalhasTipo["values"] ?? [])'
        ></div>
    </div>
@endsection

@push('scripts')
    {{-- Chart.js via CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const dataEl = document.getElementById('dashboardData');
            if (!dataEl) return;

            const parseAttr = (attr) => {
                try {
                    return JSON.parse(dataEl.getAttribute(attr) || '[]');
                } catch (e) {
                    return [];
                }
            };

            const tempoEntregaLabels = parseAttr('data-tempo-entrega-labels');
            const tempoEntregaData   = parseAttr('data-tempo-entrega-data');
            const coletasLabels      = parseAttr('data-coletas-veiculo-labels');
            const coletasData        = parseAttr('data-coletas-veiculo-data');
            const falhasLabels       = parseAttr('data-falhas-tipo-labels');
            const falhasData         = parseAttr('data-falhas-tipo-data');

            // Gráfico: Tempo médio de entrega
            const tempoCtx = document.getElementById('chartTempoEntrega');
            if (tempoCtx && tempoEntregaLabels.length) {
                new Chart(tempoCtx, {
                    type: 'line',
                    data: {
                        labels: tempoEntregaLabels,
                        datasets: [{
                            label: 'Tempo médio (min)',
                            data: tempoEntregaData,
                            borderWidth: 2,
                            tension: 0.4,
                            pointRadius: 3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: false,
                                ticks: { stepSize: 5 }
                            }
                        }
                    }
                });
            }

            // Gráfico: Coletas por categoria de veículo
            const coletasCtx = document.getElementById('chartColetasVeiculo');
            if (coletasCtx && coletasLabels.length) {
                new Chart(coletasCtx, {
                    type: 'bar',
                    data: {
                        labels: coletasLabels,
                        datasets: [{
                            label: 'Coletas',
                            data: coletasData,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { stepSize: 5 }
                            }
                        }
                    }
                });
            }

            // Gráfico: Falhas por tipo
            const falhasCtx = document.getElementById('chartFalhasTipo');
            if (falhasCtx && falhasLabels.length) {
                new Chart(falhasCtx, {
                    type: 'doughnut',
                    data: {
                        labels: falhasLabels,
                        datasets: [{
                            data: falhasData
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 12,
                                    font: { size: 11 }
                                }
                            }
                        },
                        cutout: '60%'
                    }
                });
            }
        });
    </script>
@endpush

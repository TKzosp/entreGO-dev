<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Pedido;
use App\Models\Rota;

class DashboardController extends Controller
{
    public function index(Request $request): \Illuminate\View\View
    {
        $periodo    = $request->input('periodo', '30d');
        $catVeiculo = $request->input('categoria_veiculo', 'todos');

        $dataInicio   = $this->dataInicio($periodo);
        $tiposVeiculo = $this->tiposVeiculo($catVeiculo);

        // Carrega todas as rotas do período com relacionamentos necessários
        $todasRotas = Rota::with(['veiculo', 'pedido', 'motorista'])
            ->whereHas('veiculo', fn($q) => $q->whereIn('tipo', $tiposVeiculo))
            ->whereHas('pedido', fn($q) => $q->where('data_coleta', '>=', $dataInicio))
            ->get();

        // Agrupa por motorista para montar a tabela de desempenho
        $rotas = $todasRotas
            ->groupBy('motorista_id')
            ->map(function ($grupo) {
                $primeira  = $grupo->first();
                $motorista = $primeira->motorista;
                $veiculo   = $primeira->veiculo;

                $total      = $grupo->count();
                $entregues  = $grupo->filter(fn($r) => optional($r->pedido)->status === 'entregue')->count();
                $cancelados = $grupo->filter(fn($r) => optional($r->pedido)->status === 'cancelado')->count();
                $eficiencia = $total > 0 ? round(($entregues / $total) * 100, 1) : 0.0;

                $tempos = $grupo
                    ->filter(fn($r) => optional($r->pedido)->status === 'entregue' && $r->data_fim && optional($r->pedido)->data_coleta)
                    ->map(fn($r) => $r->pedido->data_coleta->diffInMinutes($r->data_fim));

                $tempoMedio = $tempos->count() > 0 ? round($tempos->average()) . ' min' : '—';

                $tipoLabel = ['moto' => 'Moto', 'carro' => 'Carro', 'caminhao' => 'Caminhão', 'van' => 'Van'];

                return [
                    'nome'       => $motorista->nome ?? '—',
                    'veiculo'    => ($tipoLabel[$veiculo->tipo ?? ''] ?? ($veiculo->tipo ?? '—')),
                    'veiculo_key'=> $veiculo->tipo ?? '',
                    'coletas'    => $total,
                    'tempo_medio'=> $tempoMedio,
                    'eficiencia' => $eficiencia,
                    'falhas'     => $cancelados,
                ];
            })
            ->sortByDesc('coletas')
            ->values()
            ->toArray();

        // KPIs
        $pedidosNoPeriodo = Pedido::where('data_coleta', '>=', $dataInicio)
            ->whereHas('rota', fn($q) => $q->whereHas('veiculo', fn($v) => $v->whereIn('tipo', $tiposVeiculo)))
            ->get();

        $totalColetas    = $pedidosNoPeriodo->whereIn('status', ['coletado', 'transito', 'entregue'])->count();
        $totalFalhas     = $pedidosNoPeriodo->where('status', 'cancelado')->count();
        $eficienciaMedia = count($rotas) > 0 ? round(collect($rotas)->avg('eficiencia'), 1) : 0.0;

        $temposMins = collect($rotas)
            ->filter(fn($r) => $r['tempo_medio'] !== '—')
            ->map(fn($r) => (int) str_replace(' min', '', $r['tempo_medio']));
        $tempoMedioGeral = $temposMins->count() > 0 ? $temposMins->avg() . ' min' : '—';

        return view('dashboard', [
            'resumo' => [
                'eficiencia_rotas'              => $eficienciaMedia,
                'eficiencia_rotas_texto'        => 'Com base nas entregas concluídas no período',
                'total_coletas'                 => $totalColetas,
                'total_coletas_texto'           => 'Coletas concluídas no período selecionado',
                'tempo_medio_entrega_formatado' => $tempoMedioGeral,
                'tempo_medio_entrega_texto'     => 'Média entre coleta e entrega no período',
                'falhas_processuais'            => $totalFalhas,
                'falhas_processuais_texto'      => $totalFalhas > 0
                    ? "{$totalFalhas} pedido(s) cancelado(s) no período"
                    : 'Nenhuma falha no período',
            ],
            'rotas'                => $rotas,
            'seriesTempoEntrega'   => $this->seriesTempoEntrega($dataInicio, $tiposVeiculo, $periodo),
            'seriesColetasVeiculo' => $this->seriesColetasVeiculo($dataInicio, $tiposVeiculo),
            'seriesFalhasTipo'     => $this->seriesStatusDistribuicao($dataInicio, $tiposVeiculo),
            'periodoAtual'         => $periodo,
            'categoriaAtual'       => $catVeiculo,
        ]);
    }

    private function dataInicio(string $periodo): Carbon
    {
        return match ($periodo) {
            '7d'        => now()->subDays(7)->startOfDay(),
            'mes_atual' => now()->startOfMonth()->startOfDay(),
            '90d'       => now()->subDays(90)->startOfDay(),
            default     => now()->subDays(30)->startOfDay(),
        };
    }

    private function tiposVeiculo(string $catVeiculo): array
    {
        return match ($catVeiculo) {
            'moto'            => ['moto'],
            'carro'           => ['carro'],
            'caminhao_leve'   => ['caminhao'],
            'caminhao_pesado' => ['van'],
            default           => ['moto', 'carro', 'caminhao', 'van'],
        };
    }

    // Gráfico de linha: tempo médio de entrega por dia ou semana
    private function seriesTempoEntrega(Carbon $dataInicio, array $tiposVeiculo, string $periodo): array
    {
        $registros = Rota::with(['pedido', 'veiculo'])
            ->whereHas('veiculo', fn($q) => $q->whereIn('tipo', $tiposVeiculo))
            ->whereHas('pedido', fn($q) => $q->where('status', 'entregue')->where('data_coleta', '>=', $dataInicio))
            ->whereNotNull('data_fim')
            ->get()
            ->map(fn($r) => [
                'data'  => Carbon::parse($r->data_fim)->format('Y-m-d'),
                'tempo' => $r->pedido->data_coleta->diffInMinutes($r->data_fim),
            ]);

        $dias = (int) $dataInicio->diffInDays(now());

        if ($dias <= 31) {
            $labels = [];
            $values = [];
            for ($i = $dias; $i >= 0; $i--) {
                $labels[] = now()->subDays($i)->format('d/m');
                $diaKey   = now()->subDays($i)->format('Y-m-d');
                $pontos   = $registros->filter(fn($r) => $r['data'] === $diaKey);
                $values[] = $pontos->count() > 0 ? (int) round($pontos->avg('tempo')) : 0;
            }
        } else {
            $semanas = (int) ceil($dias / 7);
            $labels  = [];
            $values  = [];
            for ($i = $semanas - 1; $i >= 0; $i--) {
                $semInicio = now()->subWeeks($i)->startOfWeek()->format('Y-m-d');
                $semFim    = now()->subWeeks($i)->endOfWeek()->format('Y-m-d');
                $labels[]  = 'Sem ' . ($semanas - $i);
                $pontos    = $registros->filter(fn($r) => $r['data'] >= $semInicio && $r['data'] <= $semFim);
                $values[]  = $pontos->count() > 0 ? (int) round($pontos->avg('tempo')) : 0;
            }
        }

        return compact('labels', 'values');
    }

    // Gráfico de barras: total de rotas por tipo de veículo
    private function seriesColetasVeiculo(Carbon $dataInicio, array $tiposVeiculo): array
    {
        $labelMap = ['moto' => 'Moto', 'carro' => 'Carro', 'caminhao' => 'Caminhão', 'van' => 'Van'];

        $counts = Rota::selectRaw('veiculos.tipo, COUNT(*) as total')
            ->join('veiculos', 'rotas.veiculo_id', '=', 'veiculos.id')
            ->join('pedidos', 'rotas.pedido_id', '=', 'pedidos.id')
            ->whereIn('veiculos.tipo', $tiposVeiculo)
            ->where('pedidos.data_coleta', '>=', $dataInicio)
            ->groupBy('veiculos.tipo')
            ->pluck('total', 'veiculos.tipo');

        $labels = [];
        $values = [];
        foreach ($tiposVeiculo as $tipo) {
            $labels[] = $labelMap[$tipo] ?? $tipo;
            $values[] = (int) ($counts[$tipo] ?? 0);
        }

        return compact('labels', 'values');
    }

    // Gráfico de rosca: distribuição de status dos pedidos
    private function seriesStatusDistribuicao(Carbon $dataInicio, array $tiposVeiculo): array
    {
        $statusLabels = [
            'entregue'  => 'Entregue',
            'transito'  => 'Em trânsito',
            'coletado'  => 'Coletado',
            'aceito'    => 'Aceito',
            'pendente'  => 'Pendente',
            'cancelado' => 'Cancelado',
        ];

        $counts = Pedido::selectRaw('status, COUNT(*) as total')
            ->where('data_coleta', '>=', $dataInicio)
            ->whereHas('rota', fn($q) => $q->whereHas('veiculo', fn($v) => $v->whereIn('tipo', $tiposVeiculo)))
            ->groupBy('status')
            ->pluck('total', 'status');

        $labels = [];
        $values = [];
        foreach ($statusLabels as $key => $label) {
            if (($counts[$key] ?? 0) > 0) {
                $labels[] = $label;
                $values[] = (int) $counts[$key];
            }
        }

        return compact('labels', 'values');
    }
}

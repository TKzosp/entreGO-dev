<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): \Illuminate\View\View
    {
        $periodo    = $request->input('periodo', '30d');
        $catVeiculo = $request->input('categoria_veiculo', 'todos');

        $todasRotas = [
            ['nome' => 'Rota Centro',           'veiculo' => 'Moto',            'veiculo_key' => 'moto',            'coletas' => 42, 'tempo_medio' => '28 min', 'eficiencia' => 94.2, 'falhas' => 0],
            ['nome' => 'Rota Vila Mariana',      'veiculo' => 'Moto',            'veiculo_key' => 'moto',            'coletas' => 38, 'tempo_medio' => '25 min', 'eficiencia' => 96.1, 'falhas' => 0],
            ['nome' => 'Rota Pinheiros',         'veiculo' => 'Carro',           'veiculo_key' => 'carro',           'coletas' => 35, 'tempo_medio' => '33 min', 'eficiencia' => 90.8, 'falhas' => 0],
            ['nome' => 'Rota Zona Norte',        'veiculo' => 'Carro',           'veiculo_key' => 'carro',           'coletas' => 33, 'tempo_medio' => '36 min', 'eficiencia' => 91.5, 'falhas' => 1],
            ['nome' => 'Rota Zona Sul',          'veiculo' => 'Moto',            'veiculo_key' => 'moto',            'coletas' => 32, 'tempo_medio' => '31 min', 'eficiencia' => 89.7, 'falhas' => 0],
            ['nome' => 'Rota Osasco',            'veiculo' => 'Carro',           'veiculo_key' => 'carro',           'coletas' => 30, 'tempo_medio' => '41 min', 'eficiencia' => 85.0, 'falhas' => 0],
            ['nome' => 'Rota Zona Oeste',        'veiculo' => 'Carro',           'veiculo_key' => 'carro',           'coletas' => 29, 'tempo_medio' => '39 min', 'eficiencia' => 87.9, 'falhas' => 1],
            ['nome' => 'Rota Zona Leste',        'veiculo' => 'Caminhão leve',   'veiculo_key' => 'caminhao_leve',   'coletas' => 27, 'tempo_medio' => '48 min', 'eficiencia' => 86.2, 'falhas' => 2],
            ['nome' => 'Rota Santo André',       'veiculo' => 'Caminhão leve',   'veiculo_key' => 'caminhao_leve',   'coletas' => 22, 'tempo_medio' => '52 min', 'eficiencia' => 83.4, 'falhas' => 2],
            ['nome' => 'Rota São Bernardo',      'veiculo' => 'Caminhão leve',   'veiculo_key' => 'caminhao_leve',   'coletas' => 19, 'tempo_medio' => '55 min', 'eficiencia' => 79.6, 'falhas' => 1],
            ['nome' => 'Rota Guarulhos',         'veiculo' => 'Caminhão pesado', 'veiculo_key' => 'caminhao_pesado', 'coletas' => 17, 'tempo_medio' => '67 min', 'eficiencia' => 76.3, 'falhas' => 1],
            ['nome' => 'Rota Campinas Express',  'veiculo' => 'Caminhão pesado', 'veiculo_key' => 'caminhao_pesado', 'coletas' => 23, 'tempo_medio' => '78 min', 'eficiencia' => 68.5, 'falhas' => 0],
        ];

        $rotas = $catVeiculo === 'todos'
            ? $todasRotas
            : array_values(array_filter($todasRotas, fn($r) => $r['veiculo_key'] === $catVeiculo));

        $mult = ['7d' => 0.23, '30d' => 1.0, 'mes_atual' => 0.33, '90d' => 3.0][$periodo] ?? 1.0;

        $rotasEscaladas = array_map(fn($r) => array_merge($r, [
            'coletas' => max(1, (int) round($r['coletas'] * $mult)),
            'falhas'  => (int) round($r['falhas'] * $mult),
        ]), $rotas);

        $totalColetas   = array_sum(array_column($rotasEscaladas, 'coletas'));
        $totalFalhas    = array_sum(array_column($rotasEscaladas, 'falhas'));
        $eficienciaMedia = count($rotasEscaladas)
            ? round(array_sum(array_column($rotasEscaladas, 'eficiencia')) / count($rotasEscaladas), 1)
            : 0.0;

        $tempoMedio = match ($catVeiculo) {
            'moto'            => '27 min',
            'carro'           => '37 min',
            'caminhao_leve'   => '52 min',
            'caminhao_pesado' => '73 min',
            default           => '34 min',
        };

        $eficienciaDelta = match ($periodo) {
            '7d'       => '+1,2% em relação à semana anterior',
            'mes_atual'=> '+2,1% em relação ao mês anterior',
            '90d'      => '+5,8% em relação ao trimestre anterior',
            default    => '+3,7% em relação ao período anterior',
        };

        $seriesTempoEntrega = match ($periodo) {
            '7d' => [
                'labels' => ['04/05','05/05','06/05','07/05','08/05','09/05','10/05'],
                'values' => [35, 33, 31, 34, 32, 30, 31],
            ],
            'mes_atual' => [
                'labels' => ['01/05','02/05','03/05','04/05','05/05','06/05','07/05','08/05','09/05','10/05'],
                'values' => [36, 34, 32, 35, 33, 31, 34, 32, 31, 32],
            ],
            '90d' => [
                'labels' => ['Sem 1','Sem 2','Sem 3','Sem 4','Sem 5','Sem 6','Sem 7','Sem 8','Sem 9','Sem 10','Sem 11','Sem 12','Sem 13'],
                'values' => [45, 44, 43, 41, 42, 40, 39, 38, 40, 37, 36, 35, 34],
            ],
            default => [
                'labels' => [
                    '11/04','12/04','13/04','14/04','15/04','16/04','17/04','18/04','19/04','20/04',
                    '21/04','22/04','23/04','24/04','25/04','26/04','27/04','28/04','29/04','30/04',
                    '01/05','02/05','03/05','04/05','05/05','06/05','07/05','08/05','09/05','10/05',
                ],
                'values' => [42, 40, 38, 41, 39, 37, 43, 40, 36, 38, 35, 37, 39, 34, 36, 33, 38, 35, 32, 34, 36, 33, 31, 35, 32, 30, 33, 34, 31, 32],
            ],
        };

        if ($catVeiculo !== 'todos') {
            $fatorTempo = match ($catVeiculo) {
                'moto'            => 0.80,
                'carro'           => 1.09,
                'caminhao_leve'   => 1.53,
                'caminhao_pesado' => 2.15,
                default           => 1.0,
            };
            $seriesTempoEntrega['values'] = array_map(fn($v) => (int) round($v * $fatorTempo), $seriesTempoEntrega['values']);
        }

        $baseColetas = match ($periodo) {
            '7d'       => [26, 30, 15, 9],
            'mes_atual'=> [36, 43, 13, 6],
            '90d'      => [336, 387, 198, 103],
            default    => [112, 129, 66, 40],
        };

        if ($catVeiculo === 'todos') {
            $seriesColetasVeiculo = [
                'labels' => ['Moto', 'Carro', 'Caminhão leve', 'Caminhão pesado'],
                'values' => $baseColetas,
            ];
        } else {
            $idx = ['moto' => 0, 'carro' => 1, 'caminhao_leve' => 2, 'caminhao_pesado' => 3];
            $label = ['moto' => 'Moto', 'carro' => 'Carro', 'caminhao_leve' => 'Caminhão leve', 'caminhao_pesado' => 'Caminhão pesado'];
            $seriesColetasVeiculo = [
                'labels' => [$label[$catVeiculo]],
                'values' => [$baseColetas[$idx[$catVeiculo]]],
            ];
        }

        $baseFalhas = match ($periodo) {
            '7d'       => [1, 0, 1, 0],
            'mes_atual'=> [1, 1, 1, 0],
            '90d'      => [9, 7, 5, 3],
            default    => [3, 2, 2, 1],
        };

        $seriesFalhasTipo = [
            'labels' => ['Endereço não encontrado', 'Destinatário ausente', 'Atraso na coleta', 'Produto danificado'],
            'values' => $baseFalhas,
        ];

        return view('dashboard', [
            'resumo' => [
                'eficiencia_rotas'              => $eficienciaMedia,
                'eficiencia_rotas_texto'        => $eficienciaDelta,
                'total_coletas'                 => $totalColetas,
                'total_coletas_texto'           => 'Coletas concluídas no período selecionado',
                'tempo_medio_entrega_formatado' => $tempoMedio,
                'tempo_medio_entrega_texto'     => 'Média entre coleta e entrega no período',
                'falhas_processuais'            => $totalFalhas,
                'falhas_processuais_texto'      => $totalFalhas > 0
                    ? "−" . max(0, $totalFalhas - 1) . " vs. período anterior"
                    : 'Nenhuma falha no período',
            ],
            'rotas'                => $rotasEscaladas,
            'seriesTempoEntrega'   => $seriesTempoEntrega,
            'seriesColetasVeiculo' => $seriesColetasVeiculo,
            'seriesFalhasTipo'     => $seriesFalhasTipo,
            'periodoAtual'         => $periodo,
            'categoriaAtual'       => $catVeiculo,
        ]);
    }
}

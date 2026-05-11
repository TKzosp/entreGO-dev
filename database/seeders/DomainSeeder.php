<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\Usuario;
use App\Models\Veiculo;
use App\Models\Endereco;
use App\Models\Pedido;
use App\Models\Rota;
use App\Models\Rastreamento;

class DomainSeeder extends Seeder
{
    public function run(): void
    {
        // ──────────────────────────────────────────────
        // MOTORISTAS
        // ──────────────────────────────────────────────
        $motoristas = [
            ['nome' => 'Carlos Souza',   'email' => 'carlos@entrego.com',   'tipo' => 'motorista'],
            ['nome' => 'Ana Lima',        'email' => 'ana@entrego.com',       'tipo' => 'motorista'],
            ['nome' => 'Pedro Alves',     'email' => 'pedro@entrego.com',     'tipo' => 'motorista'],
            ['nome' => 'Julia Ferreira',  'email' => 'julia@entrego.com',     'tipo' => 'motorista'],
        ];

        $motoristaCriados = [];
        foreach ($motoristas as $m) {
            $motoristaCriados[] = Usuario::firstOrCreate(
                ['email' => $m['email']],
                array_merge($m, ['senha' => Hash::make('123456'), 'ativo' => true])
            );
        }

        // ──────────────────────────────────────────────
        // CLIENTE (Sonely Papelaria)
        // ──────────────────────────────────────────────
        $cliente = Usuario::firstOrCreate(
            ['email' => 'sonely@papelaria.com.br'],
            [
                'nome'     => 'Sonely Papelaria',
                'senha'    => Hash::make('123456'),
                'tipo'     => 'cliente',
                'cpf_cnpj' => '12.345.678/0001-99',
                'telefone' => '(11) 98765-4321',
                'ativo'    => true,
            ]
        );

        // ──────────────────────────────────────────────
        // VEÍCULOS
        // ──────────────────────────────────────────────
        $veiculosData = [
            ['usuario_id' => $motoristaCriados[0]->id, 'tipo' => 'moto',     'placa' => 'ABC-1234', 'modelo' => 'Honda CG 160',      'capacidade' => 30,  'ano' => 2022],
            ['usuario_id' => $motoristaCriados[1]->id, 'tipo' => 'moto',     'placa' => 'DEF-5678', 'modelo' => 'Yamaha Factor 150', 'capacidade' => 25,  'ano' => 2021],
            ['usuario_id' => $motoristaCriados[2]->id, 'tipo' => 'carro',    'placa' => 'GHI-9012', 'modelo' => 'Fiat Strada',       'capacidade' => 500, 'ano' => 2023],
            ['usuario_id' => $motoristaCriados[3]->id, 'tipo' => 'caminhao', 'placa' => 'JKL-3456', 'modelo' => 'VW Delivery 9.170','capacidade' => 3000,'ano' => 2020],
        ];

        $veiculos = [];
        foreach ($veiculosData as $v) {
            $veiculos[] = Veiculo::firstOrCreate(['placa' => $v['placa']], $v);
        }

        // ──────────────────────────────────────────────
        // ENDEREÇOS DE COLETA (depósito Sonely)
        // ──────────────────────────────────────────────
        $depositoBase = [
            'usuario_id' => $cliente->id,
            'cep'        => '01310-100',
            'logradouro' => 'Av. Paulista',
            'numero'     => '1578',
            'bairro'     => 'Bela Vista',
            'cidade'     => 'São Paulo',
            'estado'     => 'SP',
            'latitude'   => -23.5613,
            'longitude'  => -46.6565,
        ];
        $deposito = Endereco::firstOrCreate(
            ['usuario_id' => $cliente->id, 'logradouro' => 'Av. Paulista', 'numero' => '1578'],
            $depositoBase
        );

        // ──────────────────────────────────────────────
        // ENDEREÇOS DE ENTREGA (clientes da Sonely)
        // ──────────────────────────────────────────────
        $destinos = [
            ['logradouro' => 'Rua da Consolação',   'numero' => '234',  'bairro' => 'Consolação',   'cep' => '01301-000', 'latitude' => -23.5489, 'longitude' => -46.6658],
            ['logradouro' => 'Av. Brigadeiro Faria Lima','numero' => '3477','bairro' => 'Itaim Bibi','cep' => '04538-133', 'latitude' => -23.5710, 'longitude' => -46.6856],
            ['logradouro' => 'Rua Oscar Freire',    'numero' => '900',  'bairro' => 'Jardins',      'cep' => '01426-001', 'latitude' => -23.5614, 'longitude' => -46.6698],
            ['logradouro' => 'Rua da Mooca',        'numero' => '1200', 'bairro' => 'Mooca',        'cep' => '03103-001', 'latitude' => -23.5474, 'longitude' => -46.6050],
            ['logradouro' => 'Av. Rebouças',        'numero' => '600',  'bairro' => 'Pinheiros',    'cep' => '05402-000', 'latitude' => -23.5651, 'longitude' => -46.6762],
            ['logradouro' => 'Rua Augusta',         'numero' => '1500', 'bairro' => 'Consolação',   'cep' => '01304-001', 'latitude' => -23.5570, 'longitude' => -46.6617],
            ['logradouro' => 'Av. Santo Amaro',     'numero' => '2800', 'bairro' => 'Santo Amaro',  'cep' => '04701-003', 'latitude' => -23.6512, 'longitude' => -46.7011],
            ['logradouro' => 'Rua Vergueiro',       'numero' => '3185', 'bairro' => 'Vila Mariana', 'cep' => '04101-300', 'latitude' => -23.5928, 'longitude' => -46.6363],
        ];

        $enderecoEntrega = [];
        foreach ($destinos as $d) {
            $enderecoEntrega[] = Endereco::firstOrCreate(
                ['usuario_id' => $cliente->id, 'logradouro' => $d['logradouro'], 'numero' => $d['numero']],
                array_merge($d, ['usuario_id' => $cliente->id, 'cidade' => 'São Paulo', 'estado' => 'SP'])
            );
        }

        // ──────────────────────────────────────────────
        // PEDIDOS + ROTAS
        // ──────────────────────────────────────────────
        $now = Carbon::now();

        $pedidosData = [
            // Pedidos entregues (últimos 30 dias)
            ['dest' => 0, 'motorista' => 0, 'veiculo' => 0, 'status_pedido' => 'entregue', 'status_rota' => 'concluida', 'dias' => -28, 'descricao' => 'Resmas de papel A4',        'peso' => 12.5, 'valor' => 85.00,  'tempo' => 28],
            ['dest' => 1, 'motorista' => 2, 'veiculo' => 2, 'status_pedido' => 'entregue', 'status_rota' => 'concluida', 'dias' => -25, 'descricao' => 'Cadernos universitários',   'peso' => 8.0,  'valor' => 60.00,  'tempo' => 33],
            ['dest' => 2, 'motorista' => 1, 'veiculo' => 1, 'status_pedido' => 'entregue', 'status_rota' => 'concluida', 'dias' => -22, 'descricao' => 'Canetas e marcadores',      'peso' => 3.0,  'valor' => 45.00,  'tempo' => 25],
            ['dest' => 3, 'motorista' => 3, 'veiculo' => 3, 'status_pedido' => 'entregue', 'status_rota' => 'concluida', 'dias' => -20, 'descricao' => 'Papelão para embalagem',    'peso' => 50.0, 'valor' => 120.00, 'tempo' => 48],
            ['dest' => 4, 'motorista' => 0, 'veiculo' => 0, 'status_pedido' => 'entregue', 'status_rota' => 'concluida', 'dias' => -18, 'descricao' => 'Envelopes e papel carta',   'peso' => 6.5,  'valor' => 52.00,  'tempo' => 31],
            ['dest' => 5, 'motorista' => 2, 'veiculo' => 2, 'status_pedido' => 'entregue', 'status_rota' => 'concluida', 'dias' => -15, 'descricao' => 'Pastas e fichários',        'peso' => 9.0,  'valor' => 70.00,  'tempo' => 36],
            ['dest' => 6, 'motorista' => 1, 'veiculo' => 1, 'status_pedido' => 'entregue', 'status_rota' => 'concluida', 'dias' => -12, 'descricao' => 'Fita adesiva e cola',       'peso' => 4.5,  'valor' => 38.00,  'tempo' => 27],
            ['dest' => 7, 'motorista' => 3, 'veiculo' => 3, 'status_pedido' => 'entregue', 'status_rota' => 'concluida', 'dias' => -10, 'descricao' => 'Material para escritório',  'peso' => 35.0, 'valor' => 210.00, 'tempo' => 55],
            ['dest' => 0, 'motorista' => 0, 'veiculo' => 0, 'status_pedido' => 'entregue', 'status_rota' => 'concluida', 'dias' => -8,  'descricao' => 'Blocos de notas',           'peso' => 5.0,  'valor' => 42.00,  'tempo' => 26],
            ['dest' => 1, 'motorista' => 2, 'veiculo' => 2, 'status_pedido' => 'entregue', 'status_rota' => 'concluida', 'dias' => -6,  'descricao' => 'Agendas e planners',        'peso' => 7.5,  'valor' => 95.00,  'tempo' => 34],
            // Pedidos em trânsito
            ['dest' => 2, 'motorista' => 1, 'veiculo' => 1, 'status_pedido' => 'transito', 'status_rota' => 'iniciada',  'dias' => -1,  'descricao' => 'Papel fotográfico',         'peso' => 2.5,  'valor' => 55.00,  'tempo' => 25],
            ['dest' => 3, 'motorista' => 3, 'veiculo' => 3, 'status_pedido' => 'transito', 'status_rota' => 'iniciada',  'dias' => 0,   'descricao' => 'Caixas de arquivo morto',   'peso' => 40.0, 'valor' => 180.00, 'tempo' => 52],
            // Pedidos pendentes
            ['dest' => 4, 'motorista' => 0, 'veiculo' => 0, 'status_pedido' => 'pendente', 'status_rota' => 'planejada', 'dias' => 1,   'descricao' => 'Toner de impressora',       'peso' => 3.5,  'valor' => 320.00, 'tempo' => 30],
            ['dest' => 5, 'motorista' => 2, 'veiculo' => 2, 'status_pedido' => 'pendente', 'status_rota' => 'planejada', 'dias' => 2,   'descricao' => 'Papel sulfite colorido',    'peso' => 15.0, 'valor' => 78.00,  'tempo' => 36],
        ];

        foreach ($pedidosData as $pd) {
            $dataColeta   = $now->copy()->addDays($pd['dias'])->setHour(8)->setMinute(0);
            $dataEntrega  = $dataColeta->copy()->addMinutes($pd['tempo'] + 10);

            $pedido = Pedido::create([
                'cliente_id'             => $cliente->id,
                'endereco_coleta_id'     => $deposito->id,
                'endereco_entrega_id'    => $enderecoEntrega[$pd['dest']]->id,
                'descricao'              => $pd['descricao'],
                'peso'                   => $pd['peso'],
                'valor'                  => $pd['valor'],
                'data_coleta'            => $dataColeta,
                'data_entrega_estimada'  => $dataEntrega,
                'status'                 => $pd['status_pedido'],
            ]);

            $rota = Rota::create([
                'pedido_id'      => $pedido->id,
                'motorista_id'   => $motoristaCriados[$pd['motorista']]->id,
                'veiculo_id'     => $veiculos[$pd['veiculo']]->id,
                'distancia'      => round($pd['tempo'] * 0.6, 1),
                'tempo_estimado' => $pd['tempo'],
                'status'         => $pd['status_rota'],
                'data_inicio'    => in_array($pd['status_rota'], ['iniciada', 'concluida']) ? $dataColeta : null,
                'data_fim'       => $pd['status_rota'] === 'concluida' ? $dataColeta->copy()->addMinutes($pd['tempo']) : null,
            ]);

            // Rastreamento para rotas ativas/concluídas
            if (in_array($pd['status_rota'], ['iniciada', 'concluida'])) {
                $lat = $enderecoEntrega[$pd['dest']]->latitude ?? -23.5613;
                $lng = $enderecoEntrega[$pd['dest']]->longitude ?? -46.6565;

                Rastreamento::create([
                    'rota_id'   => $rota->id,
                    'latitude'  => $lat,
                    'longitude' => $lng,
                    'data_hora' => $pd['status_rota'] === 'concluida'
                        ? $dataColeta->copy()->addMinutes($pd['tempo'])
                        : $now,
                ]);
            }
        }
    }
}

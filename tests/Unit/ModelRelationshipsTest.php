<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use App\Models\Veiculo;
use App\Models\Endereco;
use App\Models\Pedido;
use App\Models\Rota;
use App\Models\Rastreamento;

class ModelRelationshipsTest extends TestCase
{
    use RefreshDatabase;

    // ──────────────────────────────────────────────
    // USUARIO
    // ──────────────────────────────────────────────

    public function test_usuario_has_pedidos_relationship(): void
    {
        $usuario = $this->criarUsuario();
        $this->assertInstanceOf(HasMany::class, $usuario->pedidos());
    }

    public function test_usuario_has_veiculos_relationship(): void
    {
        $usuario = $this->criarMotorista();
        $this->assertInstanceOf(HasMany::class, $usuario->veiculos());
    }

    public function test_usuario_has_enderecos_relationship(): void
    {
        $usuario = $this->criarUsuario();
        $this->assertInstanceOf(HasMany::class, $usuario->enderecos());
    }

    public function test_usuario_has_rotas_como_motorista_relationship(): void
    {
        $motorista = $this->criarMotorista();
        $this->assertInstanceOf(HasMany::class, $motorista->rotasComoMotorista());
    }

    public function test_usuario_auth_password_uses_senha_field(): void
    {
        $usuario = $this->criarUsuario(['senha' => Hash::make('secreta')]);
        $this->assertTrue(Hash::check('secreta', $usuario->getAuthPassword()));
    }

    // ──────────────────────────────────────────────
    // VEICULO
    // ──────────────────────────────────────────────

    public function test_veiculo_belongs_to_motorista(): void
    {
        $motorista = $this->criarMotorista();
        $veiculo   = Veiculo::create([
            'usuario_id' => $motorista->id,
            'tipo'       => 'moto',
            'placa'      => 'TST-0001',
            'modelo'     => 'Honda CG',
        ]);

        $this->assertInstanceOf(BelongsTo::class, $veiculo->motorista());
        $this->assertEquals($motorista->id, $veiculo->motorista->id);
    }

    public function test_veiculo_has_rotas_relationship(): void
    {
        $motorista = $this->criarMotorista();
        $veiculo   = Veiculo::create([
            'usuario_id' => $motorista->id,
            'tipo'       => 'carro',
            'placa'      => 'TST-0002',
        ]);

        $this->assertInstanceOf(HasMany::class, $veiculo->rotas());
    }

    // ──────────────────────────────────────────────
    // ENDERECO
    // ──────────────────────────────────────────────

    public function test_endereco_belongs_to_usuario(): void
    {
        $usuario  = $this->criarUsuario();
        $endereco = Endereco::create([
            'usuario_id' => $usuario->id,
            'cep'        => '01310-100',
            'logradouro' => 'Av. Paulista',
            'numero'     => '1000',
            'bairro'     => 'Bela Vista',
            'cidade'     => 'São Paulo',
            'estado'     => 'SP',
        ]);

        $this->assertInstanceOf(BelongsTo::class, $endereco->usuario());
        $this->assertEquals($usuario->id, $endereco->usuario->id);
    }

    // ──────────────────────────────────────────────
    // PEDIDO
    // ──────────────────────────────────────────────

    public function test_pedido_belongs_to_cliente(): void
    {
        [$pedido] = $this->criarPedidoComRota();
        $this->assertInstanceOf(BelongsTo::class, $pedido->cliente());
        $this->assertNotNull($pedido->cliente);
    }

    public function test_pedido_belongs_to_endereco_coleta(): void
    {
        [$pedido] = $this->criarPedidoComRota();
        $this->assertInstanceOf(BelongsTo::class, $pedido->enderecoColeta());
        $this->assertNotNull($pedido->enderecoColeta);
    }

    public function test_pedido_belongs_to_endereco_entrega(): void
    {
        [$pedido] = $this->criarPedidoComRota();
        $this->assertInstanceOf(BelongsTo::class, $pedido->enderecoEntrega());
        $this->assertNotNull($pedido->enderecoEntrega);
    }

    public function test_pedido_has_one_rota(): void
    {
        [$pedido] = $this->criarPedidoComRota();
        $this->assertInstanceOf(HasOne::class, $pedido->rota());
        $this->assertNotNull($pedido->rota);
    }

    public function test_pedido_status_enum_values(): void
    {
        $statusValidos = ['pendente', 'aceito', 'coletado', 'transito', 'entregue', 'cancelado'];

        foreach ($statusValidos as $status) {
            [$pedido] = $this->criarPedidoComRota(['status' => $status]);
            $this->assertEquals($status, $pedido->status);
        }
    }

    // ──────────────────────────────────────────────
    // ROTA
    // ──────────────────────────────────────────────

    public function test_rota_belongs_to_pedido(): void
    {
        [, $rota] = $this->criarPedidoComRota();
        $this->assertInstanceOf(BelongsTo::class, $rota->pedido());
        $this->assertNotNull($rota->pedido);
    }

    public function test_rota_belongs_to_motorista(): void
    {
        [, $rota] = $this->criarPedidoComRota();
        $this->assertInstanceOf(BelongsTo::class, $rota->motorista());
        $this->assertNotNull($rota->motorista);
    }

    public function test_rota_belongs_to_veiculo(): void
    {
        [, $rota] = $this->criarPedidoComRota();
        $this->assertInstanceOf(BelongsTo::class, $rota->veiculo());
        $this->assertNotNull($rota->veiculo);
    }

    public function test_rota_has_many_rastreamentos(): void
    {
        [, $rota] = $this->criarPedidoComRota();

        Rastreamento::create([
            'rota_id'   => $rota->id,
            'latitude'  => -23.5613,
            'longitude' => -46.6565,
            'data_hora' => now(),
        ]);

        $this->assertInstanceOf(HasMany::class, $rota->rastreamentos());
        $this->assertCount(1, $rota->rastreamentos);
    }

    // ──────────────────────────────────────────────
    // HELPER
    // ──────────────────────────────────────────────

    private function criarPedidoComRota(array $pedidoAtributos = []): array
    {
        $cliente   = $this->criarUsuario();
        $motorista = $this->criarMotorista();
        $veiculo   = Veiculo::create([
            'usuario_id' => $motorista->id,
            'tipo'       => 'moto',
            'placa'      => 'TST-' . rand(1000, 9999),
        ]);

        $endBase = [
            'usuario_id' => $cliente->id,
            'cep'        => '01310-100',
            'logradouro' => 'Rua Teste',
            'numero'     => '1',
            'bairro'     => 'Centro',
            'cidade'     => 'São Paulo',
            'estado'     => 'SP',
        ];

        $coleta  = Endereco::create(array_merge($endBase, ['numero' => '1']));
        $entrega = Endereco::create(array_merge($endBase, ['numero' => '2']));

        $pedido = Pedido::create(array_merge([
            'cliente_id'          => $cliente->id,
            'endereco_coleta_id'  => $coleta->id,
            'endereco_entrega_id' => $entrega->id,
            'data_coleta'         => now(),
            'status'              => 'pendente',
        ], $pedidoAtributos));

        $rota = Rota::create([
            'pedido_id'    => $pedido->id,
            'motorista_id' => $motorista->id,
            'veiculo_id'   => $veiculo->id,
            'status'       => 'planejada',
        ]);

        return [$pedido, $rota];
    }
}

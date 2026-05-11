<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Veiculo;
use App\Models\Rota;
use App\Models\Pedido;
use App\Models\Endereco;

class AgendamentoTest extends TestCase
{
    use RefreshDatabase;

    private function dadosPedido(array $override = []): array
    {
        return array_merge([
            'coleta_cep'        => '01310-100',
            'coleta_logradouro' => 'Av. Paulista',
            'coleta_numero'     => '1578',
            'coleta_bairro'     => 'Bela Vista',
            'coleta_cidade'     => 'São Paulo',
            'coleta_estado'     => 'SP',
            'entrega_cep'       => '04038-001',
            'entrega_logradouro'=> 'Rua Vergueiro',
            'entrega_numero'    => '500',
            'entrega_bairro'    => 'Vila Mariana',
            'entrega_cidade'    => 'São Paulo',
            'entrega_estado'    => 'SP',
            'descricao'         => 'Caixas',
            'peso'              => '2.00',
            'data_coleta'       => now()->addDay()->format('Y-m-d'),
        ], $override);
    }

    private function criarMotoristaComVeiculo(): \App\Models\Usuario
    {
        $motorista = $this->criarMotorista();
        Veiculo::create([
            'usuario_id' => $motorista->id,
            'tipo'       => 'moto',
            'placa'      => 'TST-' . rand(1000, 9999),
            'modelo'     => 'Honda CG',
            'capacidade' => 30,
            'ano'        => 2022,
        ]);
        return $motorista;
    }

    // ── Agendamento automático ────────────────────────────────────────────────

    public function test_store_cria_rota_quando_motorista_disponivel(): void
    {
        $cliente   = $this->criarUsuario();
        $this->criarMotoristaComVeiculo();

        $this->actingAs($cliente)->post('/pedidos', $this->dadosPedido());

        $this->assertDatabaseHas('pedidos', [
            'cliente_id' => $cliente->id,
            'status'     => 'aceito',
        ]);

        $pedido = Pedido::where('cliente_id', $cliente->id)->first();
        $this->assertNotNull($pedido->rota, 'Rota deve ser criada automaticamente.');
        $this->assertEquals('planejada', $pedido->rota->status);
    }

    public function test_store_sem_motorista_mantem_pedido_pendente(): void
    {
        $cliente = $this->criarUsuario();

        $this->actingAs($cliente)->post('/pedidos', $this->dadosPedido());

        $this->assertDatabaseHas('pedidos', [
            'cliente_id' => $cliente->id,
            'status'     => 'pendente',
        ]);

        $pedido = Pedido::where('cliente_id', $cliente->id)->first();
        $this->assertNull($pedido->rota);
    }

    public function test_motorista_com_menos_rotas_ativas_e_selecionado(): void
    {
        $cliente    = $this->criarUsuario();
        $motoristaA = $this->criarMotoristaComVeiculo();
        $motoristaB = $this->criarMotoristaComVeiculo();

        // Cria uma rota ativa para motoristaA
        $endereco = Endereco::create([
            'usuario_id' => $cliente->id, 'cep' => '00000-000',
            'logradouro' => 'Rua X', 'numero' => '1',
            'bairro' => 'Centro', 'cidade' => 'SP', 'estado' => 'SP',
        ]);
        $pedidoExistente = Pedido::create([
            'cliente_id' => $cliente->id, 'endereco_coleta_id' => $endereco->id,
            'endereco_entrega_id' => $endereco->id, 'status' => 'aceito',
            'data_coleta' => now()->addDay(),
        ]);
        Rota::create([
            'pedido_id' => $pedidoExistente->id,
            'motorista_id' => $motoristaA->id,
            'veiculo_id' => $motoristaA->veiculos->first()->id,
            'status' => 'planejada',
        ]);

        // Novo pedido deve ir para motoristaB (0 rotas ativas)
        $this->actingAs($cliente)->post('/pedidos', $this->dadosPedido());

        $novoPedido = Pedido::where('cliente_id', $cliente->id)
            ->where('status', 'aceito')
            ->orderByDesc('id')
            ->first();

        $this->assertEquals($motoristaB->id, $novoPedido->rota->motorista_id);
    }

    // ── Transições de status ──────────────────────────────────────────────────

    public function test_avancar_status_planejada_para_iniciada(): void
    {
        $cliente   = $this->criarUsuario();
        $motorista = $this->criarMotoristaComVeiculo();
        $this->criarMotoristaComVeiculo(); // garante motorista disponível

        $this->actingAs($cliente)->post('/pedidos', $this->dadosPedido());
        $rota = Pedido::where('cliente_id', $cliente->id)->first()->rota;

        $this->actingAs($motorista)
             ->patch("/tracking/rotas/{$rota->id}/status")
             ->assertStatus(200)
             ->assertJson(['novo_status' => 'iniciada']);

        $this->assertDatabaseHas('rotas', ['id' => $rota->id, 'status' => 'iniciada']);
    }

    public function test_avancar_status_iniciada_para_concluida_marca_pedido_entregue(): void
    {
        $cliente   = $this->criarUsuario();
        $motorista = $this->criarMotoristaComVeiculo();
        $this->criarMotoristaComVeiculo();

        $this->actingAs($cliente)->post('/pedidos', $this->dadosPedido());
        $pedido = Pedido::where('cliente_id', $cliente->id)->first();
        $rota   = $pedido->rota;

        // Avança para iniciada
        $rota->update(['status' => 'iniciada']);

        $this->actingAs($motorista)
             ->patch("/tracking/rotas/{$rota->id}/status")
             ->assertStatus(200)
             ->assertJson(['novo_status' => 'concluida']);

        $this->assertDatabaseHas('rotas',   ['id' => $rota->id,   'status' => 'concluida']);
        $this->assertDatabaseHas('pedidos', ['id' => $pedido->id, 'status' => 'entregue']);
    }

    public function test_avancar_status_rota_concluida_retorna_erro(): void
    {
        $motorista = $this->criarMotoristaComVeiculo();
        $cliente   = $this->criarUsuario();

        $endereco = Endereco::create([
            'usuario_id' => $cliente->id, 'cep' => '00000-000',
            'logradouro' => 'Rua X', 'numero' => '1',
            'bairro' => 'Centro', 'cidade' => 'SP', 'estado' => 'SP',
        ]);
        $pedido = Pedido::create([
            'cliente_id' => $cliente->id, 'endereco_coleta_id' => $endereco->id,
            'endereco_entrega_id' => $endereco->id, 'status' => 'entregue',
            'data_coleta' => now()->subDay(),
        ]);
        $rota = Rota::create([
            'pedido_id' => $pedido->id, 'motorista_id' => $motorista->id,
            'veiculo_id' => $motorista->veiculos->first()->id, 'status' => 'concluida',
        ]);

        $this->actingAs($motorista)
             ->patch("/tracking/rotas/{$rota->id}/status")
             ->assertStatus(422);
    }
}

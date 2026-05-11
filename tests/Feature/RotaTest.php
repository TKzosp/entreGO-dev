<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Veiculo;
use App\Models\Rota;
use App\Models\Pedido;
use App\Models\Endereco;

class RotaTest extends TestCase
{
    use RefreshDatabase;

    private function criarRotaCompleta(string $status = 'planejada'): array
    {
        $cliente   = $this->criarUsuario(['email' => 'cli_' . uniqid() . '@test.com']);
        $motorista = $this->criarMotorista(['email' => 'mot_' . uniqid() . '@test.com']);

        $veiculo = Veiculo::create([
            'usuario_id' => $motorista->id, 'tipo' => 'moto',
            'placa' => 'TST-' . rand(1000, 9999), 'modelo' => 'Honda CG',
            'capacidade' => 30, 'ano' => 2022,
        ]);

        $endereco = Endereco::create([
            'usuario_id' => $cliente->id, 'cep' => '01310-100',
            'logradouro' => 'Av. Paulista', 'numero' => '1000',
            'bairro' => 'Bela Vista', 'cidade' => 'São Paulo', 'estado' => 'SP',
        ]);

        $pedido = Pedido::create([
            'cliente_id' => $cliente->id, 'endereco_coleta_id' => $endereco->id,
            'endereco_entrega_id' => $endereco->id, 'status' => 'aceito',
            'data_coleta' => now()->addDay(),
        ]);

        $rota = Rota::create([
            'pedido_id' => $pedido->id, 'motorista_id' => $motorista->id,
            'veiculo_id' => $veiculo->id, 'status' => $status,
        ]);

        return compact('cliente', 'motorista', 'veiculo', 'pedido', 'rota');
    }

    // ── Index ─────────────────────────────────────────────────────────────────

    public function test_rotas_index_loads_for_authenticated_user(): void
    {
        $this->actingAs($this->criarUsuario())
             ->get('/rotas')
             ->assertStatus(200)
             ->assertSee('Gestão de Rotas');
    }

    public function test_rotas_index_redirects_unauthenticated_user(): void
    {
        $this->get('/rotas')->assertRedirect('/login');
    }

    public function test_rotas_index_filter_by_status(): void
    {
        ['cliente' => $cliente] = $this->criarRotaCompleta('planejada');
        $this->criarRotaCompleta('concluida');

        $response = $this->actingAs($cliente)->get('/rotas?status=planejada');

        $response->assertStatus(200);
        $rotas = $response->viewData('rotas');
        $this->assertTrue($rotas->every(fn($r) => $r->status === 'planejada'));
    }

    // ── Show ──────────────────────────────────────────────────────────────────

    public function test_rotas_show_displays_rota_details(): void
    {
        ['cliente' => $cliente, 'rota' => $rota] = $this->criarRotaCompleta();

        $this->actingAs($cliente)
             ->get("/rotas/{$rota->id}")
             ->assertStatus(200)
             ->assertSee("Rota #{$rota->id}");
    }

    public function test_rotas_show_returns_404_for_invalid_id(): void
    {
        $this->actingAs($this->criarUsuario())
             ->get('/rotas/99999')
             ->assertStatus(404);
    }

    // ── Update: cancelar ─────────────────────────────────────────────────────

    public function test_cancelar_rota_planejada(): void
    {
        ['cliente' => $cliente, 'rota' => $rota, 'pedido' => $pedido] = $this->criarRotaCompleta('planejada');

        $this->actingAs($cliente)
             ->patch("/rotas/{$rota->id}", ['acao' => 'cancelar'])
             ->assertRedirect();

        $this->assertDatabaseHas('rotas',   ['id' => $rota->id,   'status' => 'cancelada']);
        $this->assertDatabaseHas('pedidos', ['id' => $pedido->id, 'status' => 'cancelado']);
    }

    public function test_cancelar_rota_concluida_retorna_erro(): void
    {
        ['cliente' => $cliente, 'rota' => $rota] = $this->criarRotaCompleta('concluida');

        $this->actingAs($cliente)
             ->patch("/rotas/{$rota->id}", ['acao' => 'cancelar'])
             ->assertRedirect()
             ->assertSessionHas('error');

        $this->assertDatabaseHas('rotas', ['id' => $rota->id, 'status' => 'concluida']);
    }

    // ── Update: reatribuir ────────────────────────────────────────────────────

    public function test_reatribuir_rota_planejada(): void
    {
        ['cliente' => $cliente, 'rota' => $rota] = $this->criarRotaCompleta('planejada');

        $novoMotorista = $this->criarMotorista(['email' => 'novo_' . uniqid() . '@test.com']);
        $novoVeiculo = Veiculo::create([
            'usuario_id' => $novoMotorista->id, 'tipo' => 'carro',
            'placa' => 'NEW-' . rand(1000, 9999), 'modelo' => 'Fiat',
            'capacidade' => 500, 'ano' => 2023,
        ]);

        $this->actingAs($cliente)
             ->patch("/rotas/{$rota->id}", [
                 'acao'         => 'reatribuir',
                 'motorista_id' => $novoMotorista->id,
                 'veiculo_id'   => $novoVeiculo->id,
             ])
             ->assertRedirect()
             ->assertSessionHas('success');

        $this->assertDatabaseHas('rotas', [
            'id'          => $rota->id,
            'motorista_id'=> $novoMotorista->id,
            'veiculo_id'  => $novoVeiculo->id,
        ]);
    }

    public function test_reatribuir_rota_iniciada_retorna_erro(): void
    {
        ['cliente' => $cliente, 'rota' => $rota, 'motorista' => $motorista, 'veiculo' => $veiculo] = $this->criarRotaCompleta('iniciada');

        $this->actingAs($cliente)
             ->patch("/rotas/{$rota->id}", [
                 'acao'         => 'reatribuir',
                 'motorista_id' => $motorista->id,
                 'veiculo_id'   => $veiculo->id,
             ])
             ->assertRedirect()
             ->assertSessionHas('error');
    }
}

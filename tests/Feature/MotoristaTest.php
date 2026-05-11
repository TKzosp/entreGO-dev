<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Veiculo;
use App\Models\Pedido;
use App\Models\Endereco;
use App\Models\Rota;

class MotoristaTest extends TestCase
{
    use RefreshDatabase;

    public function test_motoristas_page_loads_for_authenticated_user(): void
    {
        $this->actingAs($this->criarUsuario())
             ->get('/motoristas')
             ->assertStatus(200)
             ->assertSee('Motoristas');
    }

    public function test_motoristas_redirects_unauthenticated_user(): void
    {
        $this->get('/motoristas')->assertRedirect('/login');
    }

    public function test_motoristas_page_shows_only_motoristas(): void
    {
        $cliente   = $this->criarUsuario(['tipo' => 'cliente', 'email' => 'c@test.com']);
        $motorista = $this->criarMotorista(['nome' => 'Motorista Visivel', 'email' => 'm@test.com']);

        $response = $this->actingAs($cliente)->get('/motoristas');

        $response->assertStatus(200)
                 ->assertSee('Motorista Visivel')
                 ->assertDontSee($cliente->nome);
    }

    public function test_motoristas_page_shows_empty_state_when_no_motoristas(): void
    {
        $this->actingAs($this->criarUsuario())
             ->get('/motoristas')
             ->assertSee('Nenhum motorista ativo encontrado');
    }

    public function test_motoristas_page_shows_correct_metrics(): void
    {
        $cliente   = $this->criarUsuario(['email' => 'cli@test.com']);
        $motorista = $this->criarMotorista(['nome' => 'Joao Silva', 'email' => 'joao@test.com']);

        $veiculo = Veiculo::create([
            'usuario_id' => $motorista->id,
            'tipo'       => 'moto',
            'placa'      => 'ABC1234',
            'modelo'     => 'Honda',
            'capacidade' => 50,
            'ano'        => 2022,
        ]);

        $endereco = Endereco::create([
            'usuario_id' => $cliente->id,
            'cep'        => '01310-100',
            'logradouro' => 'Av. Paulista',
            'numero'     => '1000',
            'bairro'     => 'Bela Vista',
            'cidade'     => 'São Paulo',
            'estado'     => 'SP',
        ]);

        $pedido = Pedido::create([
            'cliente_id'          => $cliente->id,
            'endereco_coleta_id'  => $endereco->id,
            'endereco_entrega_id' => $endereco->id,
            'status'              => 'entregue',
            'data_coleta'         => now()->subDay(),
        ]);

        Rota::create([
            'pedido_id'    => $pedido->id,
            'motorista_id' => $motorista->id,
            'veiculo_id'   => $veiculo->id,
            'status'       => 'concluida',
        ]);

        $response = $this->actingAs($cliente)->get('/motoristas');

        $response->assertStatus(200)
                 ->assertSee('Joao Silva')
                 ->assertSee('ABC1234');
    }
}

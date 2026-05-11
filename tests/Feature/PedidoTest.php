<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PedidoTest extends TestCase
{
    use RefreshDatabase;

    private function dadosValidos(array $override = []): array
    {
        return array_merge([
            'coleta_cep'        => '01310-100',
            'coleta_logradouro' => 'Av. Paulista',
            'coleta_numero'     => '1578',
            'coleta_bairro'     => 'Bela Vista',
            'coleta_cidade'     => 'São Paulo',
            'coleta_estado'     => 'SP',

            'entrega_cep'        => '04038-001',
            'entrega_logradouro' => 'Rua Vergueiro',
            'entrega_numero'     => '500',
            'entrega_bairro'     => 'Vila Mariana',
            'entrega_cidade'     => 'São Paulo',
            'entrega_estado'     => 'SP',

            'descricao'   => 'Caixas de papelaria',
            'peso'        => '3.50',
            'data_coleta' => now()->addDay()->format('Y-m-d'),
        ], $override);
    }

    public function test_registration_page_loads_for_authenticated_user(): void
    {
        $this->actingAs($this->criarUsuario())
             ->get('/registration')
             ->assertStatus(200);
    }

    public function test_store_pedido_persists_in_database(): void
    {
        $usuario = $this->criarUsuario();

        $this->actingAs($usuario)
             ->post('/pedidos', $this->dadosValidos())
             ->assertRedirect('/registration')
             ->assertSessionHas('success');

        $this->assertDatabaseHas('pedidos', [
            'cliente_id' => $usuario->id,
            'status'     => 'pendente',
            'descricao'  => 'Caixas de papelaria',
        ]);
    }

    public function test_store_creates_enderecos_for_authenticated_user(): void
    {
        $usuario = $this->criarUsuario();

        $this->actingAs($usuario)->post('/pedidos', $this->dadosValidos());

        $this->assertDatabaseHas('enderecos', [
            'usuario_id' => $usuario->id,
            'logradouro' => 'Av. Paulista',
            'cidade'     => 'São Paulo',
        ]);

        $this->assertDatabaseHas('enderecos', [
            'usuario_id' => $usuario->id,
            'logradouro' => 'Rua Vergueiro',
            'cidade'     => 'São Paulo',
        ]);
    }

    public function test_store_requires_coleta_fields(): void
    {
        $usuario = $this->criarUsuario();

        $this->actingAs($usuario)
             ->post('/pedidos', $this->dadosValidos(['coleta_cep' => '', 'coleta_logradouro' => '', 'coleta_bairro' => '']))
             ->assertSessionHasErrors(['coleta_cep', 'coleta_logradouro', 'coleta_bairro']);
    }

    public function test_store_requires_entrega_fields(): void
    {
        $usuario = $this->criarUsuario();

        $this->actingAs($usuario)
             ->post('/pedidos', $this->dadosValidos(['entrega_logradouro' => '', 'entrega_cidade' => '']))
             ->assertSessionHasErrors(['entrega_logradouro', 'entrega_cidade']);
    }

    public function test_store_requires_future_data_coleta(): void
    {
        $usuario = $this->criarUsuario();

        $this->actingAs($usuario)
             ->post('/pedidos', $this->dadosValidos(['data_coleta' => now()->subDay()->format('Y-m-d')]))
             ->assertSessionHasErrors('data_coleta');
    }

    public function test_store_redirects_unauthenticated_user_to_login(): void
    {
        $this->post('/pedidos', $this->dadosValidos())
             ->assertRedirect('/login');
    }

    // ── Histórico de pedidos ──────────────────────────────────────────────────

    public function test_pedidos_index_loads_for_authenticated_user(): void
    {
        $this->actingAs($this->criarUsuario())
             ->get('/pedidos')
             ->assertStatus(200)
             ->assertSee('Meus Pedidos');
    }

    public function test_pedidos_index_redirects_unauthenticated_user(): void
    {
        $this->get('/pedidos')->assertRedirect('/login');
    }

    public function test_pedidos_index_shows_only_own_pedidos(): void
    {
        $usuarioA = $this->criarUsuario(['email' => 'a@test.com']);
        $usuarioB = $this->criarUsuario(['email' => 'b@test.com']);

        $this->actingAs($usuarioA)->post('/pedidos', $this->dadosValidos());
        $this->actingAs($usuarioB)->post('/pedidos', $this->dadosValidos());

        $response = $this->actingAs($usuarioA)->get('/pedidos');
        $response->assertStatus(200);

        $pedidosVisiveis = $response->viewData('pedidos');
        $this->assertTrue($pedidosVisiveis->every(fn($p) => $p->cliente_id === $usuarioA->id));
    }

    public function test_pedidos_index_shows_empty_state_when_no_pedidos(): void
    {
        $this->actingAs($this->criarUsuario())
             ->get('/pedidos')
             ->assertSee('Nenhum pedido encontrado');
    }
}

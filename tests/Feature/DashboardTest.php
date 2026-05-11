<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    // ──────────────────────────────────────────────
    // DASHBOARD
    // ──────────────────────────────────────────────

    public function test_dashboard_loads_for_authenticated_user(): void
    {
        $usuario = $this->criarUsuario();

        $this->actingAs($usuario)
             ->get('/')
             ->assertStatus(200);
    }

    public function test_dashboard_alias_route_loads(): void
    {
        $usuario = $this->criarUsuario();

        $this->actingAs($usuario)
             ->get('/dashboard')
             ->assertStatus(200);
    }

    public function test_dashboard_filter_periodo_7d(): void
    {
        $usuario = $this->criarUsuario();

        $this->actingAs($usuario)
             ->get('/?periodo=7d')
             ->assertStatus(200);
    }

    public function test_dashboard_filter_periodo_mes_atual(): void
    {
        $usuario = $this->criarUsuario();

        $this->actingAs($usuario)
             ->get('/?periodo=mes_atual')
             ->assertStatus(200);
    }

    public function test_dashboard_filter_periodo_90d(): void
    {
        $usuario = $this->criarUsuario();

        $this->actingAs($usuario)
             ->get('/?periodo=90d')
             ->assertStatus(200);
    }

    public function test_dashboard_filter_categoria_moto(): void
    {
        $usuario = $this->criarUsuario();

        $this->actingAs($usuario)
             ->get('/?categoria_veiculo=moto')
             ->assertStatus(200);
    }

    public function test_dashboard_filter_categoria_carro(): void
    {
        $usuario = $this->criarUsuario();

        $this->actingAs($usuario)
             ->get('/?categoria_veiculo=carro')
             ->assertStatus(200);
    }

    public function test_dashboard_filter_categoria_caminhao_leve(): void
    {
        $usuario = $this->criarUsuario();

        $this->actingAs($usuario)
             ->get('/?categoria_veiculo=caminhao_leve')
             ->assertStatus(200);
    }

    public function test_dashboard_combined_filters(): void
    {
        $usuario = $this->criarUsuario();

        $this->actingAs($usuario)
             ->get('/?periodo=7d&categoria_veiculo=moto')
             ->assertStatus(200);
    }

    // ──────────────────────────────────────────────
    // OUTRAS PÁGINAS PROTEGIDAS
    // ──────────────────────────────────────────────

    public function test_tracking_page_loads(): void
    {
        $usuario = $this->criarUsuario();

        $this->actingAs($usuario)
             ->get('/tracking')
             ->assertStatus(200);
    }

    public function test_profile_page_loads(): void
    {
        $usuario = $this->criarUsuario();

        $this->actingAs($usuario)
             ->get('/profile')
             ->assertStatus(200);
    }

    public function test_faq_page_loads(): void
    {
        $usuario = $this->criarUsuario();

        $this->actingAs($usuario)
             ->get('/faq')
             ->assertStatus(200);
    }

    public function test_contact_page_loads(): void
    {
        $usuario = $this->criarUsuario();

        $this->actingAs($usuario)
             ->get('/contato')
             ->assertStatus(200);
    }

    public function test_tickets_page_loads(): void
    {
        $usuario = $this->criarUsuario();

        $this->actingAs($usuario)
             ->get('/meus-chamados')
             ->assertStatus(200);
    }

    public function test_assinaturas_page_loads(): void
    {
        $usuario = $this->criarUsuario();

        $this->actingAs($usuario)
             ->get('/assinaturas')
             ->assertStatus(200);
    }

    public function test_minha_assinatura_page_loads(): void
    {
        $usuario = $this->criarUsuario();

        $this->actingAs($usuario)
             ->get('/minha-assinatura')
             ->assertStatus(200);
    }

    public function test_registration_page_loads(): void
    {
        $usuario = $this->criarUsuario();

        $this->actingAs($usuario)
             ->get('/registration')
             ->assertStatus(200);
    }
}

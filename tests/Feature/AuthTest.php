<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    // ──────────────────────────────────────────────
    // PÁGINAS PÚBLICAS
    // ──────────────────────────────────────────────

    public function test_login_page_loads(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_register_page_loads(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    // ──────────────────────────────────────────────
    // PROTEÇÃO DE ROTAS
    // ──────────────────────────────────────────────

    public function test_dashboard_redirects_unauthenticated_user(): void
    {
        $this->get('/')->assertRedirect('/login');
    }

    public function test_tracking_redirects_unauthenticated_user(): void
    {
        $this->get('/tracking')->assertRedirect('/login');
    }

    public function test_profile_redirects_unauthenticated_user(): void
    {
        $this->get('/profile')->assertRedirect('/login');
    }

    // ──────────────────────────────────────────────
    // FLUXO DE LOGIN
    // ──────────────────────────────────────────────

    public function test_login_with_valid_credentials_redirects_to_dashboard(): void
    {
        $usuario = $this->criarUsuario(['senha' => Hash::make('senha123')]);

        $response = $this->post('/login', [
            'email'    => $usuario->email,
            'password' => 'senha123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($usuario);
    }

    public function test_login_with_wrong_password_returns_error(): void
    {
        $usuario = $this->criarUsuario(['senha' => Hash::make('senha123')]);

        $response = $this->post('/login', [
            'email'    => $usuario->email,
            'password' => 'senha_errada',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_login_with_nonexistent_email_returns_error(): void
    {
        $response = $this->post('/login', [
            'email'    => 'naoexiste@entrego.com',
            'password' => 'qualquer',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_login_requires_email_field(): void
    {
        $response = $this->post('/login', ['password' => 'senha123']);
        $response->assertSessionHasErrors('email');
    }

    public function test_login_requires_password_field(): void
    {
        $response = $this->post('/login', ['email' => 'alguem@entrego.com']);
        $response->assertSessionHasErrors('password');
    }

    // ──────────────────────────────────────────────
    // LOGOUT
    // ──────────────────────────────────────────────

    public function test_logout_redirects_to_login(): void
    {
        $usuario = $this->criarUsuario();

        $response = $this->actingAs($usuario)->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    // ──────────────────────────────────────────────
    // CADASTRO
    // ──────────────────────────────────────────────

    public function test_register_creates_user_and_logs_in(): void
    {
        $response = $this->post('/register', [
            'nome'               => 'Novo Usuário',
            'email'              => 'novo@entrego.com',
            'senha'              => 'Senha123',
            'senha_confirmation' => 'Senha123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('usuarios', [
            'email' => 'novo@entrego.com',
            'tipo'  => 'cliente',
        ]);
    }

    public function test_register_forces_tipo_cliente_even_when_payload_tries_admin(): void
    {
        $this->post('/register', [
            'nome'               => 'Tentativa Escalada',
            'email'              => 'atacante@entrego.com',
            'senha'              => 'Senha123',
            'senha_confirmation' => 'Senha123',
            'tipo'               => 'admin',
        ]);

        $this->assertDatabaseHas('usuarios', [
            'email' => 'atacante@entrego.com',
            'tipo'  => 'cliente',
        ]);
        $this->assertDatabaseMissing('usuarios', [
            'email' => 'atacante@entrego.com',
            'tipo'  => 'admin',
        ]);
    }
}

# entreGO — Sistema de Gestão de Entregas

Plataforma web para gerenciamento de rotas, coletas e desempenho de entregas, desenvolvida com Laravel 12 + Tailwind CSS + Alpine.js.

---

## Pré-requisitos

| Ferramenta | Versão mínima |
|------------|---------------|
| PHP        | 8.2           |
| Composer   | 2.x           |
| Node.js    | 18.x          |
| NPM        | 9.x           |
| MySQL      | 8.0           |

> Guia completo de instalação do PHP/Laravel: https://laravel.com/docs/12.x/installation

---

## Como rodar o projeto

### 1. Clonar e instalar dependências

```bash
git clone https://github.com/TKzosp/entreGO-dev
cd entreGO-dev
composer install
npm install
```

### 2. Configurar o ambiente

```bash
cp .env.example .env
```

Abra o `.env` e ajuste as credenciais do MySQL:

```env
DB_DATABASE=entrego_db
DB_USERNAME=root
DB_PASSWORD=sua_senha
```

### 3. Gerar a chave da aplicação

```bash
php artisan key:generate
```

### 4. Criar o banco e rodar as migrações

Crie o banco `entrego_db` no MySQL antes de continuar:

```sql
CREATE DATABASE entrego_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Depois rode as migrações e os seeders:

```bash
php artisan migrate
php artisan db:seed
```

### 5. Compilar os assets e subir o servidor

```bash
npm run build
php artisan serve
```

Acesse em: **http://localhost:8000**

> Para desenvolvimento com hot-reload, rode `npm run dev` em um terminal separado enquanto `php artisan serve` está ativo.

---

## Rotas disponíveis

| Rota                  | Descrição                                          |
|-----------------------|----------------------------------------------------|
| `/login`              | Tela de login                                      |
| `/register`           | Cadastro de novo usuário                           |
| `/`                   | Dashboard de desempenho (alias de `/dashboard`)    |
| `/dashboard`          | Dashboard com KPIs, gráficos e filtros             |
| `/tracking`           | Rastreamento em tempo real com Google Maps         |
| `/profile`            | Dados e configurações do usuário logado            |
| `/registration`       | Formulário de cadastro de novo pedido              |
| `/pedidos`            | Histórico de pedidos do usuário autenticado        |
| `/motoristas`         | Listagem de motoristas com métricas de desempenho  |
| `/rotas`              | Gestão de rotas com filtros de status e motorista  |
| `/rotas/{id}`         | Detalhe da rota: waypoints, rastreamento e ações   |
| `/assinaturas`        | Planos de assinatura disponíveis                   |
| `/minha-assinatura`   | Assinatura ativa do usuário                        |
| `/faq`                | Perguntas frequentes (suporte)                     |
| `/contato`            | Formulário de contato                              |
| `/meus-chamados`      | Histórico de chamados de suporte                   |

---

## Status das Funcionalidades (RF)

> Legenda: ✅ Pronto | 🔶 Parcial (UI existe, backend mock ou incompleto) | ❌ Não implementado

| RF    | Requisito                              | Status | Observação                                                        |
|-------|----------------------------------------|--------|-------------------------------------------------------------------|
| RF01  | Autenticação (login/logout)            | ✅     | Guard customizado, model `Usuario` com campo `senha`              |
| RF02  | Cadastro de usuários                   | ✅     | Validação completa, hash bcrypt                                   |
| RF03  | Planos de assinatura                   | ✅     | Views e controller implementados (`/assinaturas`)                 |
| RF04  | Rastreamento de rotas                  | ✅     | Rota ativa carregada do banco; posição inicial do mapa real       |
| RF05  | Dashboard de desempenho                | ✅     | Queries reais: KPIs, tabela por motorista, 3 gráficos dinâmicos  |
| RF06  | Notificações (e-mail/SMS ao cliente)   | ❌     | Não implementado                                                  |
| RF07  | Agendamento de coletas                 | ✅     | Atribuição automática de motorista; transições planejada→iniciada→concluida |
| RF08  | Cadastro de pedidos                    | ✅     | Formulário completo com persistência real e validação             |
| RF09  | Gestão de rotas                        | ✅     | Listagem com filtros, detalhe com waypoints/rastreamento, cancelar e reatribuir |
| RF10  | Waypoints de rota                      | ✅     | CRUD completo via `WaypointController`                            |
| RF11  | Suporte (FAQ, contato, chamados)       | ✅     | Três views e `SupportController` implementados                    |

---

## Testes Automatizados

O projeto conta com **77 testes PHPUnit** (17 unitários + 60 de feature) que rodam em SQLite in-memory e cobrem autenticação, rotas protegidas, relacionamentos Eloquent, fluxo completo de pedidos, histórico de entregas, motoristas, agendamento ponta a ponta e gestão de rotas.

Para executar:

```bash
php artisan test
```

Resultado esperado: `77 tests, 148 assertions` — todos passando.

### Exemplos de testes implementados

**1. Autenticação — login com credenciais válidas**
```php
// tests/Feature/AuthTest.php
public function test_login_with_valid_credentials_redirects_to_dashboard(): void
{
    $usuario = $this->criarUsuario(['senha' => Hash::make('senha123')]);

    $this->post('/login', ['email' => $usuario->email, 'password' => 'senha123'])
         ->assertRedirect('/');

    $this->assertAuthenticatedAs($usuario);
}
```

**2. Agendamento — pedido cria rota e atribui motorista automaticamente**
```php
// tests/Feature/AgendamentoTest.php
public function test_store_cria_rota_quando_motorista_disponivel(): void
{
    $cliente = $this->criarUsuario();
    $this->criarMotoristaComVeiculo();

    $this->actingAs($cliente)->post('/pedidos', $this->dadosPedido());

    $this->assertDatabaseHas('pedidos', ['cliente_id' => $cliente->id, 'status' => 'aceito']);

    $pedido = Pedido::where('cliente_id', $cliente->id)->first();
    $this->assertNotNull($pedido->rota);
    $this->assertEquals('planejada', $pedido->rota->status);
}
```

**3. Agendamento — conclusão de rota marca pedido como entregue**
```php
// tests/Feature/AgendamentoTest.php
public function test_avancar_status_iniciada_para_concluida_marca_pedido_entregue(): void
{
    $cliente = $this->criarUsuario();
    $this->criarMotoristaComVeiculo();
    $this->actingAs($cliente)->post('/pedidos', $this->dadosPedido());

    $pedido = Pedido::where('cliente_id', $cliente->id)->first();
    $pedido->rota->update(['status' => 'iniciada']);

    $this->actingAs($this->criarMotorista())
         ->patch("/tracking/rotas/{$pedido->rota->id}/status")
         ->assertJson(['novo_status' => 'concluida']);

    $this->assertDatabaseHas('pedidos', ['id' => $pedido->id, 'status' => 'entregue']);
}
```

**4. Motoristas — exibe apenas motoristas com métricas corretas**
```php
// tests/Feature/MotoristaTest.php
public function test_motoristas_page_shows_only_motoristas(): void
{
    $cliente   = $this->criarUsuario(['tipo' => 'cliente', 'email' => 'c@test.com']);
    $motorista = $this->criarMotorista(['nome' => 'Motorista Visivel', 'email' => 'm@test.com']);

    $response = $this->actingAs($cliente)->get('/motoristas');

    $response->assertStatus(200)
             ->assertSee('Motorista Visivel')
             ->assertDontSee($cliente->nome);
}
```

**5. Gestão de rotas — cancelar rota atualiza pedido**
```php
// tests/Feature/RotaTest.php
public function test_cancelar_rota_planejada(): void
{
    ['cliente' => $cliente, 'rota' => $rota, 'pedido' => $pedido] = $this->criarRotaCompleta('planejada');

    $this->actingAs($cliente)
         ->patch("/rotas/{$rota->id}", ['acao' => 'cancelar'])
         ->assertRedirect();

    $this->assertDatabaseHas('rotas',   ['id' => $rota->id,   'status' => 'cancelada']);
    $this->assertDatabaseHas('pedidos', ['id' => $pedido->id, 'status' => 'cancelado']);
}
```

---

## Pendências

### RF06 — Notificações por e-mail `❌ Não implementado`

| Tarefa | Esforço |
|--------|---------|
| Mailable `ColetaConfirmada` — disparado ao criar pedido com rota atribuída | Alto |
| Mailable `StatusEntregaAtualizado` — disparado a cada transição de status | Alto |
| Configurar SMTP no `.env` e fila de jobs (`queue:work`) | Alto |
| Testes com `Mail::fake()` | Médio |

---

## O que está a mais (não exigido pelo relatório)

- Sistema duplo de layouts (`layouts/` + `components/layouts/`) — gerado pelo Livewire; o projeto usa `layouts/`
- Componentes Livewire de auth (`livewire/auth/`) — duplicam a auth padrão já funcional

---

## Membros do grupo

| Nome             | RA        |
|------------------|-----------|
| Antonio Pedro    | 815711-9  |
| Gustavo Henrik   | 815955-9  |
| Rafael Cena      | 816013-5  |
| Ryan Serato Costa| 812321-8  |
| Thales Tukaze    | 816085-2  |
| Thomas Cassiano  | 811999-8  |

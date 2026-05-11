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

| Rota                  | Descrição                                    |
|-----------------------|----------------------------------------------|
| `/login`              | Tela de login                                |
| `/register`           | Cadastro de novo usuário                     |
| `/`                   | Dashboard de desempenho (alias de /dashboard)|
| `/dashboard`          | Dashboard com KPIs, gráficos e filtros       |
| `/tracking`           | Rastreamento de rotas com Google Maps        |
| `/profile`            | Dados e configurações do usuário logado      |
| `/registration`       | Formulário de cadastro de pedido             |
| `/pedidos`            | Listagem de pedidos                          |
| `/assinaturas`        | Planos de assinatura disponíveis             |
| `/minha-assinatura`   | Assinatura ativa do usuário                  |
| `/faq`                | Perguntas frequentes (suporte)               |
| `/contato`            | Formulário de contato                        |
| `/meus-chamados`      | Histórico de chamados de suporte             |

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
| RF07  | Agendamento de coletas                 | 🔶     | View de criação existe; fluxo backend não validado                |
| RF08  | Cadastro de pedidos                    | ✅     | Formulário completo com persistência real e validação             |
| RF09  | Gestão de rotas                        | 🔶     | Rotas exibidas no dashboard; sem CRUD dedicado de rotas           |
| RF10  | Waypoints de rota                      | ✅     | CRUD completo via `WaypointController`                            |
| RF11  | Suporte (FAQ, contato, chamados)       | ✅     | Três views e `SupportController` implementados                    |

---

## Testes Automatizados

O projeto conta com **53 testes PHPUnit** (17 unitários + 36 de feature) que rodam em SQLite in-memory e cobrem autenticação, rotas protegidas, relacionamentos Eloquent e fluxo completo de pedidos.

Para executar:

```bash
php artisan test
```

Resultado esperado: `53 tests, 96 assertions` — todos passando.

### Exemplos de testes implementados

**1. Login com credenciais válidas redireciona para o dashboard**
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

**2. Rotas protegidas redirecionam usuário não autenticado**
```php
// tests/Feature/AuthTest.php
public function test_dashboard_redirects_unauthenticated_user(): void
{
    $this->get('/')->assertRedirect('/login');
}

public function test_tracking_redirects_unauthenticated_user(): void
{
    $this->get('/tracking')->assertRedirect('/login');
}
```

**3. Relacionamento `hasOne` entre Pedido e Rota**
```php
// tests/Unit/ModelRelationshipsTest.php
public function test_pedido_has_one_rota(): void
{
    [$pedido] = $this->criarPedidoComRota();

    $this->assertInstanceOf(HasOne::class, $pedido->rota());
    $this->assertNotNull($pedido->rota);
}
```

**4. Rastreamento é salvo e recuperado via relacionamento**
```php
// tests/Unit/ModelRelationshipsTest.php
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
```

**5. Cadastro de usuário persiste no banco e redireciona corretamente**
```php
// tests/Feature/AuthTest.php
public function test_register_creates_user_and_redirects_to_login(): void
{
    $this->post('/register', [
        'nome'               => 'Novo Usuário',
        'email'              => 'novo@entrego.com',
        'senha'              => 'senha123',
        'senha_confirmation' => 'senha123',
    ])->assertRedirect('/login');

    $this->assertDatabaseHas('usuarios', ['email' => 'novo@entrego.com']);
}
```

---

## To Do — Próximas Implementações

As tarefas estão ordenadas por prioridade. As de cima desbloqueiam as de baixo.

### Prioridade 1 — Fundação do banco de dados ✅ *Concluída*

- [x] Migrations para as tabelas de domínio: `pedidos`, `rotas`, `veiculos`, `enderecos`, `rastreamento`
- [x] Models Eloquent com todos os relacionamentos (`Veiculo`, `Endereco`, `Pedido`, `Rota`, `Rastreamento`)
- [x] Seeder com dados realistas: 4 motoristas, 1 cliente, 14 pedidos, 14 rotas, 12 posições de rastreamento
- [x] Suite de 46 testes automatizados (PHPUnit) — todos passando

### Prioridade 2 — Conectar UI ao banco real ✅ *Concluída*

- [x] Dashboard: queries reais com filtros por período e tipo de veículo; KPIs, tabela e 3 gráficos
- [x] Cadastro de pedidos (`/registration`): formulário completo, validação e persistência no banco
- [x] Rastreamento (`/tracking`): rota ativa carregada do banco, posição inicial real no mapa

### Prioridade 3 — Funcionalidades de gestão

- [ ] Página de gestão de rotas (CRUD completo)
- [ ] Página de histórico de entregas realizadas
- [ ] Página de motoristas (listagem e detalhes)
- [ ] Agendamento de coletas: completar backend e validar fluxo de ponta a ponta

### Prioridade 4 — Funcionalidades avançadas

- [ ] RF06: Envio de notificações por e-mail ao cliente (confirmação de coleta, status de entrega)
- [ ] Página de gestão de usuários (admin)
- [ ] Login com OAuth (Google / GitHub)

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

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

Depois rode as migrações:

```bash
php artisan migrate
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
| RF04  | Rastreamento de rotas                  | 🔶     | View com Google Maps existe; não conectado a dados reais do banco |
| RF05  | Dashboard de desempenho                | 🔶     | UI completa com filtros funcionais; dados são mock                |
| RF06  | Notificações (e-mail/SMS ao cliente)   | ❌     | Não implementado                                                  |
| RF07  | Agendamento de coletas                 | 🔶     | View de criação existe; fluxo backend não validado                |
| RF08  | Cadastro de pedidos                    | 🔶     | View e controller existem; persistência no banco não testada      |
| RF09  | Gestão de rotas                        | 🔶     | Dados de rota no dashboard são mock; sem CRUD de rotas            |
| RF10  | Waypoints de rota                      | ✅     | CRUD completo via `WaypointController`                            |
| RF11  | Suporte (FAQ, contato, chamados)       | ✅     | Três views e `SupportController` implementados                    |

---

## To Do — Próximas Implementações

As tarefas estão ordenadas por prioridade. As de cima desbloqueiam as de baixo.

### Prioridade 1 — Fundação do banco de dados *(bloqueia tudo)*

- [ ] Criar migrations Laravel para as tabelas de domínio: `pedidos`, `rotas`, `veiculos`, `enderecos`, `rastreamento`
- [ ] Criar models Eloquent correspondentes com relacionamentos
- [ ] Criar seeders com dados de teste realistas

### Prioridade 2 — Conectar UI ao banco real

- [ ] Dashboard: substituir dados mock por queries reais (coletas, eficiência, falhas por período e veículo)
- [ ] Cadastro de pedidos (`/registration`): validar persistência no banco e redirecionar corretamente
- [ ] Rastreamento (`/tracking`): carregar rotas e posições reais da tabela `rastreamento`

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

- `resources/views/demo.blade.php` — página de demonstração, pode ser removida
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

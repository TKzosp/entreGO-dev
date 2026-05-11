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

> Guia de instalação completo: https://laravel.com/docs/12.x/installation#installing-php

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

Copie o arquivo de exemplo e ajuste as credenciais do banco:

```bash
cp .env.example .env
```

Edite o `.env` com os dados do seu MySQL:

```env
DB_DATABASE=entrego_db
DB_USERNAME=root
DB_PASSWORD=sua_senha
```

### 3. Gerar chave e migrar o banco

```bash
php artisan key:generate
php artisan migrate
```

> Certifique-se de que o banco `entrego_db` já foi criado no MySQL antes de rodar o migrate.

### 4. Compilar os assets e subir o servidor

```bash
npm run build
php artisan serve
```

Acesse em: **http://localhost:8000**

> Para desenvolvimento com hot-reload, rode `npm run dev` em um terminal separado enquanto `php artisan serve` está ativo.

---

## Rotas disponíveis

| Rota        | Descrição                         |
|-------------|-----------------------------------|
| `/login`    | Tela de login                     |
| `/register` | Cadastro de novo usuário          |
| `/`         | Dashboard de desempenho           |
| `/dashboard`| Dashboard de desempenho (alias)   |
| `/tracking` | Tela de rastreamento de rotas     |
| `/profile`  | Dados do usuário logado           |

---

## To Do

### Front-end

- [x] Layout base autenticado (`layouts/app.blade.php`)
- [x] Layout guest para login/cadastro (`layouts/guest.blade.php`)
- [x] Tela de login
- [x] Tela de cadastro
- [x] Dashboard com cards de KPI (eficiência, coletas, tempo médio, falhas)
- [x] Gráficos de desempenho (tempo de entrega, coletas por veículo, falhas por tipo)
- [x] Filtros de período e categoria de veículo no dashboard
- [x] Tabela de detalhamento por rota
- [ ] Página de histórico de entregas realizadas
- [ ] Página de motoristas
- [ ] Página de gestão de usuários
- [ ] Formulário de nova entrega
- [ ] Página de cadastro de entrega
- [ ] Página de rastreamento em tempo real

### Back-end

- [x] Autenticação com guard customizado (`Usuario` model, campo `senha`)
- [x] Cadastro de usuários com validação
- [x] Migrações: `usuarios`, `remember_token`, `jobs`/`failed_jobs`
- [ ] API de banco de dados de entregas
- [ ] Diagramação e criação das tabelas de entregas
- [ ] Integração com API de rastreamento
- [ ] Login com OAuth (Google / GitHub)

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

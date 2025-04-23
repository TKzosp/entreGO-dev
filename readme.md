# To do

-   Front
    -   Paginas principais
        -   pagina de historico
            -   Entregas realizadas
        -   pagina de motoristas
        -   pagina de usuarios
        -   pagina principal (dashboard maybe?)
    -   Funções
        -   Nova entrega
        -   Pagina de rastreio
        -   Pagina de cadastro de um entrega
-   Back
    -   integração com a API de rastreio
    -   API banco de dados entregas
    -   Banco de dados entregas
        -   diagramação do banco
            -   definição de tabelas e colunas
        -   definição de qual banco utilizar (Postgrees? mysql? mongoDB?)

# Como rodar o projeto

### Pré-requisitos

-   PHP
-   Laravel
-   NodeJS
-   NPM

---

### Após Garantir que os programas acima estão instalados em sua maquina rode os seguintes comando.

-   Git clone https://github.com/TKzosp/entreGO-dev
-   composer update
-   npm install
-   npm run build
-   php artisan key:generate
-   php artisan migrate
-   php artisan serve

---

### Links Uteis

Para instalar os pré-requisitos: https://laravel.com/docs/12.x/installation#installing-php

### Rotas que estão criadas:

-   /login - Tela de Login
-   /Register - tela de registro de novo usuario
-   /resetPassword - Tela de reset de senha

### possiveis dominios

oentrego.com.br <br>
oentrego.com<br>
entregow.com<br>
entregow.app<br>

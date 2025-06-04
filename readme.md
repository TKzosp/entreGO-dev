# Como rodar o projeto

### Pré-requisitos

-   PHP
-   Laravel
-   NodeJS
-   NPM
-   MySQL

---

### Após Garantir que os programas acima estão instalados em sua maquina rode os seguintes comando.

-   Git clone https://github.com/TKzosp/entreGO-dev
-   composer update
-   npm install
-   npm run build
-   php artisan key:generate
-   php artisan migrate --force
-   php artisan serve

---

### Links Uteis

Para instalar os pré-requisitos: https://laravel.com/docs/12.x/installation#installing-php

### Rotas que estão criadas:

-   /login - Tela de Login
-   /Register - tela de registro de novo usuario
-   / - Tela principal com as consultas

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

# Membros do grupo

Antonio Pedro 815711-9\
Gustavo Henrik 815955-9\
Igor Garcia 815779-1\
Rafael Cena 816013-5\
Ryan Serato Costa 812321-8\
Thales Tukaze 816085-2\
Thomas Cassiano 811999-8

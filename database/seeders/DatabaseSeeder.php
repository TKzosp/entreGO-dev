<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Plano;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(DomainSeeder::class);

        Usuario::firstOrCreate(
            ['email' => 'teste@entrego.com'],
            [
                'nome' => 'Usuário Teste',
                'senha' => bcrypt('123456'),
            ]
        );

        Plano::firstOrCreate(
            ['nome' => 'Básico'],
            [
                'descricao' => 'Ideal para clientes iniciantes.',
                'valor' => 29.90,
                'beneficios' => [
                    'Acesso ao painel de pedidos',
                    'Suporte em horário comercial',
                    '1 benefício padrão',
                ],
                'ativo' => true,
            ]
        );

        Plano::firstOrCreate(
            ['nome' => 'Profissional'],
            [
                'descricao' => 'Mais recursos e vantagens.',
                'valor' => 59.90,
                'beneficios' => [
                    'Tudo do plano básico',
                    'Prioridade no suporte',
                    'Mais benefícios por pedido',
                ],
                'ativo' => true,
            ]
        );

        Plano::firstOrCreate(
            ['nome' => 'Premium'],
            [
                'descricao' => 'Plano completo com todos os recursos.',
                'valor' => 99.90,
                'beneficios' => [
                    'Todos os benefícios',
                    'Suporte prioritário',
                    'Relatórios avançados',
                ],
                'ativo' => true,
            ]
        );
    }
}

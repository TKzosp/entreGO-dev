<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

abstract class TestCase extends BaseTestCase
{
    protected function criarUsuario(array $atributos = []): Usuario
    {
        return Usuario::create(array_merge([
            'nome'  => 'Usuário Teste',
            'email' => 'teste_' . uniqid() . '@entrego.com',
            'senha' => Hash::make('senha123'),
            'tipo'  => 'cliente',
            'ativo' => true,
        ], $atributos));
    }

    protected function criarMotorista(array $atributos = []): Usuario
    {
        return $this->criarUsuario(array_merge(['tipo' => 'motorista'], $atributos));
    }
}

<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios'; // sua tabela customizada

    protected $primaryKey = 'id';

    public $timestamps = false; // seu schema não usa created_at/updated_at

    protected $fillable = [
        'nome',
        'email',
        'senha',
        'tipo',
        'cpf_cnpj',
        'telefone',
        'ativo',
    ];

    // Ajuste para usar o campo senha corretamente
public function getAuthPassword()
    {
        return $this->senha;
    }

    // Se estiver usando remember_token, descomente a linha abaixo
    // protected $hidden = ['senha', 'remember_token'];

    // Caso queira ocultar a senha ao retornar JSON
    protected $hidden = ['senha'];
}
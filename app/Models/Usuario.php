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

    // Caso queira ocultar a senha ao retornar JSON
    protected $hidden = ['senha'];

    // Ajuste para usar o campo senha corretamente
    public function getAuthPassword()
    {
        return $this->senha;
    }

    public function assinaturas()
    {
        return $this->hasMany(Assinatura::class, 'usuario_id');
    }

    public function assinaturaAtiva()
    {
        return $this->hasOne(Assinatura::class, 'usuario_id')
            ->where('status', 'ativa')
            ->latest('id');
    }

    public function veiculos()
    {
        return $this->hasMany(Veiculo::class, 'usuario_id');
    }

    public function enderecos()
    {
        return $this->hasMany(Endereco::class, 'usuario_id');
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'cliente_id');
    }

    public function rotasComoMotorista()
    {
        return $this->hasMany(Rota::class, 'motorista_id');
    }
}

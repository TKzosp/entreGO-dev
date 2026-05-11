<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{
    protected $table = 'enderecos';

    protected $fillable = [
        'usuario_id',
        'cep',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'latitude',
        'longitude',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function pedidosColeta()
    {
        return $this->hasMany(Pedido::class, 'endereco_coleta_id');
    }

    public function pedidosEntrega()
    {
        return $this->hasMany(Pedido::class, 'endereco_entrega_id');
    }
}

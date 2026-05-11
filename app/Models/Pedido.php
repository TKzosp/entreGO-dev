<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedidos';

    protected $fillable = [
        'cliente_id',
        'endereco_coleta_id',
        'endereco_entrega_id',
        'descricao',
        'peso',
        'volume',
        'valor',
        'data_coleta',
        'data_entrega_estimada',
        'status',
        'observacoes',
    ];

    protected $casts = [
        'data_coleta'           => 'datetime',
        'data_entrega_estimada' => 'datetime',
    ];

    public function cliente()
    {
        return $this->belongsTo(Usuario::class, 'cliente_id');
    }

    public function enderecoColeta()
    {
        return $this->belongsTo(Endereco::class, 'endereco_coleta_id');
    }

    public function enderecoEntrega()
    {
        return $this->belongsTo(Endereco::class, 'endereco_entrega_id');
    }

    public function rota()
    {
        return $this->hasOne(Rota::class, 'pedido_id');
    }
}

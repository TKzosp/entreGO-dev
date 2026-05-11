<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rota extends Model
{
    protected $table = 'rotas';

    protected $fillable = [
        'pedido_id',
        'motorista_id',
        'veiculo_id',
        'distancia',
        'tempo_estimado',
        'polyline',
        'data_inicio',
        'data_fim',
        'status',
    ];

    protected $casts = [
        'data_inicio' => 'datetime',
        'data_fim'    => 'datetime',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    public function motorista()
    {
        return $this->belongsTo(Usuario::class, 'motorista_id');
    }

    public function veiculo()
    {
        return $this->belongsTo(Veiculo::class, 'veiculo_id');
    }

    public function waypoints()
    {
        return $this->hasMany(Waypoint::class, 'rota_id')->orderBy('ordem');
    }

    public function rastreamentos()
    {
        return $this->hasMany(Rastreamento::class, 'rota_id')->latest('data_hora');
    }

    public function ultimaPosicao()
    {
        return $this->hasOne(Rastreamento::class, 'rota_id')->latestOfMany('data_hora');
    }
}

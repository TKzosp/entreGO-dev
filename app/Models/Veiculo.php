<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Veiculo extends Model
{
    protected $table = 'veiculos';

    protected $fillable = [
        'usuario_id',
        'tipo',
        'placa',
        'modelo',
        'capacidade',
        'ano',
    ];

    public function motorista()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function rotas()
    {
        return $this->hasMany(Rota::class, 'veiculo_id');
    }
}

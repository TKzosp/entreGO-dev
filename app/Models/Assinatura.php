<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assinatura extends Model
{
    protected $table = 'assinaturas';

    protected $fillable = [
        'usuario_id',
        'plano_id',
        'status',
        'data_inicio',
        'data_fim',
        'renovacao_automatica',
    ];

    protected $casts = [
        'data_inicio' => 'datetime',
        'data_fim' => 'datetime',
        'renovacao_automatica' => 'boolean',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function plano()
    {
        return $this->belongsTo(Plano::class, 'plano_id');
    }
}
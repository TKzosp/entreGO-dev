<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plano extends Model
{
    protected $table = 'planos';

    protected $fillable = [
        'nome',
        'descricao',
        'valor',
        'beneficios',
        'ativo',
    ];

    protected $casts = [
    'beneficios' => 'array',
    'ativo' => 'boolean',
    'valor' => 'decimal:2',
];

    public function assinaturas()
    {
        return $this->hasMany(Assinatura::class, 'plano_id');
    }
}
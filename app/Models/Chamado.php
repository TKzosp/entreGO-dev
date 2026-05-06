<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chamado extends Model
{
    use HasFactory;

    protected $table = 'chamados';

    protected $fillable = [
        'usuario_id',
        'nome',
        'email',
        'telefone',
        'assunto',
        'categoria',
        'prioridade',
        'mensagem',
        'status',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}

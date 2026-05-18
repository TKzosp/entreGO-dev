<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pagamento extends Model
{
    protected $table = 'pagamentos';

    protected $fillable = [
        'usuario_id',
        'plano_id',
        'assinatura_id',
        'valor',
        'status',
        'metodo',
        'referencia_externa',
    ];

    protected $casts = [
        'valor' => 'decimal:2',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function plano()
    {
        return $this->belongsTo(Plano::class, 'plano_id');
    }

    public function assinatura()
    {
        return $this->belongsTo(Assinatura::class, 'assinatura_id');
    }

    public function logs()
    {
        return $this->hasMany(PagamentoLog::class, 'pagamento_id');
    }
}

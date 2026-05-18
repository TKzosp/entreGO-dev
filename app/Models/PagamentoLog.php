<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use LogicException;

class PagamentoLog extends Model
{
    protected $table = 'pagamento_logs';

    // Apenas created_at — sem updated_at (tabela imutável)
    const UPDATED_AT = null;

    protected $fillable = [
        'pagamento_id',
        'evento',
        'payload',
        'ip',
        'user_agent',
    ];

    protected $casts = [
        'payload'    => 'array',
        'created_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::updating(function () {
            throw new LogicException('Registros de PagamentoLog são imutáveis e não podem ser atualizados.');
        });

        static::deleting(function () {
            throw new LogicException('Registros de PagamentoLog são imutáveis e não podem ser removidos.');
        });
    }

    public function pagamento()
    {
        return $this->belongsTo(Pagamento::class, 'pagamento_id');
    }

    public static function registrar(Pagamento $pagamento, string $evento, array $payload = [], ?string $ip = null, ?string $userAgent = null): self
    {
        return static::create([
            'pagamento_id' => $pagamento->id,
            'evento'       => $evento,
            'payload'      => $payload,
            'ip'           => $ip,
            'user_agent'   => $userAgent,
        ]);
    }
}

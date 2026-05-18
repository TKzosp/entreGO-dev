<?php

namespace App\Services;

use App\Models\Pagamento;
use Illuminate\Support\Str;

class PagamentoSimuladoService
{
    /**
     * Processa o pagamento de forma simulada.
     * Sempre aprova — sem integração com gateway real.
     *
     * Retorna array com:
     *   status           => 'aprovado' | 'recusado'
     *   referencia_externa => UUID gerado server-side
     *   mensagem         => descrição do resultado
     */
    public function processar(Pagamento $pagamento): array
    {
        $referencia = 'SIM-' . strtoupper(Str::uuid());

        return [
            'status'            => 'aprovado',
            'referencia_externa' => $referencia,
            'mensagem'          => 'Pagamento simulado aprovado com sucesso.',
        ];
    }
}

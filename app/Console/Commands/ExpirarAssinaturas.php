<?php

namespace App\Console\Commands;

use App\Models\Assinatura;
use Illuminate\Console\Command;

class ExpirarAssinaturas extends Command
{
    protected $signature   = 'assinaturas:expirar';
    protected $description = 'Marca como expiradas as assinaturas com data_fim no passado.';

    public function handle(): int
    {
        $total = Assinatura::where('status', 'ativa')
            ->whereNotNull('data_fim')
            ->where('data_fim', '<', now())
            ->update(['status' => 'expirada']);

        $this->info("Assinaturas expiradas: {$total}");

        return Command::SUCCESS;
    }
}

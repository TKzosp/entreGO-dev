<?php

namespace App\Policies;

use App\Models\Rota;
use App\Models\Usuario;

class RotaPolicy
{
    public function view(Usuario $user, Rota $rota): bool
    {
        return $this->isOwner($user, $rota);
    }

    public function update(Usuario $user, Rota $rota): bool
    {
        return $this->isOwner($user, $rota);
    }

    public function track(Usuario $user, Rota $rota): bool
    {
        if ($user->tipo === 'admin') {
            return true;
        }

        return $rota->motorista_id === $user->id;
    }

    private function isOwner(Usuario $user, Rota $rota): bool
    {
        if ($user->tipo === 'admin') {
            return true;
        }

        if ($rota->motorista_id === $user->id) {
            return true;
        }

        $rota->loadMissing('pedido');

        return optional($rota->pedido)->cliente_id === $user->id;
    }
}

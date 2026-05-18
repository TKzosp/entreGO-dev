<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidaCpfCnpj implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $digits = preg_replace('/\D/', '', $value);

        if (strlen($digits) === 11) {
            if (!$this->validarCpf($digits)) {
                $fail('O CPF informado é inválido.');
            }
            return;
        }

        if (strlen($digits) === 14) {
            if (!$this->validarCnpj($digits)) {
                $fail('O CNPJ informado é inválido.');
            }
            return;
        }

        $fail('O CPF ou CNPJ informado é inválido.');
    }

    private function validarCpf(string $cpf): bool
    {
        // Rejeita sequências de dígitos iguais
        if (preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }

        // Primeiro dígito verificador
        $soma = 0;
        for ($i = 0; $i < 9; $i++) {
            $soma += (int) $cpf[$i] * (10 - $i);
        }
        $resto = $soma % 11;
        $d1 = $resto < 2 ? 0 : 11 - $resto;

        if ((int) $cpf[9] !== $d1) {
            return false;
        }

        // Segundo dígito verificador
        $soma = 0;
        for ($i = 0; $i < 10; $i++) {
            $soma += (int) $cpf[$i] * (11 - $i);
        }
        $resto = $soma % 11;
        $d2 = $resto < 2 ? 0 : 11 - $resto;

        return (int) $cpf[10] === $d2;
    }

    private function validarCnpj(string $cnpj): bool
    {
        if (preg_match('/^(\d)\1{13}$/', $cnpj)) {
            return false;
        }

        $calcular = function (string $cnpj, int $tamanho): int {
            $soma = 0;
            $pos  = $tamanho - 7;
            for ($i = $tamanho; $i >= 1; $i--) {
                $soma += (int) $cnpj[$tamanho - $i] * $pos--;
                if ($pos < 2) {
                    $pos = 9;
                }
            }
            $resto = $soma % 11;
            return $resto < 2 ? 0 : 11 - $resto;
        };

        $d1 = $calcular($cnpj, 12);
        if ((int) $cnpj[12] !== $d1) {
            return false;
        }

        $d2 = $calcular($cnpj, 13);
        return (int) $cnpj[13] === $d2;
    }
}

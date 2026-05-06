<?php

namespace App\Http\Requests;

use App\Models\Usuario; // Importa o model correto
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Pegamos o ID do usuário logado para ignorá-lo na verificação de email único
        $userId = $this->user()->id;

        return [
            // Alterado de 'name' para 'nome' para bater com o BD e o input do formulário
            'nome' => ['required', 'string', 'max:255'],
            
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                // Correção Crítica: Usar Usuario::class em vez de User::class
                Rule::unique(Usuario::class)->ignore($userId),
            ],
        ];
    }
}
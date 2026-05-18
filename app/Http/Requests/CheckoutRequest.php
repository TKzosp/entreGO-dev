<?php

namespace App\Http\Requests;

use App\Rules\ValidaCpfCnpj;
use App\Support\Ufs;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'cep'      => preg_replace('/\D/', '', (string) $this->input('cep')),
            'cpf_cnpj' => preg_replace('/\D/', '', (string) $this->input('cpf_cnpj')),
            'estado'   => strtoupper((string) $this->input('estado')),
        ]);
    }

    public function rules(): array
    {
        $ufs = Ufs::list();

        return [
            'plano_id'    => ['required', 'integer', 'exists:planos,id'],

            'cpf_cnpj'    => [
                'required',
                'string',
                new ValidaCpfCnpj(),
                Rule::unique('usuarios', 'cpf_cnpj')->ignore($this->user()->id),
            ],

            'cep'         => ['required', 'string', 'regex:/^\d{8}$/'],
            'logradouro'  => ['required', 'string', 'max:100'],
            'numero'      => ['nullable', 'string', 'max:10'],
            'complemento' => ['nullable', 'string', 'max:50'],
            'bairro'      => ['required', 'string', 'max:50'],
            'cidade'      => ['required', 'string', 'max:50'],
            'estado'      => ['required', 'string', 'size:2', Rule::in($ufs)],
        ];
    }

    public function messages(): array
    {
        return [
            'plano_id.required'   => 'Selecione um plano.',
            'plano_id.exists'     => 'Plano inválido.',
            'cpf_cnpj.required'   => 'Informe o CPF ou CNPJ.',
            'cpf_cnpj.unique'     => 'Este CPF/CNPJ já está cadastrado em outra conta.',
            'cep.required'        => 'Informe o CEP.',
            'cep.regex'           => 'CEP inválido. Use somente os 8 dígitos numéricos.',
            'logradouro.required' => 'Informe o logradouro.',
            'bairro.required'     => 'Informe o bairro.',
            'cidade.required'     => 'Informe a cidade.',
            'estado.required'     => 'Informe o estado.',
            'estado.size'         => 'Use a sigla de 2 letras do estado (ex: SP).',
            'estado.in'           => 'Selecione um estado válido.',
        ];
    }
}

@extends('layouts.app')

@section('title', 'Meu Perfil')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        {{-- CARTÃO DE INFORMAÇÕES GERAIS --}}
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-xl">
                <header>
                    <h2 class="text-lg font-medium text-gray-900">
                        {{ __('Informações do Usuário') }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ __('Visualize seus dados cadastrais e nível de acesso.') }}
                    </p>
                </header>

                <div class="mt-6 space-y-4">
                    <div>
                        <x-input-label value="Nome Completo" />
                        <div class="mt-1 text-lg text-gray-800 font-semibold">
                            {{ $user->nome ?? $user->name }}
                        </div>
                    </div>

                    <div>
                        <x-input-label value="Email" />
                        <div class="mt-1 text-gray-800">
                            {{ $user->email }}
                        </div>
                    </div>

                    <div class="p-4 bg-blue-50 border-l-4 border-blue-500 rounded-r-md">
                        <x-input-label value="Tipo de Permissão / Cargo" class="text-blue-700 font-bold"/>
                        <div class="mt-1 text-lg text-blue-900 uppercase tracking-wide font-bold">
                            {{ $user->tipo ?? 'Não definido' }}
                        </div>
                        <p class="text-xs text-blue-600 mt-1">
                            Este nível de acesso define quais áreas do sistema você pode gerenciar.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- FORMULÁRIO DE ATUALIZAR DADOS (Nome/Email) --}}
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        {{-- FORMULÁRIO DE ALTERAR SENHA --}}
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        {{-- ÁREA DE PERIGO (Deletar Conta) - Opcional --}}
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>
@endsection
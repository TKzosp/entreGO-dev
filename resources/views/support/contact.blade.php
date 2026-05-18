@extends('layouts.app')

@section('title', 'Contato')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h1 class="text-3xl font-bold text-slate-900 mb-2">Abrir Chamado</h1>
        <p class="text-slate-500 mb-6">Abra um chamado de assistência técnica ou comercial diretamente pelo sistema.</p>

        @if ($errors->any())
            <div role="alert" class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-red-700">
                <p class="font-medium mb-2">Por favor corrija os erros abaixo:</p>
                <ul class="list-disc pl-5 space-y-1 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('support.contact.store') }}" class="space-y-5"
              x-data="{ mensagem: '{{ old('mensagem') }}', maxLen: 1000 }">
            @csrf

            <fieldset class="space-y-5">
                <legend class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-2">Seus dados</legend>

                <p class="text-xs text-slate-500">
                    Usaremos as informações cadastradas no seu perfil. Para alterar, acesse
                    <a href="{{ route('profile') }}" class="text-entrego-blue hover:underline">Meu perfil</a>.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <x-input-label :value="__('Nome')" />
                        <div class="block w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700">
                            {{ $usuario->nome }}
                        </div>
                    </div>

                    <div>
                        <x-input-label :value="__('E-mail')" />
                        <div class="block w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700">
                            {{ $usuario->email }}
                        </div>
                    </div>
                </div>

                <div>
                    <x-input-label :value="__('Telefone')" />
                    <div class="block w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700">
                        {{ $usuario->telefone ?: '—' }}
                    </div>
                </div>
            </fieldset>

            <fieldset class="space-y-5 pt-4 border-t border-slate-100">
                <legend class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-2">Sobre o chamado</legend>

                <div>
                    <x-input-label for="assunto" :value="__('Assunto')" required />
                    <x-text-input id="assunto" name="assunto" type="text"
                                  :hasError="$errors->has('assunto')"
                                  :value="old('assunto')"
                                  placeholder="Resuma seu chamado em poucas palavras"
                                  required maxlength="120" />
                    <x-input-error :messages="$errors->get('assunto')" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <x-input-label for="categoria" :value="__('Categoria')" required />
                        <select id="categoria" name="categoria" required
                                @class([
                                    'block w-full rounded-md shadow-sm text-sm px-3 py-2.5 transition-colors duration-150',
                                    'border-red-500 focus:border-red-500 focus:ring-red-500' => $errors->has('categoria'),
                                    'border-gray-300 focus:border-entrego-blue focus:ring-entrego-blue' => !$errors->has('categoria'),
                                ])>
                            <option value="">Selecione...</option>
                            <option value="assistencia_tecnica" @selected(old('categoria') === 'assistencia_tecnica')>Assistência técnica</option>
                            <option value="comercial" @selected(old('categoria') === 'comercial')>Comercial</option>
                        </select>
                        <x-input-error :messages="$errors->get('categoria')" />
                    </div>

                    <div>
                        <x-input-label for="prioridade" :value="__('Prioridade')" required />
                        <select id="prioridade" name="prioridade" required
                                @class([
                                    'block w-full rounded-md shadow-sm text-sm px-3 py-2.5 transition-colors duration-150',
                                    'border-red-500 focus:border-red-500 focus:ring-red-500' => $errors->has('prioridade'),
                                    'border-gray-300 focus:border-entrego-blue focus:ring-entrego-blue' => !$errors->has('prioridade'),
                                ])>
                            <option value="baixa" @selected(old('prioridade') === 'baixa')>Baixa</option>
                            <option value="media" @selected(old('prioridade', 'media') === 'media')>Média</option>
                            <option value="alta" @selected(old('prioridade') === 'alta')>Alta</option>
                        </select>
                        <x-input-error :messages="$errors->get('prioridade')" />
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <x-input-label for="mensagem" :value="__('Mensagem')" required />
                        <span class="text-xs text-slate-500">
                            <span x-text="mensagem.length"></span>/<span x-text="maxLen"></span>
                        </span>
                    </div>
                    <textarea id="mensagem" name="mensagem" rows="6"
                              x-model="mensagem"
                              x-bind:maxlength="maxLen"
                              required
                              @class([
                                  'block w-full rounded-md shadow-sm text-sm px-3 py-2.5 transition-colors duration-150 placeholder:text-gray-400',
                                  'border-red-500 focus:border-red-500 focus:ring-red-500' => $errors->has('mensagem'),
                                  'border-gray-300 focus:border-entrego-blue focus:ring-entrego-blue' => !$errors->has('mensagem'),
                              ])
                              placeholder="Descreva seu problema ou dúvida com o máximo de detalhes possível...">{{ old('mensagem') }}</textarea>
                    <x-input-error :messages="$errors->get('mensagem')" />
                </div>
            </fieldset>

            <div class="flex justify-end pt-2">
                <x-primary-button loadingText="Enviando chamado...">
                    Enviar chamado
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
@endsection

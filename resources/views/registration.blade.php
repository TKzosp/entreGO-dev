@extends('layouts.app')
@section('title', 'Cadastrar Pedido')

@section('content')
<div class="py-10">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <div class="px-4 sm:px-0">
            <h1 class="text-2xl font-semibold text-gray-800">Cadastrar Pedido</h1>
            <p class="mt-1 text-sm text-gray-500">Preencha os endereços e os dados da carga para registrar uma nova coleta.</p>
        </div>

        {{-- Alerta de sucesso --}}
        @if (session('success'))
            <div class="mx-4 sm:mx-0 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-md text-green-800 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('pedidos.store') }}" class="space-y-6">
            @csrf

            {{-- ENDEREÇO DE COLETA --}}
            <div class="p-6 bg-white shadow sm:rounded-lg">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Endereço de Coleta</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">CEP <span class="text-red-500">*</span></label>
                        <input type="text" name="coleta_cep" value="{{ old('coleta_cep') }}" maxlength="10" placeholder="00000-000"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('coleta_cep') border-red-500 @enderror">
                        @error('coleta_cep') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Estado <span class="text-red-500">*</span></label>
                        <input type="text" name="coleta_estado" value="{{ old('coleta_estado') }}" maxlength="2" placeholder="SP"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('coleta_estado') border-red-500 @enderror">
                        @error('coleta_estado') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Logradouro <span class="text-red-500">*</span></label>
                        <input type="text" name="coleta_logradouro" value="{{ old('coleta_logradouro') }}" maxlength="100" placeholder="Av. Paulista"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('coleta_logradouro') border-red-500 @enderror">
                        @error('coleta_logradouro') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Número</label>
                        <input type="text" name="coleta_numero" value="{{ old('coleta_numero') }}" maxlength="10" placeholder="100"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Complemento</label>
                        <input type="text" name="coleta_complemento" value="{{ old('coleta_complemento') }}" maxlength="50" placeholder="Sala 5"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Bairro <span class="text-red-500">*</span></label>
                        <input type="text" name="coleta_bairro" value="{{ old('coleta_bairro') }}" maxlength="50" placeholder="Bela Vista"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('coleta_bairro') border-red-500 @enderror">
                        @error('coleta_bairro') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Cidade <span class="text-red-500">*</span></label>
                        <input type="text" name="coleta_cidade" value="{{ old('coleta_cidade') }}" maxlength="50" placeholder="São Paulo"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('coleta_cidade') border-red-500 @enderror">
                        @error('coleta_cidade') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- ENDEREÇO DE ENTREGA --}}
            <div class="p-6 bg-white shadow sm:rounded-lg">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Endereço de Entrega</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">CEP <span class="text-red-500">*</span></label>
                        <input type="text" name="entrega_cep" value="{{ old('entrega_cep') }}" maxlength="10" placeholder="00000-000"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('entrega_cep') border-red-500 @enderror">
                        @error('entrega_cep') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Estado <span class="text-red-500">*</span></label>
                        <input type="text" name="entrega_estado" value="{{ old('entrega_estado') }}" maxlength="2" placeholder="SP"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('entrega_estado') border-red-500 @enderror">
                        @error('entrega_estado') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Logradouro <span class="text-red-500">*</span></label>
                        <input type="text" name="entrega_logradouro" value="{{ old('entrega_logradouro') }}" maxlength="100" placeholder="Rua das Flores"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('entrega_logradouro') border-red-500 @enderror">
                        @error('entrega_logradouro') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Número</label>
                        <input type="text" name="entrega_numero" value="{{ old('entrega_numero') }}" maxlength="10" placeholder="200"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Complemento</label>
                        <input type="text" name="entrega_complemento" value="{{ old('entrega_complemento') }}" maxlength="50" placeholder="Apto 12"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Bairro <span class="text-red-500">*</span></label>
                        <input type="text" name="entrega_bairro" value="{{ old('entrega_bairro') }}" maxlength="50" placeholder="Jardins"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('entrega_bairro') border-red-500 @enderror">
                        @error('entrega_bairro') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Cidade <span class="text-red-500">*</span></label>
                        <input type="text" name="entrega_cidade" value="{{ old('entrega_cidade') }}" maxlength="50" placeholder="São Paulo"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('entrega_cidade') border-red-500 @enderror">
                        @error('entrega_cidade') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- DADOS DA CARGA --}}
            <div class="p-6 bg-white shadow sm:rounded-lg">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Dados da Carga</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Descrição</label>
                        <input type="text" name="descricao" value="{{ old('descricao') }}" maxlength="255" placeholder="Ex: Caixas de papelaria"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Peso (kg)</label>
                        <input type="number" name="peso" value="{{ old('peso') }}" step="0.01" min="0" placeholder="5.00"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('peso') border-red-500 @enderror">
                        @error('peso') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Volume (m³)</label>
                        <input type="number" name="volume" value="{{ old('volume') }}" step="0.01" min="0" placeholder="0.50"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('volume') border-red-500 @enderror">
                        @error('volume') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Data de Coleta <span class="text-red-500">*</span></label>
                        <input type="date" name="data_coleta" value="{{ old('data_coleta') }}" min="{{ date('Y-m-d') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('data_coleta') border-red-500 @enderror">
                        @error('data_coleta') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Observações</label>
                        <textarea name="observacoes" rows="3" placeholder="Informações adicionais para o entregador..."
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('observacoes') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- AÇÕES --}}
            <div class="flex items-center justify-end gap-4 px-4 sm:px-0 pb-6">
                <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-gray-800">Cancelar</a>
                <button type="submit"
                    class="px-6 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                    Cadastrar Pedido
                </button>
            </div>
        </form>

    </div>
</div>
@endsection

<x-guest-layout>
    <div class="mb-4 text-center">
        <h2 class="text-2xl font-bold text-gray-800">Agendar Nova Coleta</h2>
        <p class="text-sm text-gray-600">Preencha as informações abaixo para criar um novo agendamento.</p>
    </div>

    <form method="POST" action="{{ route('schedule.store') }}">
        @csrf

        <div>
            <x-input-label for="address" :value="__('Endereço de Coleta:')" />
            <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address')" required autofocus />
            <x-input-error :messages="$errors->get('address')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="date" :value="__('Data e Hora:')" />
            <x-text-input id="date" class="block mt-1 w-full" type="datetime-local" name="date" required />
            <x-input-error :messages="$errors->get('date')" class="mt-2" />
        </div>
        
        <div class="mt-4">
            <x-input-label for="description" :value="__('Descrição do Item:')" />
            <textarea id="description" name="description" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ old('description') }}</textarea>
            <x-input-error :messages="$errors->get('description')" class="mt-2" />
        </div>


        <div class="flex items-center justify-between mt-6">
            <a href="{{ url()->previous() }}" class="underline text-sm text-gray-600 hover:text-gray-900">
                {{ __('Voltar') }}
            </a>

            <div>
                <x-secondary-button type="button" onclick="window.location='{{ route('dashboard') }}'" class="ms-3">
                    {{ __('Cancelar') }}
                </x-secondary-button>

                <x-primary-button class="ms-3">
                    {{ __('Agendar') }}
                </x-primary-button>
            </div>
        </div>
    </form>
</x-guest-layout>
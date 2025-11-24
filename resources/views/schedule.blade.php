<x-layouts.app title="Agendamento de Coleta">
    <div class="p-6 bg-white shadow-md rounded-lg">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Agendar Nova Coleta</h2>

        {{-- Form: O "Estado Indesejado" que o usuário pode ter entrado por engano --}}
        <form action="#" method="POST">
            @csrf
            <div class="space-y-4">
                {{-- Campos de Agendamento (Mock) --}}
                <div>
                    <x-input-label for="address" value="Endereço de Coleta" />
                    <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" required autofocus />
                </div>

                <div>
                    <x-input-label for="date" value="Data Preferencial" />
                    <x-text-input id="date" class="block mt-1 w-full" type="date" name="date" required />
                </div>

                <div>
                    <x-input-label for="description" value="Descrição da Carga" />
                    <textarea id="description" name="description" rows="3" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full"></textarea>
                </div>
            </div>

            <div class="flex items-center justify-end mt-6 space-x-4">
                {{-- Botão Principal para Ação --}}
                <x-primary-button>
                    Confirmar Agendamento
                </x-primary-button>

                {{-- A SAÍDA DE EMERGÊNCIA: Botão de Cancelamento/Voltar --}}
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                    Cancelar / Voltar
                </a>
            </div>
        </form>
    </div>
</x-layouts.app>
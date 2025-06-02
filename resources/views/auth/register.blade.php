<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Nome -->
        <div>
            <x-input-label for="nome" :value="__('Nome')" />
            <x-text-input id="nome" class="block mt-1 w-full" type="text" name="nome" :value="old('nome')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('nome')" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Senha -->
        <div class="mt-4">
            <x-input-label for="senha" :value="__('Senha')" />
            <x-text-input id="senha" class="block mt-1 w-full"
                          type="password"
                          name="senha"
                          required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('senha')" class="mt-2" />
        </div>

        <!-- Confirmação de Senha -->
        <div class="mt-4">
            <x-input-label for="senha_confirmation" :value="__('Confirme a Senha')" />
            <x-text-input id="senha_confirmation" class="block mt-1 w-full"
                          type="password"
                          name="senha_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('senha_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Já registrado?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Registrar') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
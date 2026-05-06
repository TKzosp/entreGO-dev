<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Esqueceu sua senha? Sem problema. Informe seu e-mail abaixo e enviaremos um link para você redefinir.') }}
    </div>

    <!-- Status da sessão -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- E-mail -->
        <div>
            <x-input-label for="email" :value="__('E-mail')" required />
            <x-text-input id="email"
                          type="email"
                          name="email"
                          inputmode="email"
                          :hasError="$errors->has('email')"
                          :value="old('email')"
                          placeholder="seu@email.com"
                          required autofocus />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <x-primary-button loadingText="Enviando...">
                {{ __('Enviar link de redefinição') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

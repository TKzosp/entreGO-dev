<x-guest-layout>
    @if(session('success'))
        <div role="status" class="bg-green-100 text-green-800 p-4 rounded mb-4 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Status da sessão -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" x-data="{ showPassword: false }">
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
                          required autofocus
                          autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <!-- Senha -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Senha')" required />
            <div class="relative">
                <x-text-input id="password"
                              x-bind:type="showPassword ? 'text' : 'password'"
                              type="password"
                              name="password"
                              class="pr-10"
                              :hasError="$errors->has('password')"
                              required
                              autocomplete="current-password"
                              enterkeyhint="go" />
                <button type="button"
                        @click="showPassword = !showPassword"
                        class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-gray-700 focus:outline-none focus:text-entrego-blue"
                        :aria-label="showPassword ? 'Ocultar senha' : 'Mostrar senha'">
                    <span x-show="!showPassword" class="text-xs font-medium">Mostrar</span>
                    <span x-show="showPassword" x-cloak class="text-xs font-medium">Ocultar</span>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <!-- Manter conectado -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox"
                       class="rounded border-gray-300 text-entrego-blue shadow-sm focus:ring-entrego-blue"
                       name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Manter conectado') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-6 gap-3">
            @if (Route::has('password.request'))
                <a class="text-sm text-gray-600 hover:text-entrego-blue underline focus:outline-none focus:ring-2 focus:ring-entrego-blue rounded"
                   href="{{ route('password.request') }}">
                    {{ __('Esqueceu sua senha?') }}
                </a>
            @endif

            <x-primary-button loadingText="Entrando...">
                {{ __('Entrar') }}
            </x-primary-button>
        </div>

        <!-- Link para registro -->
        <div class="mt-6 text-center text-sm">
            <span class="text-gray-600">Não tem uma conta?</span>
            <a href="{{ route('register') }}"
               class="font-medium text-entrego-blue hover:underline focus:outline-none focus:ring-2 focus:ring-entrego-blue rounded">
                {{ __('Cadastre-se') }}
            </a>
        </div>
    </form>
</x-guest-layout>

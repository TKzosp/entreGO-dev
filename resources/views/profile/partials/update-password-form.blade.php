<section x-data="{ showCurrent: false, showNew: false, showConfirm: false }">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Alterar Senha') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Use uma senha longa e única para manter sua conta segura.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Senha Atual')" required />
            <div class="relative">
                <x-text-input
                    id="update_password_current_password"
                    name="current_password"
                    :type="showCurrent ? 'text' : 'password'"
                    x-bind:type="showCurrent ? 'text' : 'password'"
                    type="password"
                    class="pr-10"
                    :hasError="$errors->updatePassword->has('current_password')"
                    autocomplete="current-password"
                    required />
                <button type="button" @click="showCurrent = !showCurrent"
                        class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-gray-700"
                        :aria-label="showCurrent ? 'Ocultar senha' : 'Mostrar senha'">
                    <span x-show="!showCurrent" class="text-xs font-medium">Mostrar</span>
                    <span x-show="showCurrent" x-cloak class="text-xs font-medium">Ocultar</span>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('Nova Senha')" required />
            <div class="relative">
                <x-text-input
                    id="update_password_password"
                    name="password"
                    x-bind:type="showNew ? 'text' : 'password'"
                    type="password"
                    class="pr-10"
                    :hasError="$errors->updatePassword->has('password')"
                    autocomplete="new-password"
                    minlength="8"
                    required />
                <button type="button" @click="showNew = !showNew"
                        class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-gray-700"
                        :aria-label="showNew ? 'Ocultar senha' : 'Mostrar senha'">
                    <span x-show="!showNew" class="text-xs font-medium">Mostrar</span>
                    <span x-show="showNew" x-cloak class="text-xs font-medium">Ocultar</span>
                </button>
            </div>
            <p class="mt-1 text-xs text-gray-500">Use no mínimo 8 caracteres.</p>
            <x-input-error :messages="$errors->updatePassword->get('password')" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirmar Nova Senha')" required />
            <div class="relative">
                <x-text-input
                    id="update_password_password_confirmation"
                    name="password_confirmation"
                    x-bind:type="showConfirm ? 'text' : 'password'"
                    type="password"
                    class="pr-10"
                    :hasError="$errors->updatePassword->has('password_confirmation')"
                    autocomplete="new-password"
                    required />
                <button type="button" @click="showConfirm = !showConfirm"
                        class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-gray-700"
                        :aria-label="showConfirm ? 'Ocultar senha' : 'Mostrar senha'">
                    <span x-show="!showConfirm" class="text-xs font-medium">Mostrar</span>
                    <span x-show="showConfirm" x-cloak class="text-xs font-medium">Ocultar</span>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button loadingText="Salvando...">{{ __('Salvar') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 4000)"
                    class="text-sm text-green-600 font-medium"
                >{{ __('Senha alterada com sucesso!') }}</p>
            @endif
        </div>
    </form>
</section>

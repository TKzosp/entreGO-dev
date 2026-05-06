<x-guest-layout>
    <form method="POST" action="{{ route('register') }}"
          x-data="{
              showSenha: false,
              showConfirma: false,
              senha: '',
              confirma: '',
              get strength() {
                  let score = 0;
                  if (this.senha.length >= 8) score++;
                  if (/[A-Z]/.test(this.senha)) score++;
                  if (/[0-9]/.test(this.senha)) score++;
                  if (/[^A-Za-z0-9]/.test(this.senha)) score++;
                  return score;
              },
              get strengthLabel() {
                  return ['', 'Fraca', 'Razoável', 'Boa', 'Forte'][this.strength] || '';
              },
              get strengthColor() {
                  return ['bg-gray-200', 'bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-green-500'][this.strength];
              },
              get senhasIguais() {
                  return this.confirma.length > 0 && this.senha === this.confirma;
              }
          }">
        @csrf

        <!-- Nome -->
        <div>
            <x-input-label for="nome" :value="__('Nome completo')" required />
            <x-text-input id="nome"
                          type="text"
                          name="nome"
                          :hasError="$errors->has('nome')"
                          :value="old('nome')"
                          placeholder="Como devemos te chamar"
                          required autofocus
                          autocomplete="name" />
            <x-input-error :messages="$errors->get('nome')" />
        </div>

        <!-- E-mail -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('E-mail')" required />
            <x-text-input id="email"
                          type="email"
                          name="email"
                          inputmode="email"
                          :hasError="$errors->has('email')"
                          :value="old('email')"
                          placeholder="seu@email.com"
                          required
                          autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <!-- Senha -->
        <div class="mt-4">
            <x-input-label for="senha" :value="__('Senha')" required />
            <div class="relative">
                <x-text-input id="senha"
                              x-bind:type="showSenha ? 'text' : 'password'"
                              type="password"
                              name="senha"
                              x-model="senha"
                              class="pr-10"
                              :hasError="$errors->has('senha')"
                              minlength="8"
                              required
                              autocomplete="new-password" />
                <button type="button"
                        @click="showSenha = !showSenha"
                        class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-gray-700 focus:outline-none focus:text-entrego-blue"
                        :aria-label="showSenha ? 'Ocultar senha' : 'Mostrar senha'">
                    <span x-show="!showSenha" class="text-xs font-medium">Mostrar</span>
                    <span x-show="showSenha" x-cloak class="text-xs font-medium">Ocultar</span>
                </button>
            </div>

            <!-- Indicador de força -->
            <div x-show="senha.length > 0" x-cloak class="mt-2">
                <div class="flex gap-1 h-1.5">
                    <template x-for="i in 4" :key="i">
                        <div class="flex-1 rounded-full transition-colors duration-200"
                             :class="i <= strength ? strengthColor : 'bg-gray-200'"></div>
                    </template>
                </div>
                <p class="mt-1 text-xs text-gray-600">
                    Força: <span class="font-medium" x-text="strengthLabel"></span>
                </p>
            </div>

            <p class="mt-1 text-xs text-gray-500">Mínimo 8 caracteres. Use letras, números e símbolos para mais segurança.</p>
            <x-input-error :messages="$errors->get('senha')" />
        </div>

        <!-- Confirmação de Senha -->
        <div class="mt-4">
            <x-input-label for="senha_confirmation" :value="__('Confirme a senha')" required />
            <div class="relative">
                <x-text-input id="senha_confirmation"
                              x-bind:type="showConfirma ? 'text' : 'password'"
                              type="password"
                              name="senha_confirmation"
                              x-model="confirma"
                              class="pr-10"
                              :hasError="$errors->has('senha_confirmation')"
                              required
                              autocomplete="new-password" />
                <button type="button"
                        @click="showConfirma = !showConfirma"
                        class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-gray-700 focus:outline-none focus:text-entrego-blue"
                        :aria-label="showConfirma ? 'Ocultar senha' : 'Mostrar senha'">
                    <span x-show="!showConfirma" class="text-xs font-medium">Mostrar</span>
                    <span x-show="showConfirma" x-cloak class="text-xs font-medium">Ocultar</span>
                </button>
            </div>

            <!-- Feedback de senhas iguais -->
            <p x-show="confirma.length > 0 && !senhasIguais" x-cloak
               class="mt-1 text-xs text-red-600">
                As senhas não coincidem.
            </p>
            <p x-show="senhasIguais" x-cloak
               class="mt-1 text-xs text-green-600">
                ✓ As senhas coincidem.
            </p>

            <x-input-error :messages="$errors->get('senha_confirmation')" />
        </div>

        <div class="flex items-center justify-between mt-6 gap-3">
            <a class="text-sm text-gray-600 hover:text-entrego-blue underline focus:outline-none focus:ring-2 focus:ring-entrego-blue rounded"
               href="{{ route('login') }}">
                {{ __('Já tenho conta') }}
            </a>

            <x-primary-button loadingText="Cadastrando...">
                {{ __('Criar conta') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

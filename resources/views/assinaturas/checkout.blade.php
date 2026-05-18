@extends('layouts.app')

@section('title', 'Checkout — ' . $plano->nome)

@section('content')
<div class="container mx-auto px-4 sm:px-6 py-8">
    <div class="max-w-4xl mx-auto">

        <div class="mb-6">
            <a href="{{ route('assinaturas.index') }}"
               class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-slate-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Voltar aos planos
            </a>
        </div>

        <h1 class="text-2xl font-bold text-slate-900 mb-6">Finalizar assinatura</h1>

        @if($errors->has('pagamento'))
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800 text-sm">
                {{ $errors->first('pagamento') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Formulário de faturamento — único x-data para evitar escopos Alpine aninhados --}}
            <div class="lg:col-span-2 space-y-6">

                <form action="{{ route('assinaturas.processar') }}" method="POST"
                      id="form-checkout"
                      x-data="{
                          loading: false,
                          cpfCnpj: '{{ old('cpf_cnpj', $usuario->cpf_cnpj ?? '') }}',
                          cep: '{{ old('cep', $enderecoFaturamento?->cep ?? '') }}',
                          logradouro: '{{ old('logradouro', $enderecoFaturamento?->logradouro ?? '') }}',
                          bairro: '{{ old('bairro', $enderecoFaturamento?->bairro ?? '') }}',
                          cidade: '{{ old('cidade', $enderecoFaturamento?->cidade ?? '') }}',
                          estado: '{{ old('estado', $enderecoFaturamento?->estado ?? '') }}',
                          buscandoCep: false,
                          cepErro: '',
                          formatarCpfCnpj() {
                              let v = this.cpfCnpj.replace(/\D/g, '').slice(0, 14);
                              if (v.length <= 11) {
                                  v = v.replace(/(\d{3})(\d)/, '$1.$2')
                                       .replace(/(\d{3})(\d)/, '$1.$2')
                                       .replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                              } else {
                                  v = v.replace(/^(\d{2})(\d)/, '$1.$2')
                                       .replace(/^(\d{2}\.\d{3})(\d)/, '$1.$2')
                                       .replace(/\.(\d{3})(\d)/, '.$1/$2')
                                       .replace(/(\d{4})(\d{1,2})$/, '$1-$2');
                              }
                              this.cpfCnpj = v;
                          },
                          formatarCep() {
                              let v = this.cep.replace(/\D/g, '').slice(0, 8);
                              if (v.length > 5) v = v.replace(/^(\d{5})(\d)/, '$1-$2');
                              this.cep = v;
                              const digits = v.replace(/\D/g, '');
                              if (digits.length === 8) this.buscarCep(digits);
                          },
                          buscarCep(digits) {
                              this.cepErro = '';
                              this.buscandoCep = true;
                              fetch('https://viacep.com.br/ws/' + digits + '/json/')
                                  .then(r => r.json())
                                  .then(d => {
                                      if (d.erro) {
                                          this.cepErro = 'CEP não encontrado.';
                                          return;
                                      }
                                      this.logradouro = d.logradouro ?? '';
                                      this.bairro = d.bairro ?? '';
                                      this.cidade = d.localidade ?? '';
                                      this.estado = d.uf ?? '';
                                  })
                                  .catch(() => { this.cepErro = 'Falha ao consultar CEP.'; })
                                  .finally(() => { this.buscandoCep = false; });
                          }
                      }"
                      @submit="loading = true">
                    @csrf
                    <input type="hidden" name="plano_id" value="{{ $plano->id }}">

                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-5">
                        <h2 class="text-base font-semibold text-slate-900">Dados de faturamento</h2>

                        {{-- CPF/CNPJ --}}
                        <div>
                            <label for="cpf_cnpj" class="block text-sm font-medium text-slate-700 mb-1">CPF / CNPJ</label>
                            <input type="text" id="cpf_cnpj" name="cpf_cnpj"
                                   x-model="cpfCnpj"
                                   @input="formatarCpfCnpj()"
                                   inputmode="numeric"
                                   placeholder="000.000.000-00"
                                   maxlength="18"
                                   class="w-full rounded-lg border @error('cpf_cnpj') border-red-400 @else border-slate-300 @enderror px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-entrego-blue">
                            @error('cpf_cnpj')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- CEP --}}
                        <div>
                            <label for="cep" class="block text-sm font-medium text-slate-700 mb-1">CEP</label>
                            <div class="flex gap-2">
                                <input type="text" id="cep" name="cep"
                                       x-model="cep"
                                       @input="formatarCep()"
                                       inputmode="numeric"
                                       placeholder="00000-000"
                                       maxlength="9"
                                       class="flex-1 rounded-lg border @error('cep') border-red-400 @else border-slate-300 @enderror px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-entrego-blue">
                                <span x-show="buscandoCep" class="flex items-center text-xs text-slate-400">Buscando...</span>
                            </div>
                            <p x-show="cepErro" x-cloak class="mt-1 text-xs text-amber-600" x-text="cepErro"></p>
                            @error('cep')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Logradouro + Número --}}
                        <div class="grid grid-cols-3 gap-3">
                            <div class="col-span-2">
                                <label for="logradouro" class="block text-sm font-medium text-slate-700 mb-1">Logradouro</label>
                                <input type="text" id="logradouro" name="logradouro"
                                       x-model="logradouro"
                                       placeholder="Rua, Avenida..."
                                       maxlength="100"
                                       class="w-full rounded-lg border @error('logradouro') border-red-400 @else border-slate-300 @enderror px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-entrego-blue">
                                @error('logradouro')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="numero" class="block text-sm font-medium text-slate-700 mb-1">Número</label>
                                <input type="text" id="numero" name="numero"
                                       value="{{ old('numero', $enderecoFaturamento?->numero ?? '') }}"
                                       placeholder="123"
                                       maxlength="10"
                                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-entrego-blue">
                            </div>
                        </div>

                        {{-- Complemento --}}
                        <div>
                            <label for="complemento" class="block text-sm font-medium text-slate-700 mb-1">Complemento <span class="text-slate-400 font-normal">(opcional)</span></label>
                            <input type="text" id="complemento" name="complemento"
                                   value="{{ old('complemento', $enderecoFaturamento?->complemento ?? '') }}"
                                   placeholder="Apto, bloco..."
                                   maxlength="50"
                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-entrego-blue">
                        </div>

                        {{-- Bairro --}}
                        <div>
                            <label for="bairro" class="block text-sm font-medium text-slate-700 mb-1">Bairro</label>
                            <input type="text" id="bairro" name="bairro"
                                   x-model="bairro"
                                   maxlength="50"
                                   class="w-full rounded-lg border @error('bairro') border-red-400 @else border-slate-300 @enderror px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-entrego-blue">
                            @error('bairro')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Cidade + Estado --}}
                        <div class="grid grid-cols-3 gap-3">
                            <div class="col-span-2">
                                <label for="cidade" class="block text-sm font-medium text-slate-700 mb-1">Cidade</label>
                                <input type="text" id="cidade" name="cidade"
                                       x-model="cidade"
                                       maxlength="50"
                                       class="w-full rounded-lg border @error('cidade') border-red-400 @else border-slate-300 @enderror px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-entrego-blue">
                                @error('cidade')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="estado" class="block text-sm font-medium text-slate-700 mb-1">Estado</label>
                                <select id="estado" name="estado"
                                        x-model="estado"
                                        class="w-full rounded-lg border @error('estado') border-red-400 @else border-slate-300 @enderror px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-entrego-blue">
                                    <option value="">UF</option>
                                    @foreach(['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'] as $uf)
                                        <option value="{{ $uf }}">{{ $uf }}</option>
                                    @endforeach
                                </select>
                                @error('estado')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Pagamento simulado (apenas visual) --}}
                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-4 mt-6">
                        <div class="flex items-center justify-between">
                            <h2 class="text-base font-semibold text-slate-900">Cartão de crédito</h2>
                            <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 text-amber-800 text-xs font-medium px-2.5 py-0.5">
                                Ambiente simulado
                            </span>
                        </div>

                        <p class="text-xs text-slate-500 bg-amber-50 border border-amber-100 rounded-lg px-3 py-2">
                            Este é um ambiente de simulação. Nenhum dado de cartão é enviado ou processado.
                        </p>

                        {{-- Campos visuais — não são <input>, não entram no request --}}
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Número do cartão</label>
                                <div class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-400 tracking-widest select-none cursor-default">
                                    •••• •••• •••• ••••
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Validade</label>
                                    <div class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-400 select-none cursor-default">
                                        MM/AA
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">CVV</label>
                                    <div class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-400 select-none cursor-default">
                                        •••
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Botão submit --}}
                    <div class="mt-6">
                        <button type="submit" :disabled="loading" :aria-busy="loading"
                                class="w-full inline-flex items-center justify-center gap-2 min-h-[52px] py-3.5 px-5 bg-entrego-blue border border-transparent rounded-lg font-medium text-base text-white hover:bg-entrego-blue-600 active:bg-entrego-blue-700 focus:outline-none focus:ring-2 focus:ring-entrego-blue focus:ring-offset-2 disabled:opacity-70 disabled:cursor-not-allowed transition-colors duration-150">
                            <svg x-show="loading" x-cloak class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>
                            <span x-show="!loading">Confirmar assinatura</span>
                            <span x-show="loading" x-cloak>Processando...</span>
                        </button>
                    </div>
                </form>
            </div>{{-- /col-span-2 --}}

            {{-- Resumo do plano (read-only, server-side) --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 sticky top-6">
                    <h2 class="text-base font-semibold text-slate-900 mb-4">Resumo do pedido</h2>

                    <div class="space-y-3">
                        <div class="flex justify-between items-start">
                            <span class="text-sm text-slate-600">Plano</span>
                            <span class="text-sm font-medium text-slate-900 text-right">{{ $plano->nome }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-slate-600">Período</span>
                            <span class="text-sm text-slate-700">30 dias</span>
                        </div>
                        <div class="border-t border-slate-100 pt-3 flex justify-between items-center">
                            <span class="text-sm font-semibold text-slate-900">Total</span>
                            <span class="text-lg font-bold text-entrego-blue">
                                R$ {{ number_format($plano->valor, 2, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    @if(!empty($plano->beneficios))
                        <div class="mt-5 pt-5 border-t border-slate-100">
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-3">Benefícios</p>
                            <ul class="space-y-1.5">
                                @foreach($plano->beneficios as $beneficio)
                                    <li class="flex items-start gap-2 text-xs text-slate-600">
                                        <span class="text-slate-400 flex-shrink-0 mt-0.5">–</span>
                                        {{ $beneficio }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <p class="mt-5 text-xs text-slate-400 text-center">
                        Renovação automática após 30 dias. Cancele quando quiser.
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

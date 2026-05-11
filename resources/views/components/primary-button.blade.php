@props([
    'type' => 'submit',
    'loadingText' => null,
])

<button
    type="{{ $type }}"
    x-data="{ loading: false }"
    @if($type === 'submit')
        {{--
            Escuta o evento 'submit' do formulário pai, não o 'click' do botão.
            O evento 'submit' só dispara após o browser ter validado todos os campos
            e já iniciado o envio — desabilitar o botão nesse momento não cancela
            o request, apenas impede cliques duplicados.
        --}}
        x-init="$el.closest('form')?.addEventListener('submit', () => { loading = true })"
    @endif
    :disabled="loading"
    :aria-busy="loading"
    {{ $attributes->merge([
        'class' => 'inline-flex items-center justify-center gap-2 min-h-[44px] px-5 py-2.5 bg-entrego-blue border border-transparent rounded-lg font-medium text-sm text-white hover:bg-entrego-blue-600 focus:outline-none focus:ring-2 focus:ring-entrego-blue focus:ring-offset-2 active:bg-entrego-blue-700 disabled:opacity-70 disabled:cursor-not-allowed transition-colors duration-150'
    ]) }}
>
    <svg x-show="loading" x-cloak class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24" aria-hidden="true">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor"
              d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
    </svg>

    <span x-show="!loading">{{ $slot }}</span>
    <span x-show="loading" x-cloak>{{ $loadingText ?? 'Enviando...' }}</span>
</button>

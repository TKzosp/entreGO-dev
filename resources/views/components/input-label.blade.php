@props([
    'value' => null,
    'required' => false,
])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-gray-700 mb-1']) }}>
    {{ $value ?? $slot }}
    @if($required)
        <span class="text-red-500" aria-hidden="true">*</span>
        <span class="sr-only">obrigatório</span>
    @endif
</label>

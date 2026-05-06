@props([
    'disabled' => false,
    'hasError' => false,
])

@php
    $base = 'block w-full rounded-md shadow-sm text-sm px-3 py-2.5 transition-colors duration-150 placeholder:text-gray-400 disabled:opacity-60 disabled:cursor-not-allowed';

    $state = $hasError
        ? 'border-red-500 text-red-900 focus:border-red-500 focus:ring-red-500'
        : 'border-gray-300 focus:border-entrego-blue focus:ring-entrego-blue';
@endphp

<input
    @disabled($disabled)
    @if($hasError) aria-invalid="true" @endif
    {{ $attributes->merge(['class' => "$base $state"]) }}
>

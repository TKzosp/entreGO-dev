@props(['label', 'type' => 'text', 'name'])

<div class="text-left">
    <label for="{{ $name }}" class="block mb-2 font-bold text-sm">{{ $label }}</label>
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        {{ $attributes->merge([
            'class' => 'w-[calc(100%-24px)] mx-auto p-3 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-200'
        ]) }}
    />
</div>
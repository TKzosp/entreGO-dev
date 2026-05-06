@props(['messages'])

@if ($messages)
    @php $messages = (array) $messages; @endphp

    <div
        role="alert"
        aria-live="polite"
        {{ $attributes->merge(['class' => 'mt-1.5 text-sm text-red-600 flex items-start gap-1.5']) }}
    >
        <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
            <path fill-rule="evenodd"
                  d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                  clip-rule="evenodd" />
        </svg>

        @if(count($messages) === 1)
            <span>{{ $messages[0] }}</span>
        @else
            <ul class="space-y-0.5 list-disc list-inside">
                @foreach($messages as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        @endif
    </div>
@endif

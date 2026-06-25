@props([
    'title' => null,
    'subtitle' => null,
    'padding' => 'md',
    'hover' => false,
])

@php
    $paddingClasses = match($padding) {
        'none' => '',
        'sm' => 'p-4',
        'md' => 'p-6',
        'lg' => 'p-8',
        default => 'p-6',
    };
@endphp

<div {{ $attributes->merge(['class' => 'bg-white rounded-lg shadow-sm border border-gray-200' . ($hover ? ' hover:shadow-md transition-shadow' : '')]) }}>
    @if($title || $subtitle)
        <div class="border-b border-gray-200 px-6 py-4">
            @if($title)
                <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
            @endif
            @if($subtitle)
                <p class="text-sm text-gray-500 mt-1">{{ $subtitle }}</p>
            @endif
        </div>
    @endif
    <div class="{{ $paddingClasses }}">
        {{ $slot }}
    </div>
</div>

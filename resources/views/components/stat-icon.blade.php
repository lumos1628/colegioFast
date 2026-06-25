@props([
    'icon' => null,
    'color' => 'blue',
])

@php
    $colorClasses = match($color) {
        'blue' => 'bg-blue-100 text-blue-600',
        'green' => 'bg-green-100 text-green-600',
        'red' => 'bg-red-100 text-red-600',
        'yellow' => 'bg-yellow-100 text-yellow-600',
        'purple' => 'bg-purple-100 text-purple-600',
        'indigo' => 'bg-indigo-100 text-indigo-600',
        'pink' => 'bg-pink-100 text-pink-600',
        default => 'bg-blue-100 text-blue-600',
    };
@endphp

<div {{ $attributes->merge(['class' => 'w-12 h-12 rounded-lg flex items-center justify-center ' . $colorClasses]) }}>
    @if($icon)
        {!! $icon !!}
    @else
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>
    @endif
</div>

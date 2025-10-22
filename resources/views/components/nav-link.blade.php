@props([
    'href' => '#',
    'active' => false,
    'icon' => null,
])

@php
    $classes = $active
        ? 'flex items-center px-4 py-3 text-sm font-medium text-indigo-600 bg-indigo-50 border-l-4 border-indigo-600'
        : 'flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-indigo-600 border-l-4 border-transparent transition';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    @if($icon)
        <span class="mr-3">
            {!! $icon !!}
        </span>
    @endif
    {{ $slot }}
</a>


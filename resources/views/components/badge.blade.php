@props(['variant' => 'neutral'])

@php
    $classes = match($variant) {
        'success' => 'badge-success',
        'warning' => 'badge-warning',
        'danger' => 'badge-danger',
        'primary' => 'badge-primary',
        default => 'badge-neutral',
    };
@endphp

<span {{ $attributes->merge(['class' => 'badge ' . $classes]) }}>
    {{ $slot }}
</span>

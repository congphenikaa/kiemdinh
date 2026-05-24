@props(['type' => 'success', 'message'])

@php
    $styles = [
        'success' => 'border-emerald-200 bg-emerald-50 text-emerald-800',
        'error' => 'border-red-200 bg-red-50 text-red-800',
    ];
    $icons = [
        'success' => 'fa-circle-check',
        'error' => 'fa-circle-exclamation',
    ];
@endphp

<div
    x-data="flashMessages"
    x-show="visible"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    {{ $attributes->merge(['class' => 'mb-4 flex items-start gap-3 rounded-lg border px-4 py-3 text-sm ' . ($styles[$type] ?? $styles['success'])]) }}
    role="alert"
>
    <i class="fas {{ $icons[$type] ?? $icons['success'] }} mt-0.5 shrink-0"></i>
    <p class="flex-1">{{ $message }}</p>
    <button type="button" @click="visible = false" class="shrink-0 opacity-60 hover:opacity-100" aria-label="Đóng">
        <i class="fas fa-times"></i>
    </button>
</div>

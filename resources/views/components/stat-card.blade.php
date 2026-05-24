@props([
    'label',
    'value',
    'icon' => 'fa-chart-bar',
    'color' => 'primary',
])

@php
    $colors = [
        'primary' => 'bg-primary-50 text-primary-600',
        'emerald' => 'bg-emerald-50 text-emerald-600',
        'amber' => 'bg-amber-50 text-amber-600',
        'violet' => 'bg-violet-50 text-violet-600',
    ];
    $iconBg = $colors[$color] ?? $colors['primary'];
@endphp

<div {{ $attributes->merge(['class' => 'app-card p-5']) }}>
    <div class="flex items-start justify-between gap-4">
        <div>
            <p class="text-sm font-medium text-slate-500">{{ $label }}</p>
            <p class="mt-2 text-3xl font-semibold tracking-tight text-slate-900">{{ $value }}</p>
        </div>
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $iconBg }}">
            <i class="fas {{ $icon }} text-lg"></i>
        </div>
    </div>
    @if(isset($footer))
        <div class="mt-4 border-t border-slate-100 pt-3 text-sm text-slate-500">
            {{ $footer }}
        </div>
    @endif
</div>

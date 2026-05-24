@props([
    'label',
    'for' => null,
    'hint' => null,
    'required' => false,
])

<div {{ $attributes->merge(['class' => 'grid grid-cols-1 gap-4 md:grid-cols-3']) }}>
    <div class="md:col-span-1">
        <label @if($for) for="{{ $for }}" @endif class="form-label">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
        @if($hint)
            <p class="mt-1 text-xs text-slate-500">{{ $hint }}</p>
        @endif
    </div>
    <div class="md:col-span-2">
        {{ $slot }}
    </div>
</div>

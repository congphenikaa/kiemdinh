@props(['title' => null, 'description' => null])

<div {{ $attributes->merge(['class' => 'space-y-4']) }}>
    @if($title)
        <div class="border-b border-slate-100 pb-3">
            <h3 class="text-sm font-semibold text-slate-900">{{ $title }}</h3>
            @if($description)
                <p class="mt-0.5 text-xs text-slate-500">{{ $description }}</p>
            @endif
        </div>
    @endif
    <div class="space-y-4">
        {{ $slot }}
    </div>
</div>

@props(['title' => null, 'description' => null])

<div {{ $attributes->merge(['class' => 'app-card overflow-hidden']) }}>
    @if($title || isset($actions))
        <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="min-w-0">
                @if($title)
                    <h2 class="text-base font-semibold text-slate-900">{{ $title }}</h2>
                @endif
                @if($description)
                    <p class="mt-0.5 text-sm text-slate-500">{{ $description }}</p>
                @endif
            </div>
            @isset($actions)
                <div class="flex shrink-0 flex-wrap items-center gap-2">{{ $actions }}</div>
            @endisset
        </div>
    @endif
    <div class="p-5 sm:p-6">
        {{ $slot }}
    </div>
</div>

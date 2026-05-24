@props(['title' => 'Lọc dữ liệu'])

<div {{ $attributes->merge(['class' => 'app-card p-5 sm:p-6']) }}>
    <h3 class="mb-4 text-sm font-semibold text-slate-900">{{ $title }}</h3>
    {{ $slot }}
</div>

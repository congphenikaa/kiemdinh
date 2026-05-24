@extends('layouts.app')

@section('title', 'Quản lý ' . $entityName)

@section('breadcrumb', $entityName)

@section('content')
<div class="content-section">
    <div class="page-toolbar">
        <div>
            <p class="text-sm text-slate-500">Danh sách</p>
            <h2 class="text-xl font-semibold text-slate-900">{{ $entityName }}</h2>
        </div>
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
            <div class="search-input-wrap">
                <input type="text" placeholder="Tìm kiếm..." class="search-input">
                <i class="fas fa-search pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
            </div>
            @if ($routePrefix !== 'teaching-assignments')
                <a href="{{ route($routePrefix.'.create') }}" class="btn-primary shrink-0">
                    <i class="fas fa-plus"></i>
                    Thêm mới
                </a>
            @endif
        </div>
    </div>

    <div class="app-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="data-table min-w-full">
                <thead>
                    <tr>
                        @yield('table_headers')
                    </tr>
                </thead>
                <tbody>
                    @yield('table_rows')
                </tbody>
            </table>
        </div>

        @php
            $paginator = collect(get_defined_vars())->first(
                fn ($v) => $v instanceof \Illuminate\Contracts\Pagination\Paginator
            );
        @endphp
        @if($paginator && $paginator->total() > 0)
            <div class="flex flex-col items-center justify-between gap-3 border-t border-slate-100 bg-slate-50/50 px-4 py-3 text-sm text-slate-600 md:flex-row">
                <div class="text-center md:text-left">
                    Hiển thị <span class="font-medium text-slate-900">{{ $paginator->firstItem() }}</span>–<span class="font-medium text-slate-900">{{ $paginator->lastItem() }}</span>
                    trong tổng <span class="font-medium text-slate-900">{{ $paginator->total() }}</span> bản ghi
                </div>
                <div>{{ $paginator->appends(request()->query())->links() }}</div>
            </div>
        @endif
    </div>
</div>

<form id="delete-form" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
    const entityName = @json($entityName);
    const routePrefix = @json($routePrefix);

    document.addEventListener('DOMContentLoaded', function () {
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.btn-delete');
            if (!btn) return;
            e.preventDefault();
            const id = btn.dataset.id;
            window.showConfirmModal(
                'Xóa ' + entityName,
                'Bạn có chắc chắn muốn xóa mục này? Hành động không thể hoàn tác.',
                function () {
                    const form = document.getElementById('delete-form');
                    form.action = `/${routePrefix}/${id}`;
                    form.submit();
                }
            );
        });
    });
</script>
@endpush

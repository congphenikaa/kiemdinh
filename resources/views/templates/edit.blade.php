@extends('layouts.app')

@section('title', 'Sửa ' . $entityName)

@section('breadcrumb', $entityName . ' / Chỉnh sửa')

@section('content')
<div class="mx-auto max-w-3xl">
    <div class="page-toolbar">
        <div>
            <p class="text-sm text-slate-500">Chỉnh sửa</p>
            <h2 class="text-xl font-semibold text-slate-900">{{ $entityName }}</h2>
        </div>
        <a href="{{ route($routePrefix.'.index') }}" class="btn-secondary shrink-0">
            <i class="fas fa-arrow-left"></i>
            Quay lại
        </a>
    </div>

    <div class="app-card p-6 sm:p-8">
        <form action="{{ route($routePrefix.'.update', $model) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            @yield('form_fields')

            <div class="flex flex-col-reverse justify-end gap-3 border-t border-slate-100 pt-6 sm:flex-row">
                <a href="{{ route($routePrefix.'.index') }}" class="btn-secondary">
                    <i class="fas fa-times"></i>
                    Hủy
                </a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-check"></i>
                    Cập nhật
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

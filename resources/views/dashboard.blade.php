@extends('layouts.app')

@section('title', 'Tổng quan')

@section('breadcrumb', 'Trang chủ')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <x-stat-card label="Bằng cấp" :value="$degrees->count()" icon="fa-graduation-cap" color="primary" />
        <x-stat-card label="Khoa" :value="$faculties->count()" icon="fa-building-columns" color="emerald" />
        <x-stat-card label="Giảng viên" :value="$teachers->count()" icon="fa-chalkboard-user" color="violet" />
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <a href="{{ route('teachers.create') }}" class="app-card flex items-center gap-3 p-4 transition hover:border-primary-200 hover:shadow-md">
            <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary-50 text-primary-600">
                <i class="fas fa-user-plus"></i>
            </span>
            <span class="text-sm font-medium text-slate-800">Thêm giảng viên</span>
        </a>
        <a href="{{ route('payment-calculations.index') }}" class="app-card flex items-center gap-3 p-4 transition hover:border-primary-200 hover:shadow-md">
            <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                <i class="fas fa-calculator"></i>
            </span>
            <span class="text-sm font-medium text-slate-800">Tính thanh toán</span>
        </a>
        <a href="{{ route('reports.summary') }}" class="app-card flex items-center gap-3 p-4 transition hover:border-primary-200 hover:shadow-md">
            <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                <i class="fas fa-chart-line"></i>
            </span>
            <span class="text-sm font-medium text-slate-800">Báo cáo tổng hợp</span>
        </a>
    </div>

    <div class="app-card overflow-hidden">
        <div class="border-b border-slate-100 px-5 py-4">
            <h2 class="text-base font-semibold text-slate-900">Giảng viên thêm gần đây</h2>
            <p class="mt-0.5 text-sm text-slate-500">5 bản ghi mới nhất</p>
        </div>
        <div class="overflow-x-auto">
            <table class="data-table min-w-full">
                <thead>
                    <tr>
                        <th>Mã số</th>
                        <th>Họ tên</th>
                        <th>Khoa</th>
                        <th>Bằng cấp</th>
                        <th>Ngày thêm</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTeachers as $teacher)
                        <tr>
                            <td class="font-mono text-xs text-slate-500">{{ $teacher->code }}</td>
                            <td class="font-medium text-slate-900">{{ $teacher->name }}</td>
                            <td>{{ $teacher->faculty->short_name ?? '—' }}</td>
                            <td>{{ $teacher->degree->short_name ?? '—' }}</td>
                            <td>{{ $teacher->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-500">
                                <i class="fas fa-inbox mb-2 text-2xl text-slate-300"></i>
                                <p>Chưa có giảng viên nào</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($recentTeachers->isNotEmpty())
            <div class="border-t border-slate-100 px-5 py-3 text-right">
                <a href="{{ route('teachers.index') }}" class="text-sm font-medium text-primary-600 hover:text-primary-700">
                    Xem tất cả <i class="fas fa-arrow-right ml-1 text-xs"></i>
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

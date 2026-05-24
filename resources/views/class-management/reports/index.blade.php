@extends('layouts.app')

@section('title', 'Thống kê lớp học')
@section('breadcrumb', 'Lớp học / Thống kê')

@section('content')
<div class="space-y-6">
    <x-filter-panel>
        <form method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div>
                <label class="form-label">Học kỳ</label>
                <select name="semester" class="form-input">
                    <option value="">Tất cả học kỳ</option>
                    @foreach($semesters as $semester)
                        <option value="{{ $semester->id }}" {{ request('semester') == $semester->id ? 'selected' : '' }}>
                            {{ $semester->name }} ({{ $semester->academicYear->name }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Trạng thái</label>
                <select name="status" class="form-input">
                    <option value="all" {{ request('status', 'all') == 'all' ? 'selected' : '' }}>Tất cả</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                    <option value="ended" {{ request('status') == 'ended' ? 'selected' : '' }}>Đã kết thúc</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="btn-primary w-full"><i class="fas fa-filter"></i> Lọc</button>
            </div>
        </form>
    </x-filter-panel>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-stat-card label="Tổng lớp" :value="$totalClasses" icon="fa-school" color="primary" />
        <x-stat-card label="Tổng sinh viên" :value="$totalStudents" icon="fa-user-graduate" color="emerald" />
        <x-stat-card label="Điểm danh TB" :value="round($avgAttendance, 1) . '%'" icon="fa-clipboard-check" color="violet" />
        <x-stat-card label="Buổi đã hoàn thành" :value="$completedSessions" icon="fa-calendar-check" color="amber" />
    </div>

    @if($byStatus->isNotEmpty())
        <x-page-card title="Theo trạng thái">
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                @foreach($byStatus as $status => $count)
                    <div class="rounded-lg border border-slate-100 bg-slate-50 p-4">
                        <p class="text-sm text-slate-500">{{ ucfirst($status) }}</p>
                        <p class="mt-1 text-2xl font-semibold text-slate-900">{{ $count }}</p>
                    </div>
                @endforeach
            </div>
        </x-page-card>
    @endif

    <x-page-card title="Theo khoa">
        <div class="overflow-x-auto">
            <table class="data-table min-w-full">
                <thead><tr><th>Khoa</th><th>Số lớp</th><th>Sinh viên</th></tr></thead>
                <tbody>
                    @forelse($byFaculty as $faculty)
                        <tr>
                            <td class="font-medium text-slate-900">{{ $faculty->name }}</td>
                            <td>{{ $faculty->class_count }}</td>
                            <td>{{ $faculty->student_count }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="py-8 text-center text-slate-500">Không có dữ liệu</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-page-card>

    <x-page-card title="Thanh toán">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="rounded-lg border border-slate-100 bg-slate-50 p-4">
                <p class="text-sm text-slate-500">Tổng thanh toán</p>
                <p class="mt-1 text-xl font-semibold text-slate-900">{{ number_format($paymentStats->total_paid ?? 0) }} ₫</p>
            </div>
            <div class="rounded-lg border border-slate-100 bg-slate-50 p-4">
                <p class="text-sm text-slate-500">Trung bình</p>
                <p class="mt-1 text-xl font-semibold text-slate-900">{{ number_format($paymentStats->avg_payment ?? 0) }} ₫</p>
            </div>
        </div>
    </x-page-card>
</div>
@endsection

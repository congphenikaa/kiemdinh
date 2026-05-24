@extends('layouts.app')

@section('title', 'Thống kê giảng viên')
@section('breadcrumb', 'Giáo viên / Thống kê')

@section('content')
<div class="space-y-6">
    @if($hasNoResults)
        <x-flash-alert type="error" message="Không tìm thấy giảng viên phù hợp với tiêu chí lọc." />
    @endif

    <x-filter-panel>
        <form method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-4">
            <div>
                <label class="form-label">Khoa</label>
                <select name="faculty" class="form-input">
                    <option value="">Tất cả khoa</option>
                    @foreach($faculties as $faculty)
                        <option value="{{ $faculty->id }}" {{ $currentFilters['faculty'] == $faculty->id ? 'selected' : '' }}>{{ $faculty->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Bằng cấp</label>
                <select name="degree" class="form-input">
                    <option value="">Tất cả bằng cấp</option>
                    @foreach($degrees as $degree)
                        <option value="{{ $degree->id }}" {{ $currentFilters['degree'] == $degree->id ? 'selected' : '' }}>
                            {{ $degree->name }} (HS: {{ $degree->salary_coefficient }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Trạng thái</label>
                <select name="status" class="form-input">
                    <option value="all" {{ $currentFilters['status'] == 'all' ? 'selected' : '' }}>Tất cả</option>
                    <option value="active" {{ $currentFilters['status'] == 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                    <option value="inactive" {{ $currentFilters['status'] == 'inactive' ? 'selected' : '' }}>Ngừng hoạt động</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="btn-primary w-full"><i class="fas fa-filter"></i> Áp dụng</button>
            </div>
        </form>
    </x-filter-panel>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <x-stat-card label="Tổng giảng viên" :value="$total" icon="fa-users" color="primary" />
        <x-stat-card label="Đang hoạt động" :value="$active" icon="fa-user-check" color="emerald" />
        <x-stat-card label="Ngừng hoạt động" :value="$inactive" icon="fa-user-slash" color="amber" />
    </div>

    <x-page-card title="Phân bố theo khoa">
        @if($byFaculty->isEmpty())
            <p class="text-sm text-slate-500">Không có dữ liệu</p>
        @else
            <div class="overflow-x-auto">
                <table class="data-table min-w-full">
                    <thead><tr><th>Khoa</th><th>Số GV</th><th>Tỷ lệ</th></tr></thead>
                    <tbody>
                        @foreach($byFaculty as $faculty)
                            <tr>
                                <td class="font-medium text-slate-900">{{ $faculty->name }}</td>
                                <td>{{ $faculty->teachers_count }}</td>
                                <td>{{ $total > 0 ? round(($faculty->teachers_count / $total) * 100, 1) : 0 }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-page-card>

    <x-page-card title="Phân bố theo bằng cấp">
        @if($byDegree->isEmpty())
            <p class="text-sm text-slate-500">Không có dữ liệu</p>
        @else
            <div class="overflow-x-auto">
                <table class="data-table min-w-full">
                    <thead><tr><th>Bằng cấp</th><th>Hệ số</th><th>Số GV</th><th>Tỷ lệ</th></tr></thead>
                    <tbody>
                        @foreach($byDegree as $degree)
                            <tr>
                                <td class="font-medium text-slate-900">{{ $degree->name }}</td>
                                <td class="font-mono">{{ $degree->salary_coefficient }}</td>
                                <td>{{ $degree->teachers_count }}</td>
                                <td>{{ $total > 0 ? round(($degree->teachers_count / $total) * 100, 1) : 0 }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-page-card>
</div>
@endsection

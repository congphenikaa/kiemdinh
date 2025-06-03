@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <h1 class="text-2xl font-bold mb-6">Thống kê lớp học</h1>

    {{-- Bộ lọc --}}
    <form method="GET" class="flex flex-wrap items-end gap-4 mb-6">
        <div>
            <label for="semester" class="block text-sm font-medium text-gray-700">Học kỳ</label>
            <select name="semester" id="semester" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Tất cả</option>
                @foreach($semesters as $semester)
                    <option value="{{ $semester->id }}" {{ $currentFilters['semester'] == $semester->id ? 'selected' : '' }}>
                        {{ $semester->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="status" class="block text-sm font-medium text-gray-700">Trạng thái lớp</label>
            <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="all" {{ $currentFilters['status'] == 'all' ? 'selected' : '' }}>Tất cả</option>
                <option value="active" {{ $currentFilters['status'] == 'active' ? 'selected' : '' }}>Đang dạy</option>
                <option value="finished" {{ $currentFilters['status'] == 'finished' ? 'selected' : '' }}>Đã kết thúc</option>
                <option value="cancelled" {{ $currentFilters['status'] == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
            </select>
        </div>

        <div>
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Lọc
            </button>
        </div>
    </form>

    {{-- Thống kê tổng quan --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-4 shadow rounded-lg">
            <div class="text-sm text-gray-500">Tổng số lớp</div>
            <div class="text-xl font-semibold">{{ $totalClasses }}</div>
        </div>
        <div class="bg-white p-4 shadow rounded-lg">
            <div class="text-sm text-gray-500">Tổng số sinh viên</div>
            <div class="text-xl font-semibold">{{ $totalStudents }}</div>
        </div>
        <div class="bg-white p-4 shadow rounded-lg">
            <div class="text-sm text-gray-500">Điểm danh TB (%)</div>
            <div class="text-xl font-semibold">{{ number_format($avgAttendance, 1) }}%</div>
        </div>
        <div class="bg-white p-4 shadow rounded-lg">
            <div class="text-sm text-gray-500">Số buổi hủy</div>
            <div class="text-xl font-semibold">{{ $cancelledSessions }}</div>
        </div>
    </div>

    {{-- Thống kê theo trạng thái lớp --}}
    <div class="mb-8">
        <h2 class="text-lg font-bold mb-3">Theo trạng thái lớp</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($byStatus as $status => $count)
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="text-sm text-gray-500 capitalize">Trạng thái: {{ $status }}</div>
                    <div class="text-xl font-semibold">{{ $count }}</div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Thống kê theo khoa --}}
    <div class="mb-8">
        <h2 class="text-lg font-bold mb-3">Theo khoa</h2>
        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full table-auto divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Khoa</th>
                        <th class="px-4 py-2 text-right text-sm font-medium text-gray-700">Số lớp</th>
                        <th class="px-4 py-2 text-right text-sm font-medium text-gray-700">Số sinh viên</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($byFaculty as $faculty)
                        <tr>
                            <td class="px-4 py-2">{{ $faculty->name }}</td>
                            <td class="px-4 py-2 text-right">{{ $faculty->class_count }}</td>
                            <td class="px-4 py-2 text-right">{{ $faculty->student_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Thống kê thanh toán --}}
    <div class="mb-8">
        <h2 class="text-lg font-bold mb-3">Thanh toán cho giảng viên</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-white p-4 shadow rounded-lg">
                <div class="text-sm text-gray-500">Tổng tiền đã trả</div>
                <div class="text-xl font-semibold">{{ number_format($paymentStats->total_paid, 0) }} VNĐ</div>
            </div>
            <div class="bg-white p-4 shadow rounded-lg">
                <div class="text-sm text-gray-500">Mức trả trung bình</div>
                <div class="text-xl font-semibold">{{ number_format($paymentStats->avg_payment, 0) }} VNĐ</div>
            </div>
        </div>
    </div>
</div>
@endsection

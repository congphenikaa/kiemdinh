@extends('layouts.app')

@section('title', 'Thống kê lớp học')
@section('breadcrumb', 'Danh sách thống kê')

@section('content')
<div class="content-section">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Thống kê lớp học</h2>
            <div class="flex space-x-2">
                <a href="#" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    <i class="fas fa-file-export mr-2"></i>Xuất Excel
                </a>
            </div>
        </div>

        <!-- Filter Form -->
        <form method="GET" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Học kỳ</label>
                    <select name="semester" class="w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">Tất cả học kỳ</option>
                        @foreach($semesters as $semester)
                            <option value="{{ $semester->id }}" {{ request('semester') == $semester->id ? 'selected' : '' }}>
                                {{ $semester->name }} ({{ $semester->academicYear->name }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Trạng thái</label>
                    <select name="status" class="w-full border-gray-300 rounded-md shadow-sm">
                        <option value="all" {{ request('status', 'all') == 'all' ? 'selected' : '' }}>Tất cả</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                        <option value="ended" {{ request('status') == 'ended' ? 'selected' : '' }}>Đã kết thúc</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 w-full">
                        <i class="fas fa-filter mr-2"></i>Lọc
                    </button>
                </div>
            </div>
        </form>

        <!-- Summary Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white p-4 rounded shadow">
                <h3 class="text-sm font-medium text-gray-500">Tổng số lớp</h3>
                <p class="text-2xl font-semibold">{{ $totalClasses }}</p>
            </div>
            <div class="bg-white p-4 rounded shadow">
                <h3 class="text-sm font-medium text-gray-500">Tổng sinh viên</h3>
                <p class="text-2xl font-semibold">{{ $totalStudents }}</p>
            </div>
            <div class="bg-white p-4 rounded shadow">
                <h3 class="text-sm font-medium text-gray-500">Điểm danh TB</h3>
                <p class="text-2xl font-semibold">{{ round($avgAttendance, 1) }}%</p>
            </div>
            <div class="bg-white p-4 rounded shadow">
                <h3 class="text-sm font-medium text-gray-500">Buổi học đã hoàn thành</h3>
                <p class="text-2xl font-semibold">{{ $completedSessions }}</p>
            </div>
        </div>

        <!-- Statistics by Status -->
        <div class="mb-6">
            <h3 class="text-lg font-medium mb-2">Thống kê theo trạng thái</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($byStatus as $status => $count)
                <div class="bg-white p-4 rounded shadow">
                    <h4 class="text-sm font-medium text-gray-500">{{ ucfirst($status) }}</h4>
                    <p class="text-xl font-semibold">{{ $count }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Statistics by Faculty -->
        <div class="mb-6">
            <h3 class="text-lg font-medium mb-2">Thống kê theo khoa</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Khoa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Số lớp</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Số sinh viên</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($byFaculty as $faculty)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $faculty->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $faculty->class_count }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $faculty->student_count }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Payment Statistics -->
        <div>
            <h3 class="text-lg font-medium mb-2">Thống kê thanh toán</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white p-4 rounded shadow">
                    <h4 class="text-sm font-medium text-gray-500">Tổng thanh toán</h4>
                    <p class="text-xl font-semibold">{{ number_format($paymentStats->total_paid ?? 0) }} VNĐ</p>
                </div>
                <div class="bg-white p-4 rounded shadow">
                    <h4 class="text-sm font-medium text-gray-500">Trung bình thanh toán</h4>
                    <p class="text-xl font-semibold">{{ number_format($paymentStats->avg_payment ?? 0) }} VNĐ</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Update Statistics Form -->
<form id="update-statistics-form" method="POST" class="hidden">
    @csrf
    @method('PUT')
</form>

<script>
    function updateStatistics(classId) {
        if (confirm('Bạn có chắc muốn cập nhật thống kê cho lớp này?')) {
            const form = document.getElementById('update-statistics-form');
            form.action = `/class-statistics/${classId}/update-statistics`;
            form.submit();
        }
    }
</script>
@endsection
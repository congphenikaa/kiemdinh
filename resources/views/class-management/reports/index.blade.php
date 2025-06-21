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
                    <select name="semester_id" class="w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">Tất cả học kỳ</option>
                        @foreach($semesters as $semester)
                            <option value="{{ $semester->id }}" {{ request('semester_id') == $semester->id ? 'selected' : '' }}>
                                {{ $semester->name }} ({{ $semester->academicYear->name }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Khoa</label>
                    <select name="faculty_id" class="w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">Tất cả khoa</option>
                        @foreach($faculties as $faculty)
                            <option value="{{ $faculty->id }}" {{ request('faculty_id') == $faculty->id ? 'selected' : '' }}>
                                {{ $faculty->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 w-full">
                        <i class="fas fa-filter mr-2"></i>Lọc
                    </button>
                </div>
            </div>
        </form>

        <!-- Statistics Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STT</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lớp học</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Môn học</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng buổi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Đã dạy</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Điểm danh TB</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($classes as $index => $class)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $index + $classes->firstItem() }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900">{{ $class->class_code }}</div>
                            <div class="text-sm text-gray-500">HK: {{ $class->semester->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900">{{ $class->course->name }}</div>
                            <div class="text-sm text-gray-500">{{ $class->course->faculty->short_name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $class->course->total_sessions }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $class->statistics->total_sessions_taught ?? 0 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-blue-600 h-2.5 rounded-full" 
                                     style="width: {{ $class->statistics->average_attendance ?? 0 }}%"></div>
                            </div>
                            <span class="text-sm text-gray-500">{{ $class->statistics->average_attendance ?? 0 }}%</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('class-statistics.show', $class->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('class-statistics.export', $class->id) }}" class="text-green-600 hover:text-green-900 mr-3">
                                <i class="fas fa-file-pdf"></i>
                            </a>
                            <button onclick="updateStatistics({{ $class->id }})" class="text-yellow-600 hover:text-yellow-900">
                                <i class="fas fa-sync-alt"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $classes->appends(request()->query())->links() }}
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
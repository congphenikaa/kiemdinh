@extends('layouts.app')

@section('title', 'Chi tiết thống kê lớp học')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('class-statistics.index') }}">Thống kê lớp học</a></li>
    <li class="breadcrumb-item active">{{ $class->class_code }}</li>
@endsection

@section('content')
<div class="content-section">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Thống kê chi tiết lớp {{ $class->class_code }}</h2>
            <div class="flex space-x-2">
                <a href="{{ route('class-statistics.export', $class->id) }}" 
                   class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    <i class="fas fa-file-export mr-2"></i>Xuất báo cáo
                </a>
                <button onclick="updateStatistics({{ $class->id }})" 
                        class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">
                    <i class="fas fa-sync-alt mr-2"></i>Cập nhật
                </button>
            </div>
        </div>

        <!-- Thông tin chung -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-blue-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-blue-800 mb-2">Thông tin lớp học</h3>
                <p class="text-sm"><span class="font-medium">Mã lớp:</span> {{ $class->class_code }}</p>
                <p class="text-sm"><span class="font-medium">Môn học:</span> {{ $class->course->name }}</p>
                <p class="text-sm"><span class="font-medium">Học kỳ:</span> {{ $class->semester->name }} ({{ $class->semester->academicYear->name }})</p>
            </div>
            
            <div class="bg-green-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-green-800 mb-2">Thống kê buổi học</h3>
                <p class="text-sm"><span class="font-medium">Tổng số buổi:</span> {{ $totalSessions }}</p>
                <p class="text-sm"><span class="font-medium">Đã dạy:</span> {{ $taughtSessions }} ({{ round($taughtSessions/$totalSessions*100, 2) }}%)</p>
                <p class="text-sm"><span class="font-medium">Còn lại:</span> {{ $totalSessions - $taughtSessions }}</p>
            </div>
            
            <div class="bg-purple-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-purple-800 mb-2">Điểm danh</h3>
                <p class="text-sm"><span class="font-medium">Tỷ lệ điểm danh TB:</span> {{ $attendanceRate }}%</p>
                <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2">
                    <div class="bg-purple-600 h-2.5 rounded-full" style="width: {{ $attendanceRate }}%"></div>
                </div>
            </div>
        </div>

        <!-- Danh sách giáo viên -->
        <div class="mb-8">
            <h3 class="text-lg font-medium text-gray-800 mb-4">Giáo viên phụ trách</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($teacherStats as $teacherStat)
                <div class="border rounded-lg p-4">
                    <div class="flex items-center mb-2">
                        <div class="bg-blue-100 p-2 rounded-full mr-3">
                            <i class="fas fa-chalkboard-teacher text-blue-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium">{{ $teacherStat['teacher']->name }}</h4>
                            <p class="text-sm text-gray-500">{{ $teacherStat['teacher']->degree->short_name }}</p>
                        </div>
                    </div>
                    <div class="text-sm mt-2">
                        <p><span class="font-medium">Số buổi đã dạy:</span> {{ $teacherStat['taught_sessions'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Danh sách buổi học -->
        <div>
            <h3 class="text-lg font-medium text-gray-800 mb-4">Chi tiết các buổi học</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Buổi số</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày học</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giáo viên</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Điểm danh</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($class->schedules as $schedule)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $schedule->session_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $schedule->date->format('d/m/Y') }} ({{ ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'][$schedule->day_of_week] }})
                                <div class="text-sm text-gray-500">
                                    {{ $schedule->start_time }} - {{ $schedule->end_time }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @foreach($schedule->teachingAssignments as $assignment)
                                    <span class="text-sm">{{ $assignment->teacher->name }}</span><br>
                                @endforeach
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full {{ $schedule->is_taught ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $schedule->is_taught ? 'Đã dạy' : 'Chưa dạy' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($schedule->is_taught)
                                    {{ $schedule->attendance_rate ?? 0 }}%
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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
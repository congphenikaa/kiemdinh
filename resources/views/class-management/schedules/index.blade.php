@extends('layouts.app')

@section('title', 'Quản lý thời khóa biểu')
@section('breadcrumb', 'Danh sách lịch học')

@section('content')
<div class="content-section">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Danh sách lịch học</h2>
            <a href="{{ route('schedules.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>Thêm lịch học
            </a>
        </div>

        <!-- Filter Form -->
        <form method="GET" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lớp học</label>
                    <select name="class_id" class="w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">Tất cả lớp</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->class_code }} - {{ $class->course->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ngày học</label>
                    <input type="date" name="date" value="{{ request('date') }}" class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Trạng thái</label>
                    <select name="is_taught" class="w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">Tất cả</option>
                        <option value="1" {{ request('is_taught') === '1' ? 'selected' : '' }}>Đã dạy</option>
                        <option value="0" {{ request('is_taught') === '0' ? 'selected' : '' }}>Chưa dạy</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 w-full">
                        <i class="fas fa-filter mr-2"></i>Lọc
                    </button>
                </div>
            </div>
        </form>

        <!-- Schedule Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                @if(session('warning'))
                    <div class="mb-4 p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700">
                        <p>{{ session('warning') }}</p>
                    </div>
                @endif
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STT</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lớp học</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày học</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thời gian</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Buổi số</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($schedules as $index => $schedule)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $index + $schedules->firstItem() }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900">{{ $schedule->class->class_code }}</div>
                            <div class="text-sm text-gray-500">{{ $schedule->class->course->name }}</div>
                            <div class="text-sm text-gray-500">HK: {{ $schedule->class->semester->name }} ({{ $schedule->class->semester->academicYear->name }})</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($schedule->date)->format('d/m/Y') }}
                            <div class="text-sm text-gray-500">
                                {{ ['Chủ nhật', 'Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7'][$schedule->day_of_week] }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $schedule->session_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full {{ $schedule->is_taught ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $schedule->is_taught ? 'Đã dạy' : 'Chưa dạy' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button onclick="toggleTaughtStatus({{ $schedule->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3" title="Đánh dấu đã dạy/chưa dạy">
                                <i class="fas fa-check-circle"></i>
                            </button>
                            <a href="{{ route('schedules.edit', $schedule->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="confirmDelete({{ $schedule->id }})" class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $schedules->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="delete-form" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<script>
    function confirmDelete(id) {
        showModal(
            'Xác nhận xóa', 
            'Bạn có chắc chắn muốn xóa lịch học này?',
            function() {
                const form = document.getElementById('delete-form');
                form.action = `/schedules/${id}`;
                form.submit();
            }
        );
    }

    function toggleTaughtStatus(id) {
        fetch(`/schedules/${id}/toggle-taught`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            }
        });
    }
</script>
@endsection
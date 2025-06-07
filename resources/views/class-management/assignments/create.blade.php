@extends('templates.create', [
    'entityName' => 'Phân công giảng dạy',
    'routePrefix' => 'teaching-assignments'
])

@section('form_fields')
    <div class="space-y-6">
        <!-- Thông tin lớp học -->
        <div class="p-4 bg-gray-50 rounded-lg mb-6">
            <h3 class="font-medium text-gray-700">Thông tin lớp học</h3>
            <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Mã lớp:</p>
                    <p class="font-medium">{{ $class->class_code }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Khóa học:</p>
                    <p class="font-medium">{{ $class->course->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Học kỳ:</p>
                    <p class="font-medium">{{ $class->semester->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Trạng thái:</p>
                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                        {{ $class->status === 'open' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $class->status === 'open' ? 'Đang mở' : 'Đã đóng' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Giảng viên -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="teacher_id" class="block text-sm font-medium text-gray-700 pt-2">
                    Giảng viên <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Chọn giảng viên từ danh sách</p>
            </div>
            <div class="md:col-span-2">
                <select id="teacher_id" name="teacher_id" required
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">-- Chọn giảng viên --</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->name }} ({{ $teacher->code ?? 'N/A' }})
                        </option>
                    @endforeach
                </select>
                @error('teacher_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Số buổi phụ trách -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="assigned_sessions" class="block text-sm font-medium text-gray-700 pt-2">
                    Số buổi phụ trách <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Tổng số buổi học: {{ $class->schedules->count() }}</p>
            </div>
            <div class="md:col-span-2">
                <input type="number" id="assigned_sessions" name="assigned_sessions" 
                    value="{{ old('assigned_sessions', 1) }}" min="1" max="{{ $class->schedules->count() }}" required
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                @error('assigned_sessions')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Giảng viên chính -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label class="block text-sm font-medium text-gray-700 pt-2">
                    Vai trò
                </label>
                <p class="mt-1 text-xs text-gray-500">Chỉ có 1 giảng viên chính/lớp</p>
            </div>
            <div class="md:col-span-2">
                <div class="relative flex items-start">
                    <div class="flex items-center h-5">
                        <input id="main_teacher" name="main_teacher" type="checkbox" value="1"
                            {{ old('main_teacher') ? 'checked' : '' }}
                            class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                        <!-- Hidden input để gửi giá trị 0 khi không checked -->
                        <input type="hidden" name="main_teacher" value="0">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="main_teacher" class="font-medium text-gray-700">
                            Giảng viên chính
                        </label>
                        <p class="text-xs text-gray-500">
                            Nếu chọn, giảng viên hiện tại sẽ trở thành giảng viên chính
                        </p>
                    </div>
                </div>
                @error('main_teacher')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Danh sách phân công hiện tại -->
        @if($currentAssignments->count() > 0)
        <div class="mt-6">
            <h3 class="text-sm font-medium text-gray-700 mb-2">Danh sách phân công hiện tại</h3>
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="py-2 pl-4 pr-3 text-left text-xs font-medium text-gray-500">Giảng viên</th>
                            <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500">Số buổi</th>
                            <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500">Vai trò</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($currentAssignments as $assignment)
                        <tr>
                            <td class="whitespace-nowrap py-2 pl-4 pr-3 text-sm font-medium text-gray-900">
                                {{ $assignment->teacher->name }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500">
                                {{ $assignment->assigned_sessions }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500">
                                @if($assignment->main_teacher)
                                    <span class="px-2 py-0.5 bg-green-100 text-green-800 rounded-full text-xs">Chính</span>
                                @else
                                    <span class="px-2 py-0.5 bg-gray-100 text-gray-800 rounded-full text-xs">Phụ</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tự động cập nhật max value cho số buổi phụ trách
    const maxSessions = {{ $class->schedules->count() }};
    const sessionInput = document.getElementById('assigned_sessions');
    
    if (maxSessions > 0) {
        sessionInput.max = maxSessions;
        sessionInput.addEventListener('input', function() {
            if (parseInt(this.value) > maxSessions) {
                this.value = maxSessions;
            }
        });
    }
});
</script>
@endsection
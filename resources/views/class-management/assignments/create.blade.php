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
                    <p class="font-medium">{{ $class->class_code ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Khóa học:</p>
                    <p class="font-medium">
                        {{ $class->course->name ?? 'Chưa có' }} ({{ $class->course->code ?? 'N/A' }})
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Học kỳ:</p>
                    <p class="font-medium">
                        {{ $class->semester->name ?? 'Chưa có' }} ({{ $class->semester->year ?? 'N/A' }})
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tổng số buổi:</p>
                    <p class="font-medium">{{ $class->schedules->count() }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Buổi đã phân công:</p>
                    <p class="font-medium">{{ $totalAssignedSessions }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Buổi còn lại:</p>
                    <p class="font-medium {{ $remainingSessions <= 0 ? 'text-red-600' : 'text-green-600' }}">
                        {{ $remainingSessions }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Form fields -->
        <input type="hidden" name="class_id" value="{{ $class->id }}">

        @if($remainingSessions > 0)
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
                        @php
                            $isAssigned = $class->teachingAssignments->contains('teacher_id', $teacher->id);
                        @endphp
                        <option value="{{ $teacher->id }}" {{ $isAssigned ? 'disabled' : '' }}>
                            {{ $teacher->name }} ({{ $teacher->code ?? 'N/A' }})
                            {{ $isAssigned ? '(Đã phân công)' : '' }}
                        </option>
                    @endforeach
                </select>
                @error('teacher_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Số buổi phụ trách -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
            <div class="md:col-span-1">
                <label for="assigned_sessions" class="block text-sm font-medium text-gray-700 pt-2">
                    Số buổi phụ trách <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">
                    Tối đa: {{ $remainingSessions }} buổi
                </p>
            </div>
            <div class="md:col-span-2">
                <input type="number" id="assigned_sessions" name="assigned_sessions" 
                    value="{{ old('assigned_sessions', min(1, $remainingSessions)) }}" 
                    min="1" 
                    max="{{ $remainingSessions }}"
                    required
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                @error('assigned_sessions')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Giảng viên chính -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
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
                            {{ $class->mainTeacher ? 'disabled' : '' }}
                            class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="main_teacher" class="font-medium text-gray-700">
                            Giảng viên chính
                        </label>
                        <p class="text-xs text-gray-500">
                            @if($class->mainTeacher)
                                Lớp đã có giảng viên chính: {{ $class->mainTeacher->name }}
                            @else
                                Nếu chọn, giảng viên hiện tại sẽ trở thành giảng viên chính
                            @endif
                        </p>
                    </div>
                </div>
                @error('main_teacher')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        @else
        <div class="p-4 bg-blue-50 rounded-lg">
            <p class="text-blue-700 text-sm">Lớp học đã được phân công đủ số buổi. Vui lòng chỉnh sửa phân công hiện có nếu cần thay đổi.</p>
        </div>
        @endif

        <!-- Danh sách phân công hiện tại -->
        @if($class->teachingAssignments->count() > 0)
        <div class="mt-6">
            <h3 class="text-sm font-medium text-gray-700 mb-2">Danh sách phân công hiện tại</h3>
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="py-2 pl-4 pr-3 text-left text-xs font-medium text-gray-500">Giảng viên</th>
                            <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500">Số buổi</th>
                            <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500">Vai trò</th>
                            <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500">Tỉ lệ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($class->teachingAssignments as $assignment)
                        <tr>
                            <td class="whitespace-nowrap py-2 pl-4 pr-3 text-sm font-medium text-gray-900">
                                {{ $assignment->teacher->name }} ({{ $assignment->teacher->code ?? 'N/A' }})
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
                            <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500">
                                {{ round(($assignment->assigned_sessions / $class->schedules->count()) * 100) }}%
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
    const maxSessions = {{ $remainingSessions }};
    const sessionInput = document.getElementById('assigned_sessions');
    
    if (maxSessions > 0) {
        sessionInput.max = maxSessions;
        sessionInput.addEventListener('input', function() {
            if (parseInt(this.value) > maxSessions) {
                this.value = maxSessions;
            }
        });
    } else {
        sessionInput.disabled = true;
        sessionInput.value = '0';
        sessionInput.classList.add('bg-gray-100', 'cursor-not-allowed');
    }
});
</script>
@endsection
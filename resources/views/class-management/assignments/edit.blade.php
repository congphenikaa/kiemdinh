@extends('templates.edit', [
    'entityName' => 'Phân công giảng dạy',
    'routePrefix' => 'teaching-assignments',
    'model' => $class,
])

@section('form_fields')
    <div class="space-y-6">
        <!-- Thông tin lớp học -->
        <div class="p-4 bg-gray-50 rounded-lg mb-6">
            <h3 class="font-medium text-gray-700">Thông tin lớp học</h3>
            <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Giữ nguyên phần hiển thị thông tin lớp -->
            </div>
        </div>

        <!-- Danh sách phân công -->
        <div class="space-y-4">
            <h3 class="text-sm font-medium text-gray-700">Phân công giảng dạy</h3>
            
            @if($class->teachingAssignments->count() > 0)
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-2 pl-4 pr-3 text-left text-xs font-medium text-gray-500">Giảng viên</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500">Số buổi</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500">Vai trò</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500">Tỉ lệ</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white" id="assignments-table">
                            @foreach($class->teachingAssignments as $index => $assignment)
                            <tr data-assignment-id="{{ $assignment->id }}">
                                <td class="whitespace-nowrap py-2 pl-4 pr-3 text-sm font-medium text-gray-900">
                                    <input type="hidden" name="assignments[{{ $index }}][id]" value="{{ $assignment->id }}">
                                    <input type="hidden" name="assignments[{{ $index }}][teacher_id]" value="{{ $assignment->teacher_id }}">
                                    {{ $assignment->teacher->name }} ({{ $assignment->teacher->code ?? 'N/A' }})
                                </td>
                                <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500">
                                    <input type="number" 
                                           name="assignments[{{ $index }}][assigned_sessions]" 
                                           value="{{ $assignment->assigned_sessions }}"
                                           min="1"
                                           max="{{ $totalSessions }}"
                                           class="w-20 px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                </td>
                                <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500">
                                    <div class="flex items-center">
                                        <input type="radio" 
                                               name="main_teacher" 
                                               value="{{ $index }}" 
                                               {{ $assignment->main_teacher ? 'checked' : '' }}
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                        <span class="ml-2">Giảng viên chính</span>
                                    </div>
                                    <input type="hidden" name="assignments[{{ $index }}][main_teacher]" value="{{ $assignment->main_teacher ? '1' : '0' }}">
                                </td>
                                <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500">
                                    {{ round(($assignment->assigned_sessions / $totalSessions) * 100) }}%
                                </td>
                                <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500">
                                    <button type="button" class="text-red-600 hover:text-red-900 remove-assignment">
                                        <i class="fas fa-trash-alt"></i> Xóa
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-4 bg-blue-50 rounded-lg">
                    <p class="text-blue-700 text-sm">Lớp học chưa có phân công giảng dạy.</p>
                </div>
            @endif
        </div>

        <!-- Thêm giảng viên mới -->
        @if($remainingSessions > 0 && $availableTeachers->count() > 0)
        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
            <h3 class="font-medium text-gray-700 mb-4">Thêm giảng viên mới</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-1">
                    <label for="new_teacher_id" class="block text-sm font-medium text-gray-700 pt-2">
                        Giảng viên
                    </label>
                </div>
                <div class="md:col-span-2">
                    <select id="new_teacher_id"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">-- Chọn giảng viên --</option>
                        @foreach($availableTeachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->name }} ({{ $teacher->code ?? 'N/A' }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                <div class="md:col-span-1">
                    <label for="new_assigned_sessions" class="block text-sm font-medium text-gray-700 pt-2">
                        Số buổi phụ trách
                    </label>
                </div>
                <div class="md:col-span-2">
                    <input type="number" id="new_assigned_sessions" 
                        value="1" 
                        min="1" 
                        max="{{ $remainingSessions }}"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
            </div>

            <div class="mt-4 flex justify-end">
                <button type="button" id="add-teacher-btn" 
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-plus mr-2"></i> Thêm giảng viên
                </button>
            </div>
        </div>
        @endif
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cập nhật trạng thái main_teacher khi radio button thay đổi
    document.querySelectorAll('input[name="main_teacher"]').forEach(radio => {
        radio.addEventListener('change', function() {
            // Cập nhật tất cả các hidden input main_teacher về 0
            document.querySelectorAll('input[name$="[main_teacher]"]').forEach(input => {
                input.value = '0';
            });
            
            // Cập nhật giá trị của radio được chọn thành 1
            const index = this.value;
            document.querySelector(`input[name="assignments[${index}][main_teacher]"]`).value = '1';
        });
    });

    // Xử lý xóa phân công
    document.querySelectorAll('.remove-assignment').forEach(button => {
        button.addEventListener('click', function() {
            const row = this.closest('tr');
            if (confirm('Bạn có chắc chắn muốn xóa phân công này?')) {
                // Nếu là phân công đã tồn tại (có ID), cần đánh dấu để xóa
                const assignmentId = row.dataset.assignmentId;
                if (assignmentId) {
                    // Tạo input ẩn để đánh dấu phân công cần xóa
                    const deleteInput = document.createElement('input');
                    deleteInput.type = 'hidden';
                    deleteInput.name = 'deleted_assignments[]';
                    deleteInput.value = assignmentId;
                    row.parentNode.appendChild(deleteInput);
                }
                
                // Xóa hàng khỏi hiển thị
                row.remove();
                updateRemainingSessions();
                
                // Đánh lại chỉ số các phân công còn lại
                reindexAssignments();
            }
        });
    });

    // Hàm đánh lại chỉ số phân công sau khi xóa
    function reindexAssignments() {
        const rows = document.querySelectorAll('#assignments-table tr');
        rows.forEach((row, index) => {
            // Cập nhật tên input với chỉ số mới
            const inputs = row.querySelectorAll('input');
            inputs.forEach(input => {
                const name = input.name.replace(/assignments\[\d+\]/g, `assignments[${index}]`);
                input.name = name;
            });
            
            // Cập nhật giá trị radio button
            const radio = row.querySelector('input[type="radio"]');
            if (radio) {
                radio.value = index;
            }
        });
    }

   // Thêm giảng viên mới
    const addTeacherBtn = document.getElementById('add-teacher-btn');
    if (addTeacherBtn) {
        addTeacherBtn.addEventListener('click', function() {
            const teacherSelect = document.getElementById('new_teacher_id');
            const sessionsInput = document.getElementById('new_assigned_sessions');
            const teacherId = teacherSelect.value;
            const teacherName = teacherSelect.options[teacherSelect.selectedIndex].text;
            const assignedSessions = parseInt(sessionsInput.value);
            const remainingSessions = {{ $remainingSessions }};
            
            if (!teacherId) {
                alert('Vui lòng chọn giảng viên');
                return;
            }
            
            if (assignedSessions < 1 || assignedSessions > remainingSessions) {
                alert(`Số buổi phải từ 1 đến ${remainingSessions}`);
                return;
            }
            
            // Tạo chỉ số mới
            const newIndex = document.querySelectorAll('#assignments-table tr').length;
            
            // Thêm hàng mới vào bảng
            const tbody = document.getElementById('assignments-table');
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td class="whitespace-nowrap py-2 pl-4 pr-3 text-sm font-medium text-gray-900">
                    <input type="hidden" name="assignments[${newIndex}][teacher_id]" value="${teacherId}">
                    ${teacherName}
                </td>
                <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500">
                    <input type="number" 
                        name="assignments[${newIndex}][assigned_sessions]" 
                        value="${assignedSessions}"
                        min="1"
                        max="{{ $totalSessions }}"
                        class="w-20 px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </td>
                <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500">
                    <div class="flex items-center">
                        <input type="radio" 
                            name="main_teacher" 
                            value="${newIndex}" 
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                        <span class="ml-2">Giảng viên chính</span>
                    </div>
                    <input type="hidden" name="assignments[${newIndex}][main_teacher]" value="0">
                </td>
                <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500">
                    ${Math.round((assignedSessions / {{ $totalSessions }}) * 100)}%
                </td>
                <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500">
                    <button type="button" class="text-red-600 hover:text-red-900 remove-assignment">
                        <i class="fas fa-trash-alt"></i> Xóa
                    </button>
                </td>
            `;
            
            tbody.appendChild(newRow);
            
            // Thêm sự kiện cho radio button mới
            newRow.querySelector('input[type="radio"]').addEventListener('change', function() {
                document.querySelectorAll('input[name$="[main_teacher]"]').forEach(input => {
                    input.value = '0';
                });
                newRow.querySelector('input[name$="[main_teacher]"]').value = '1';
            });
            
            // Thêm sự kiện xóa cho hàng mới
            newRow.querySelector('.remove-assignment').addEventListener('click', function() {
                if (confirm('Bạn có chắc chắn muốn xóa phân công này?')) {
                    this.closest('tr').remove();
                    updateRemainingSessions();
                    reindexAssignments();
                }
            });
            
            // Reset form thêm mới
            teacherSelect.value = '';
            sessionsInput.value = 1;
            
            // Cập nhật số buổi còn lại
            updateRemainingSessions();
        });
    }
    
    // Hàm cập nhật số buổi còn lại (nếu cần hiển thị)
    function updateRemainingSessions() {
        // Logic để cập nhật hiển thị số buổi còn lại nếu cần
    }
    
    // Validate form trước khi submit
    document.querySelector('form').addEventListener('submit', function(e) {
        const rows = document.querySelectorAll('#assignments-table tr');
        if (rows.length === 0) {
            e.preventDefault();
            alert('Vui lòng thêm ít nhất một giảng viên');
            return;
        }
        
        let totalSessions = 0;
        let mainTeacherCount = 0;
        
        rows.forEach(row => {
            const sessionsInput = row.querySelector('input[type="number"]');
            const mainTeacherInput = row.querySelector('input[name$="[main_teacher]"]');
            
            if (sessionsInput) {
                totalSessions += parseInt(sessionsInput.value) || 0;
            }
            
            if (mainTeacherInput && mainTeacherInput.value === '1') {
                mainTeacherCount++;
            }
        });
        
        if (totalSessions > {{ $totalSessions }}) {
            e.preventDefault();
            alert(`Tổng số buổi phân công (${totalSessions}) vượt quá số buổi học ({{ $totalSessions }})`);
            return;
        }
        
        if (mainTeacherCount === 0) {
            e.preventDefault();
            alert('Vui lòng chọn ít nhất một giảng viên chính');
            return;
        }
        
        if (mainTeacherCount > 1) {
            e.preventDefault();
            alert('Chỉ được chọn một giảng viên chính');
            return;
        }
    });
});
</script>
@endsection
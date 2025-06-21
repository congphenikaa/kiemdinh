@extends('templates.create', [
    'entityName' => 'Phân công giảng dạy',
    'routePrefix' => 'teaching-assignments'
])

@section('form_fields')
    <div class="space-y-6">
        {{-- Lớp học --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="class_id" class="block text-sm font-medium text-gray-700 pt-2">
                    Lớp học <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Chọn lớp học cần phân công</p>
            </div>
            <div class="md:col-span-2">
                <select id="class_id" name="class_id" required
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">-- Chọn lớp học --</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" 
                            data-faculty-id="{{ $class->course->faculty_id }}">
                            {{ $class->class_code }} - {{ $class->course->name }} ({{ $class->semester->academicYear->name }})
                        </option>
                    @endforeach
                </select>
                <div id="class-info" class="mt-2 p-2 bg-blue-50 text-blue-800 rounded text-sm hidden">
                    Khoa phụ trách: <span id="faculty-name"></span>
                </div>
            </div>
        </div>

        {{-- Giáo viên --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="teacher_id" class="block text-sm font-medium text-gray-700 pt-2">
                    Giáo viên <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Chọn giáo viên phân công</p>
            </div>
            <div class="md:col-span-2">
                <select id="teacher_id" name="teacher_id" required
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">-- Chọn giáo viên --</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" 
                            data-faculty-id="{{ $teacher->faculty_id }}"
                            data-degree="{{ $teacher->degree->short_name }}">
                            {{ $teacher->name }} ({{ $teacher->code }}) - {{ $teacher->faculty->short_name }}
                        </option>
                    @endforeach
                </select>
                <div id="teacher-info" class="mt-2 p-2 bg-blue-50 text-blue-800 rounded text-sm hidden">
                    Khoa: <span id="teacher-faculty"></span> | 
                    Học vị: <span id="teacher-degree"></span>
                </div>
                <div id="faculty-warning" class="mt-2 p-2 bg-yellow-50 text-yellow-800 rounded text-sm hidden">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    Giáo viên không thuộc khoa phụ trách môn học
                </div>
            </div>
        </div>

        {{-- Xác nhận khác khoa --}}
        <div id="force-confirm" class="hidden">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-1"></div>
                <div class="md:col-span-2 p-4 bg-red-50 border border-red-200 rounded-md">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 text-red-400">
                            <i class="fas fa-exclamation-circle text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Xác nhận phân công</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <p>Giáo viên không thuộc khoa phụ trách môn học. Bạn có chắc chắn muốn phân công?</p>
                            </div>
                            <div class="mt-4">
                                <div class="flex items-center">
                                    <input id="force_assign" name="force_assign" type="checkbox" 
                                        class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                    <label for="force_assign" class="ml-2 block text-sm text-red-900">
                                        Tôi xác nhận phân công này
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const classSelect = document.getElementById('class_id');
            const teacherSelect = document.getElementById('teacher_id');
            const facultyWarning = document.getElementById('faculty-warning');
            const forceConfirm = document.getElementById('force-confirm');
            
            // Hiển thị thông tin khi chọn lớp
            classSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const facultyId = selectedOption.getAttribute('data-faculty-id');
                const classInfo = document.getElementById('class-info');
                
                if (this.value) {
                    // Gọi API lấy thông tin khoa và giáo viên
                    fetch(`/api/classes/${this.value}/teachers`)
                        .then(response => response.json())
                        .then(data => {
                            document.getElementById('faculty-name').textContent = data.faculty_name;
                            classInfo.classList.remove('hidden');
                            
                            // Lọc giáo viên cùng khoa
                            const teacherOptions = teacherSelect.options;
                            let hasSameFacultyTeacher = false;
                            
                            for (let i = 0; i < teacherOptions.length; i++) {
                                if (teacherOptions[i].value && 
                                    teacherOptions[i].getAttribute('data-faculty-id') === facultyId) {
                                    teacherOptions[i].style.display = '';
                                    hasSameFacultyTeacher = true;
                                } else {
                                    teacherOptions[i].style.display = 'none';
                                }
                            }
                            
                            if (!hasSameFacultyTeacher) {
                                // Hiển thị tất cả giáo viên nếu không có giáo viên cùng khoa
                                for (let i = 0; i < teacherOptions.length; i++) {
                                    teacherOptions[i].style.display = '';
                                }
                            }
                        });
                } else {
                    classInfo.classList.add('hidden');
                    // Hiển thị tất cả giáo viên khi không chọn lớp
                    const teacherOptions = teacherSelect.options;
                    for (let i = 0; i < teacherOptions.length; i++) {
                        teacherOptions[i].style.display = '';
                    }
                }
                
                // Reset cảnh báo
                facultyWarning.classList.add('hidden');
                forceConfirm.classList.add('hidden');
            });
            
            // Hiển thị thông tin khi chọn giáo viên
            teacherSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const teacherInfo = document.getElementById('teacher-info');
                
                if (this.value) {
                    document.getElementById('teacher-faculty').textContent = 
                        selectedOption.text.split(' - ')[1] || '';
                    document.getElementById('teacher-degree').textContent = 
                        selectedOption.getAttribute('data-degree') || '';
                    teacherInfo.classList.remove('hidden');
                    
                    // Kiểm tra có cùng khoa với lớp không
                    const classSelected = classSelect.options[classSelect.selectedIndex];
                    if (classSelected.value && 
                        classSelected.getAttribute('data-faculty-id') !== selectedOption.getAttribute('data-faculty-id')) {
                        facultyWarning.classList.remove('hidden');
                        forceConfirm.classList.remove('hidden');
                    } else {
                        facultyWarning.classList.add('hidden');
                        forceConfirm.classList.add('hidden');
                    }
                } else {
                    teacherInfo.classList.add('hidden');
                    facultyWarning.classList.add('hidden');
                    forceConfirm.classList.add('hidden');
                }
            });
        });
    </script>
    @endpush
@endsection
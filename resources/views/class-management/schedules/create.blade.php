@extends('templates.create', [
    'entityName' => 'Lịch học',
    'routePrefix' => 'schedules'
])

@section('form_fields')
    <div class="space-y-6">
        {{-- Lớp học --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="class_id" class="form-label">
                    Lớp học <span class="text-red-500">*</span>
                </label>
                <p class="form-hint">Chọn lớp học cần tạo lịch</p>
            </div>
            <div class="md:col-span-2">
                <select id="class_id" name="class_id" required
                    class="form-input">
                    <option value="">-- Chọn lớp học --</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" data-total-sessions="{{ $class->course->total_sessions }}">
                            {{ $class->class_code }} - {{ $class->course->name }} ({{ $class->semester->academicYear->name }})
                        </option>
                    @endforeach
                </select>
                <div id="class-info" class="mt-2 text-sm text-gray-600 hidden">
                    Số buổi cần tạo: <span id="total-sessions">0</span>
                </div>
                @error('class_id')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Khoảng thời gian --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label class="form-label">
                    Khoảng thời gian <span class="text-red-500">*</span>
                </label>
                <p class="form-hint">Chọn khoảng thời gian tổ chức lớp</p>
            </div>
            <div class="md:col-span-2">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="start_date" class="block text-xs font-medium text-gray-500">Ngày bắt đầu</label>
                        <input type="date" id="start_date" name="start_date" required
                               class="form-input">
                    </div>
                    <div>
                        <label for="end_date" class="block text-xs font-medium text-gray-500">Ngày kết thúc</label>
                        <input type="date" id="end_date" name="end_date" required
                               class="form-input">
                    </div>
                </div>
                @error('start_date')
                    <p class="form-error">{{ $message }}</p>
                @enderror
                @error('end_date')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Ngày trong tuần --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label class="form-label">
                    Ngày học trong tuần <span class="text-red-500">*</span>
                </label>
                <p class="form-hint">Chọn các ngày học trong tuần</p>
            </div>
            <div class="md:col-span-2">
                <div class="flex flex-wrap gap-2">
                    <div class="flex items-center">
                        <input type="checkbox" id="day_0" name="days_of_week[]" value="0" class="form-checkbox">
                        <label for="day_0" class="ml-2 text-sm text-gray-700">Chủ nhật</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="day_1" name="days_of_week[]" value="1" class="form-checkbox">
                        <label for="day_1" class="ml-2 text-sm text-gray-700">Thứ 2</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="day_2" name="days_of_week[]" value="2" class="form-checkbox">
                        <label for="day_2" class="ml-2 text-sm text-gray-700">Thứ 3</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="day_3" name="days_of_week[]" value="3" class="form-checkbox">
                        <label for="day_3" class="ml-2 text-sm text-gray-700">Thứ 4</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="day_4" name="days_of_week[]" value="4" class="form-checkbox">
                        <label for="day_4" class="ml-2 text-sm text-gray-700">Thứ 5</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="day_5" name="days_of_week[]" value="5" class="form-checkbox">
                        <label for="day_5" class="ml-2 text-sm text-gray-700">Thứ 6</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="day_6" name="days_of_week[]" value="6" class="form-checkbox">
                        <label for="day_6" class="ml-2 text-sm text-gray-700">Thứ 7</label>
                    </div>
                </div>
                @error('days_of_week')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Giờ học --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label class="form-label">
                    Giờ học <span class="text-red-500">*</span>
                </label>
                <p class="form-hint">Khung giờ học của buổi</p>
            </div>
            <div class="md:col-span-2">
                <div class="flex space-x-4">
                    <div class="flex-1">
                        <label for="start_time" class="block text-xs font-medium text-gray-500">Bắt đầu</label>
                        <input type="time" id="start_time" name="start_time" 
                               value="07:30" required
                               class="form-input">
                    </div>
                    <div class="flex-1">
                        <label for="end_time" class="block text-xs font-medium text-gray-500">Kết thúc</label>
                        <input type="time" id="end_time" name="end_time" 
                               value="09:30" required
                               class="form-input">
                    </div>
                </div>
                @error('start_time')
                    <p class="form-error">{{ $message }}</p>
                @enderror
                @error('end_time')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Hiển thị số buổi cần tạo khi chọn lớp
            document.getElementById('class_id').addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const totalSessions = selectedOption.getAttribute('data-total-sessions');
                
                const classInfoDiv = document.getElementById('class-info');
                const totalSessionsSpan = document.getElementById('total-sessions');
                
                if (this.value && totalSessions) {
                    classInfoDiv.classList.remove('hidden');
                    totalSessionsSpan.textContent = totalSessions;
                } else {
                    classInfoDiv.classList.add('hidden');
                }
            });

            // Đặt ngày mặc định
            const today = new Date();
            document.getElementById('start_date').valueAsDate = today;
            
            // Thêm 2 tháng vào ngày kết thúc mặc định
            const endDate = new Date();
            endDate.setMonth(today.getMonth() + 2);
            document.getElementById('end_date').valueAsDate = endDate;
        });
    </script>
    @endpush
@endsection
@extends('templates.edit', [
    'entityName' => 'Lịch học',
    'routePrefix' => 'schedules',
    'model' => $schedule
])

@section('form_fields')
    <div class="space-y-6">
        {{-- Lớp học --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="class_id" class="block text-sm font-medium text-gray-700 pt-2">
                    Lớp học <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Lớp học không thể thay đổi</p>
            </div>
            <div class="md:col-span-2">
                <input type="text" value="{{ $schedule->class->class_code }} - {{ $schedule->class->course->name }}" 
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100 sm:text-sm" readonly>
                <input type="hidden" name="class_id" value="{{ $schedule->class_id }}">
            </div>
        </div>

        {{-- Ngày học --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="date" class="block text-sm font-medium text-gray-700 pt-2">
                    Ngày học <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Ngày diễn ra buổi học</p>
            </div>
            <div class="md:col-span-2">
                <input type="date" id="date" name="date" 
                    value="{{ old('date', $schedule->date instanceof \Carbon\Carbon ? $schedule->date->format('Y-m-d') : \Carbon\Carbon::parse($schedule->date)->format('Y-m-d')) }}" required
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    min="{{ now()->subYear()->format('Y-m-d') }}"
                    max="{{ now()->addYear()->format('Y-m-d') }}"
                    {{ $schedule->is_taught ? 'disabled' : '' }}>
                @error('date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="day_of_week" class="block text-sm font-medium text-gray-700 pt-2">
                    Thứ <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Thứ trong tuần (2-7 tương ứng T2-T7)</p>
            </div>
            <div class="md:col-span-2">
                <select id="day_of_week" name="day_of_week" required
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    {{ $schedule->is_taught ? 'disabled' : '' }}>
                    @foreach([2 => 'Thứ 2', 3 => 'Thứ 3', 4 => 'Thứ 4', 5 => 'Thứ 5', 6 => 'Thứ 6', 7 => 'Thứ 7'] as $day => $label)
                        <option value="{{ $day }}" {{ old('day_of_week', $schedule->day_of_week) == $day ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('day_of_week')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Giờ học --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label class="block text-sm font-medium text-gray-700 pt-2">
                    Giờ học <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Khung giờ diễn ra buổi học</p>
            </div>
            <div class="md:col-span-2 flex space-x-4">
                <div class="flex-1">
                    <label for="start_time" class="block text-xs font-medium text-gray-500">Bắt đầu</label>
                    <input type="time" id="start_time" name="start_time" 
                           value="{{ old('start_time', $schedule->start_time->format('H:i')) }}" required
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           {{ $schedule->is_taught ? 'disabled' : '' }}>
                    @error('start_time')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex-1">
                    <label for="end_time" class="block text-xs font-medium text-gray-500">Kết thúc</label>
                    <input type="time" id="end_time" name="end_time" 
                           value="{{ old('end_time', $schedule->end_time->format('H:i')) }}" required
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           {{ $schedule->is_taught ? 'disabled' : '' }}>
                    @error('end_time')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Loại buổi học --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="session_type" class="block text-sm font-medium text-gray-700 pt-2">
                    Loại buổi học <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Chọn loại buổi học</p>
            </div>
            <div class="md:col-span-2">
                <select id="session_type" name="session_type" required
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    {{ $schedule->is_taught ? 'disabled' : '' }}>
                    <option value="theory" {{ old('session_type', $schedule->session_type) == 'theory' ? 'selected' : '' }}>Lý thuyết</option>
                    <option value="practice" {{ old('session_type', $schedule->session_type) == 'practice' ? 'selected' : '' }}>Thực hành</option>
                    <option value="exam" {{ old('session_type', $schedule->session_type) == 'exam' ? 'selected' : '' }}>Kiểm tra</option>
                    <option value="review" {{ old('session_type', $schedule->session_type) == 'review' ? 'selected' : '' }}>Ôn tập</option>
                </select>
                @error('session_type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Trạng thái hủy --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label class="block text-sm font-medium text-gray-700 pt-2">
                    Trạng thái buổi học
                </label>
                <p class="mt-1 text-xs text-gray-500">Đánh dấu nếu buổi học bị hủy</p>
            </div>
            <div class="md:col-span-2">
                <div class="relative flex items-start">
                    <div class="flex items-center h-5">
                        <input id="is_cancelled" name="is_cancelled" type="checkbox" value="1"
                            {{ old('is_cancelled', $schedule->is_cancelled) ? 'checked' : '' }}
                            class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded toggle-cancellation"
                            {{ $schedule->is_taught ? 'disabled' : '' }}>
                        <input type="hidden" name="is_cancelled" value="0">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="is_cancelled" class="font-medium text-gray-700">
                            Buổi học đã bị hủy
                        </label>
                    </div>
                </div>
            </div>
        </div>

        {{-- Lý do hủy --}}
        <div id="cancellation_reason_field" class="grid grid-cols-1 md:grid-cols-3 gap-4" 
             style="{{ old('is_cancelled', $schedule->is_cancelled) ? '' : 'display: none;' }}">
            <div class="md:col-span-1">
                <label for="cancellation_reason" class="block text-sm font-medium text-gray-700 pt-2">
                    Lý do hủy
                </label>
                <p class="mt-1 text-xs text-gray-500">Nhập lý do hủy buổi học</p>
            </div>
            <div class="md:col-span-2">
                <textarea id="cancellation_reason" name="cancellation_reason" rows="2"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    {{ $schedule->is_taught ? 'disabled' : '' }}>{{ old('cancellation_reason', $schedule->cancellation_reason) }}</textarea>
                @error('cancellation_reason')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Đã dạy --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label class="block text-sm font-medium text-gray-700 pt-2">
                    Trạng thái dạy
                </label>
                <p class="mt-1 text-xs text-gray-500">Đánh dấu nếu buổi học đã được dạy</p>
            </div>
            <div class="md:col-span-2">
                <div class="relative flex items-start">
                    <div class="flex items-center h-5">
                        <input id="is_taught" name="is_taught" type="checkbox" value="1"
                            {{ old('is_taught', $schedule->is_taught) ? 'checked' : '' }}
                            class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                        <input type="hidden" name="is_taught" value="0">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="is_taught" class="font-medium text-gray-700">
                            Buổi học đã được dạy
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle hiển thị lý do hủy
            const cancellationCheckbox = document.querySelector('.toggle-cancellation');
            const reasonField = document.getElementById('cancellation_reason_field');
            
            if (cancellationCheckbox) {
                cancellationCheckbox.addEventListener('change', function() {
                    reasonField.style.display = this.checked ? 'grid' : 'none';
                    if (this.checked) {
                        document.getElementById('cancellation_reason').focus();
                    }
                });
            }

            // Tự động điền thứ khi chọn ngày
            const dateInput = document.getElementById('date');
            const dayOfWeekSelect = document.getElementById('day_of_week');
            
            if (dateInput && dayOfWeekSelect) {
                dateInput.addEventListener('change', function() {
                    const selectedDate = new Date(this.value);
                    if (!isNaN(selectedDate.getTime())) {
                        // Lấy thứ (0-6, CN là 0) và chuyển sang hệ thống 1-7 (T2-CN)
                        let dayOfWeek = selectedDate.getDay() + 1;
                        if (dayOfWeek === 0) dayOfWeek = 7; // Chủ nhật
                        
                        dayOfWeekSelect.value = dayOfWeek;
                    }
                });
            }
        });
    </script>
    @endpush
@endsection
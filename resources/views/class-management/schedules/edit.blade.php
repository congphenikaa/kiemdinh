@extends('templates.edit', [
    'entityName' => 'Lịch học',
    'routePrefix' => 'schedules',
    'model' => $schedule,
])

@section('form_fields')
    <div class="space-y-6">
        {{-- Lớp học --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label class="form-label">
                    Lớp học
                </label>
                <p class="form-hint">Lớp học của lịch này</p>
            </div>
            <div class="md:col-span-2">
                <input type="text" readonly 
                       value="{{ $schedule->class->class_code }} - {{ $schedule->class->course->name }}" 
                       class="form-input bg-slate-100">
                <input type="hidden" name="class_id" value="{{ $schedule->class_id }}">
            </div>
        </div>

        {{-- Ngày học --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="date" class="form-label">
                    Ngày học <span class="text-red-500">*</span>
                </label>
                <p class="form-hint">Ngày diễn ra buổi học</p>
            </div>
            <div class="md:col-span-2">
                <input type="date" id="date" name="date" 
                       value="{{ old('date', $schedule->date->format('Y-m-d')) }}" required
                       class="form-input">
                @error('date')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Thứ trong tuần --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="day_of_week" class="form-label">
                    Thứ trong tuần <span class="text-red-500">*</span>
                </label>
                <p class="form-hint">Chọn thứ diễn ra buổi học</p>
            </div>
            <div class="md:col-span-2">
                <select id="day_of_week" name="day_of_week" required
                    class="form-input">
                    <option value="0" {{ $schedule->day_of_week == 0 ? 'selected' : '' }}>Chủ nhật</option>
                    <option value="1" {{ $schedule->day_of_week == 1 ? 'selected' : '' }}>Thứ 2</option>
                    <option value="2" {{ $schedule->day_of_week == 2 ? 'selected' : '' }}>Thứ 3</option>
                    <option value="3" {{ $schedule->day_of_week == 3 ? 'selected' : '' }}>Thứ 4</option>
                    <option value="4" {{ $schedule->day_of_week == 4 ? 'selected' : '' }}>Thứ 5</option>
                    <option value="5" {{ $schedule->day_of_week == 5 ? 'selected' : '' }}>Thứ 6</option>
                    <option value="6" {{ $schedule->day_of_week == 6 ? 'selected' : '' }}>Thứ 7</option>
                </select>
                @error('day_of_week')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Buổi số --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="session_number" class="form-label">
                    Buổi số <span class="text-red-500">*</span>
                </label>
                <p class="form-hint">Số thứ tự buổi học</p>
            </div>
            <div class="md:col-span-2">
                <input type="number" id="session_number" name="session_number" 
                       value="{{ old('session_number', $schedule->session_number) }}" min="1" required
                       class="form-input">
                @error('session_number')
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
                               value="{{ old('start_time', $schedule->start_time) }}" required
                               class="form-input">
                        @error('start_time')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex-1">
                        <label for="end_time" class="block text-xs font-medium text-gray-500">Kết thúc</label>
                        <input type="time" id="end_time" name="end_time" 
                               value="{{ old('end_time', $schedule->end_time) }}" required
                               class="form-input">
                        @error('end_time')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Trạng thái --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label class="form-label">
                    Trạng thái
                </label>
                <p class="form-hint">Đánh dấu nếu buổi học đã hoàn thành</p>
            </div>
            <div class="md:col-span-2 flex items-center">
                <input type="checkbox" id="is_taught" name="is_taught" value="1" 
                       {{ old('is_taught', $schedule->is_taught) ? 'checked' : '' }}
                       class="form-checkbox">
                <label for="is_taught" class="ml-2 block text-sm text-gray-700">
                    Đã dạy
                </label>
            </div>
        </div>
    </div>
@endsection
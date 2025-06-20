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
                <label class="block text-sm font-medium text-gray-700 pt-2">
                    Lớp học
                </label>
                <p class="mt-1 text-xs text-gray-500">Lớp học của lịch này</p>
            </div>
            <div class="md:col-span-2">
                <input type="text" readonly 
                       value="{{ $schedule->class->class_code }} - {{ $schedule->class->course->name }}" 
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100">
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
                       value="{{ old('date', $schedule->date->format('Y-m-d')) }}" required
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                @error('date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Thứ trong tuần --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="day_of_week" class="block text-sm font-medium text-gray-700 pt-2">
                    Thứ trong tuần <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Chọn thứ diễn ra buổi học</p>
            </div>
            <div class="md:col-span-2">
                <select id="day_of_week" name="day_of_week" required
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="0" {{ $schedule->day_of_week == 0 ? 'selected' : '' }}>Chủ nhật</option>
                    <option value="1" {{ $schedule->day_of_week == 1 ? 'selected' : '' }}>Thứ 2</option>
                    <option value="2" {{ $schedule->day_of_week == 2 ? 'selected' : '' }}>Thứ 3</option>
                    <option value="3" {{ $schedule->day_of_week == 3 ? 'selected' : '' }}>Thứ 4</option>
                    <option value="4" {{ $schedule->day_of_week == 4 ? 'selected' : '' }}>Thứ 5</option>
                    <option value="5" {{ $schedule->day_of_week == 5 ? 'selected' : '' }}>Thứ 6</option>
                    <option value="6" {{ $schedule->day_of_week == 6 ? 'selected' : '' }}>Thứ 7</option>
                </select>
                @error('day_of_week')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Buổi số --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="session_number" class="block text-sm font-medium text-gray-700 pt-2">
                    Buổi số <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Số thứ tự buổi học</p>
            </div>
            <div class="md:col-span-2">
                <input type="number" id="session_number" name="session_number" 
                       value="{{ old('session_number', $schedule->session_number) }}" min="1" required
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                @error('session_number')
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
                <p class="mt-1 text-xs text-gray-500">Khung giờ học của buổi</p>
            </div>
            <div class="md:col-span-2">
                <div class="flex space-x-4">
                    <div class="flex-1">
                        <label for="start_time" class="block text-xs font-medium text-gray-500">Bắt đầu</label>
                        <input type="time" id="start_time" name="start_time" 
                               value="{{ old('start_time', $schedule->start_time) }}" required
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        @error('start_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex-1">
                        <label for="end_time" class="block text-xs font-medium text-gray-500">Kết thúc</label>
                        <input type="time" id="end_time" name="end_time" 
                               value="{{ old('end_time', $schedule->end_time) }}" required
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        @error('end_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Trạng thái --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label class="block text-sm font-medium text-gray-700 pt-2">
                    Trạng thái
                </label>
                <p class="mt-1 text-xs text-gray-500">Đánh dấu nếu buổi học đã hoàn thành</p>
            </div>
            <div class="md:col-span-2 flex items-center">
                <input type="checkbox" id="is_taught" name="is_taught" value="1" 
                       {{ old('is_taught', $schedule->is_taught) ? 'checked' : '' }}
                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="is_taught" class="ml-2 block text-sm text-gray-700">
                    Đã dạy
                </label>
            </div>
        </div>
    </div>
@endsection
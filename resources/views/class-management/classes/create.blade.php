@extends('templates.create', [
    'entityName' => 'Lớp học',
    'routePrefix' => 'classes'
])

@section('form_fields')
    <div class="space-y-6">
        <!-- Mã lớp -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="class_code" class="block text-sm font-medium text-gray-700 pt-2">
                    Mã lớp <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Ví dụ: CSE101, MATH202</p>
            </div>
            <div class="md:col-span-2">
                <input type="text" id="class_code" name="class_code" value="{{ old('class_code') }}" required
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                       maxlength="50" placeholder="Nhập mã lớp">
                @error('class_code')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Khóa học -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="course_id" class="block text-sm font-medium text-gray-700 pt-2">
                    Khóa học <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Chọn khóa học</p>
            </div>
            <div class="md:col-span-2">
                <select id="course_id" name="course_id" required
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">-- Chọn khóa học --</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->name }}
                        </option>
                    @endforeach
                </select>
                @error('course_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Học kỳ -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="semester_id" class="block text-sm font-medium text-gray-700 pt-2">
                    Học kỳ <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Chọn học kỳ</p>
            </div>
            <div class="md:col-span-2">
                <select id="semester_id" name="semester_id" required
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">-- Chọn học kỳ --</option>
                    @foreach($semesters as $semester)
                        <option value="{{ $semester->id }}" {{ old('semester_id') == $semester->id ? 'selected' : '' }}>
                            {{ $semester->name }}
                        </option>
                    @endforeach
                </select>
                @error('semester_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Phòng học -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="room" class="block text-sm font-medium text-gray-700 pt-2">
                    Phòng học
                </label>
                <p class="mt-1 text-xs text-gray-500">Ví dụ: A101, Phòng máy 1</p>
            </div>
            <div class="md:col-span-2">
                <input type="text" id="room" name="room" value="{{ old('room') }}"
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                       maxlength="100" placeholder="Nhập phòng học">
                @error('room')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Số sinh viên tối đa -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="max_students" class="block text-sm font-medium text-gray-700 pt-2">
                    Số sinh viên tối đa <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Nhập số sinh viên tối đa cho lớp</p>
            </div>
            <div class="md:col-span-2">
                <input type="number" id="max_students" name="max_students" value="{{ old('max_students') }}" required min="1"
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                       placeholder="Nhập số sinh viên tối đa">
                @error('max_students')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Số sinh viên hiện tại -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="current_students" class="block text-sm font-medium text-gray-700 pt-2">
                    Số sinh viên hiện tại
                </label>
                <p class="mt-1 text-xs text-gray-500">Có thể để trống nếu chưa có</p>
            </div>
            <div class="md:col-span-2">
                <input type="number" id="current_students" name="current_students" value="{{ old('current_students') }}" min="0"
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                       placeholder="Nhập số sinh viên hiện tại">
                @error('current_students')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Loại lịch học -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="schedule_type" class="block text-sm font-medium text-gray-700 pt-2">
                    Loại lịch học <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Ví dụ: Sáng, Chiều, Tối</p>
            </div>
            <div class="md:col-span-2">
                <input type="text" id="schedule_type" name="schedule_type" value="{{ old('schedule_type') }}" required maxlength="100"
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                       placeholder="Nhập loại lịch học">
                @error('schedule_type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Ngày bắt đầu -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="start_date" class="block text-sm font-medium text-gray-700 pt-2">
                    Ngày bắt đầu <span class="text-red-500">*</span>
                </label>
            </div>
            <div class="md:col-span-2">
                <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" required
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                @error('start_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Ngày kết thúc -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="end_date" class="block text-sm font-medium text-gray-700 pt-2">
                    Ngày kết thúc <span class="text-red-500">*</span>
                </label>
            </div>
            <div class="md:col-span-2">
                <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" required
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                @error('end_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Trạng thái -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="status" class="block text-sm font-medium text-gray-700 pt-2">
                    Trạng thái <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Chọn trạng thái lớp học</p>
            </div>
            <div class="md:col-span-2">
                <select id="status" name="status" required
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">-- Chọn trạng thái --</option>
                    <option value="open" {{ old('status') == 'open' ? 'selected' : '' }}>Mở</option>
                    <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Đóng</option>
                    <option value="finished" {{ old('status') == 'finished' ? 'selected' : '' }}>Kết thúc</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
@endsection

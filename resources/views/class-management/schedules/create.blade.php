@extends('templates.create', [
    'entityName' => 'Lịch học',
    'routePrefix' => 'schedules'
])

@section('form_fields')
    <div class="space-y-6">
        {{-- Lớp học --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="class_id" class="block text-sm font-medium text-gray-700 pt-2">
                    Lớp học <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Chọn lớp học cần tạo lịch</p>
            </div>
            <div class="md:col-span-2">
                <select id="class_id" name="class_id" required
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">-- Chọn lớp học --</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->class_code }} - {{ $class->course->name }}
                        </option>
                    @endforeach
                </select>
                @error('class_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Ngày bắt đầu --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="start_date" class="block text-sm font-medium text-gray-700 pt-2">
                    Ngày bắt đầu <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Ngày bắt đầu lịch học</p>
            </div>
            <div class="md:col-span-2">
                <input type="date" id="start_date" name="start_date" 
                       value="{{ old('start_date', now()->format('Y-m-d')) }}" required
                       min="{{ now()->format('Y-m-d') }}"
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                @error('start_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Số buổi học --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="total_sessions" class="block text-sm font-medium text-gray-700 pt-2">
                    Số buổi học <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Tổng số buổi học cần tạo</p>
            </div>
            <div class="md:col-span-2">
                <input type="number" id="total_sessions" name="total_sessions" 
                       value="{{ old('total_sessions', 15) }}" min="1" max="100" required
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                @error('total_sessions')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Ngày học trong tuần --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label class="block text-sm font-medium text-gray-700 pt-2">
                    Ngày học trong tuần <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Chọn các ngày học trong tuần</p>
            </div>
            <div class="md:col-span-2">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach([
                        2 => 'Thứ 2',
                        3 => 'Thứ 3',
                        4 => 'Thứ 4',
                        5 => 'Thứ 5',
                        6 => 'Thứ 6',
                        7 => 'Thứ 7'
                    ] as $day => $label)
                    <div class="flex items-center">
                        <input type="checkbox" id="day_{{ $day }}" name="day_of_week[]" 
                               value="{{ $day }}" {{ in_array($day, old('day_of_week', [2,4])) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="day_{{ $day }}" class="ml-2 block text-sm text-gray-700">
                            {{ $label }}
                        </label>
                    </div>
                    @endforeach
                </div>
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
                <p class="mt-1 text-xs text-gray-500">Khung giờ học mỗi buổi</p>
            </div>
            <div class="md:col-span-2">
                <div class="flex space-x-4">
                    <div class="flex-1">
                        <label for="start_time" class="block text-xs font-medium text-gray-500">Bắt đầu</label>
                        <input type="time" id="start_time" name="start_time" 
                               value="{{ old('start_time', '07:30') }}" required
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        @error('start_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex-1">
                        <label for="end_time" class="block text-xs font-medium text-gray-500">Kết thúc</label>
                        <input type="time" id="end_time" name="end_time" 
                               value="{{ old('end_time', '09:30') }}" required
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        @error('end_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
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
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="theory" {{ old('session_type', 'theory') == 'theory' ? 'selected' : '' }}>Lý thuyết</option>
                    <option value="practice" {{ old('session_type') == 'practice' ? 'selected' : '' }}>Thực hành</option>
                    <option value="exam" {{ old('session_type') == 'exam' ? 'selected' : '' }}>Kiểm tra</option>
                    <option value="review" {{ old('session_type') == 'review' ? 'selected' : '' }}>Ôn tập</option>
                </select>
                @error('session_type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tự động tính toán ngày kết thúc dựa trên số buổi và ngày học
            const calculateEndDate = () => {
                // Logic tính toán ngày kết thúc có thể thêm ở đây
            };

            // Gắn sự kiện cho các trường liên quan
            document.getElementById('start_date').addEventListener('change', calculateEndDate);
            document.getElementById('total_sessions').addEventListener('change', calculateEndDate);
            document.querySelectorAll('input[name="day_of_week[]"]').forEach(checkbox => {
                checkbox.addEventListener('change', calculateEndDate);
            });
        });
    </script>
    @endpush
@endsection
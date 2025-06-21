@extends('templates.create', [
    'entityName' => 'Năm học',
    'routePrefix' => 'academic-years'
])

@section('form_fields')
    <div class="space-y-6">
        <!-- Tên năm học -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="name" class="block text-sm font-medium text-gray-700 pt-2">
                    Tên năm học <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Ví dụ: 2023-2024, 2024-2025</p>
            </div>
            <div class="md:col-span-2">
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                       placeholder="Nhập tên năm học">
                @error('name')
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
                <p class="mt-1 text-xs text-gray-500">Ngày bắt đầu năm học</p>
            </div>
            <div class="md:col-span-2">
                <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" required
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                       min="{{ now()->subYears(1)->format('Y-m-d') }}"
                       max="{{ now()->addYears(5)->format('Y-m-d') }}">
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
                <p class="mt-1 text-xs text-gray-500">Ngày kết thúc năm học</p>
            </div>
            <div class="md:col-span-2">
                <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" required
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                       min="{{ now()->subYears(1)->format('Y-m-d') }}"
                       max="{{ now()->addYears(5)->format('Y-m-d') }}">
                @error('end_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Trạng thái hoạt động -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label class="block text-sm font-medium text-gray-700 pt-2">
                    Trạng thái
                </label>
                <p class="mt-1 text-xs text-gray-500">Năm học hiện tại</p>
            </div>
            <div class="md:col-span-2">
                <div class="relative flex items-start">
                    <div class="flex items-center h-5">
                        <input id="is_active" name="is_active" type="checkbox" value="1"
                            {{ old('is_active', isset($academicYear) ? $academicYear->is_active : false) ? 'checked' : '' }}
                            class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                        <!-- Add a hidden input to ensure false value is sent when unchecked -->
                        <input type="hidden" name="is_active" value="0">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="is_active" class="font-medium text-gray-700">
                            Đặt làm năm học hiện tại
                        </label>
                        <p class="text-xs text-gray-500">
                            Khi chọn, tất cả các năm học khác sẽ được đặt thành không hoạt động
                        </p>
                    </div>
                </div>
                @error('is_active')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
@endsection
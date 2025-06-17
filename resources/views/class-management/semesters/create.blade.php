@extends('templates.create', [
    'entityName' => 'Kỳ học',
    'routePrefix' => 'semesters'
])

@section('form_fields')
    <div class="space-y-6">
        <!-- Tên kỳ học -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="name" class="block text-sm font-medium text-gray-700 pt-2">
                    Tên kỳ học <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Ví dụ: Kỳ I Đợt 1, Kỳ II Đợt 2</p>
            </div>
            <div class="md:col-span-2">
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                       placeholder="Nhập tên kỳ học">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Năm học -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="academic_year_id" class="block text-sm font-medium text-gray-700 pt-2">
                    Năm học <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Chọn năm học tương ứng</p>
            </div>
            <div class="md:col-span-2">
                <select id="academic_year_id" name="academic_year_id" required
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">-- Chọn năm học --</option>
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ old('academic_year_id') == $year->id ? 'selected' : '' }}>
                            {{ $year->name }} ({{ $year->start_date->format('d/m/Y') }} - {{ $year->end_date->format('d/m/Y') }})
                        </option>
                    @endforeach
                </select>
                @error('academic_year_id')
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
                <p class="mt-1 text-xs text-gray-500">Ngày bắt đầu kỳ học</p>
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
                <p class="mt-1 text-xs text-gray-500">Ngày kết thúc kỳ học</p>
            </div>
            <div class="md:col-span-2">
                <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" required
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                @error('end_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Loại kỳ học -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="type" class="block text-sm font-medium text-gray-700 pt-2">
                    Loại kỳ học <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Chọn loại học kỳ</p>
            </div>
            <div class="md:col-span-2">
                <select id="type" name="type" required
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">-- Chọn loại kỳ học --</option>
                    <option value="1" {{ old('type') == '1' ? 'selected' : '' }}>Học kỳ I</option>
                    <option value="2" {{ old('type') == '2' ? 'selected' : '' }}>Học kỳ II</option>
                </select>
                @error('type')
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
                <p class="mt-1 text-xs text-gray-500">Kích hoạt làm học kỳ hiện tại</p>
            </div>
            <div class="md:col-span-2">
                <div class="relative flex items-start">
                    <div class="flex items-center h-5">
                        <input type="hidden" name="is_active" value="0">
                        <input id="is_active" name="is_active" type="checkbox" value="1"
                            {{ old('is_active') ? 'checked' : '' }}
                            class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="is_active" class="font-medium text-gray-700">
                            Kích hoạt học kỳ này
                        </label>
                        <p class="text-xs text-gray-500">
                            Khi chọn, tất cả các kỳ học khác sẽ được đặt thành không hoạt động
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
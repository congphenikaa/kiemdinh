@extends('templates.create', [
    'entityName' => 'Hệ số lớp học',
    'routePrefix' => 'class-size-coefficients'
])

@section('form_fields')
    <div class="space-y-6">
        <!-- Academic Year -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="academic_year_id" class="block text-sm font-medium text-gray-700">
                    Năm học <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Chọn năm học áp dụng</p>
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

        <!-- Student Range -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label class="block text-sm font-medium text-gray-700">
                    Khoảng sinh viên <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Nhập số sinh viên tối thiểu và tối đa</p>
            </div>
            <div class="md:col-span-2 grid grid-cols-2 gap-4">
                <div>
                    <input type="number" name="min_students" id="min_students" 
                           value="{{ old('min_students') }}" min="1" required
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           placeholder="Từ">
                    @error('min_students')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <input type="number" name="max_students" id="max_students" 
                           value="{{ old('max_students') }}" min="2" required
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           placeholder="Đến">
                    @error('max_students')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Coefficient -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="coefficient" class="block text-sm font-medium text-gray-700">
                    Hệ số <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Hệ số nhân cho lương</p>
            </div>
            <div class="md:col-span-2">
                <div class="relative rounded-md shadow-sm">
                    <input type="number" step="0.01" min="0" name="coefficient" id="coefficient" 
                           value="{{ old('coefficient') }}" required
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           placeholder="Nhập hệ số">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">x</span>
                    </div>
                </div>
                @error('coefficient')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
@endsection
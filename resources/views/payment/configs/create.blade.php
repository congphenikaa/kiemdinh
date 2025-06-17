@extends('templates.create', [
    'entityName' => 'Cấu hình thanh toán',
    'routePrefix' => 'payment-configs'
])

@section('form_fields')
    <div class="space-y-6">
        <!-- Academic Year Selection -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="academic_year_id" class="block text-sm font-medium text-gray-700 pt-2">
                    Năm học <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Chọn năm học áp dụng cấu hình</p>
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

        <!-- Lương cơ bản/buổi -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="base_salary_per_session" class="block text-sm font-medium text-gray-700 pt-2">
                    Lương cơ bản/buổi <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Lương cơ bản cho mỗi buổi dạy (VNĐ)</p>
            </div>
            <div class="md:col-span-2">
                <div class="relative rounded-md shadow-sm">
                    <input type="number" id="base_salary_per_session" name="base_salary_per_session" 
                           value="{{ old('base_salary_per_session') }}" 
                           min="0" step="1000" required
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           placeholder="Nhập lương cơ bản">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">VNĐ</span>
                    </div>
                </div>
                @error('base_salary_per_session')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
@endsection
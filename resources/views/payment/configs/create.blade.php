@extends('templates.create', [
    'entityName' => 'Cấu hình thanh toán',
    'routePrefix' => 'payment-configs'
])

@section('form_fields')
    <div class="space-y-6">
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
                           value="{{ old('base_salary_per_session', $config->base_salary_per_session ?? '') }}" 
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
        
        <!-- Tỷ lệ buổi thực hành -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="practice_session_rate" class="block text-sm font-medium text-gray-700 pt-2">
                    Tỷ lệ buổi thực hành <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Từ 0 đến 1 (0.7 = 70% lương cơ bản)</p>
            </div>
            <div class="md:col-span-2">
                <div class="relative rounded-md shadow-sm">
                    <input type="number" step="0.01" min="0" max="1" id="practice_session_rate" 
                           name="practice_session_rate" 
                           value="{{ old('practice_session_rate', $config->practice_session_rate ?? '') }}" 
                           required
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           placeholder="Nhập tỷ lệ buổi thực hành">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">x</span>
                    </div>
                </div>
                @error('practice_session_rate')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
@endsection
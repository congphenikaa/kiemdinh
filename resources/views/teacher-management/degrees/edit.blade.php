@extends('templates.edit', [
    'entityName' => 'Bằng cấp',
    'routePrefix' => 'degrees',
    'model' => $degree
])

@section('form_fields')
    <div class="space-y-6">
        <!-- Tên đầy đủ -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="name" class="block text-sm font-medium text-gray-700 pt-2">
                    Tên đầy đủ <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Ví dụ: Tiến sĩ, Thạc sĩ, Cử nhân</p>
            </div>
            <div class="md:col-span-2">
                <input type="text" id="name" name="name" value="{{ old('name', $degree->name) }}" required
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                       placeholder="Nhập tên đầy đủ bằng cấp">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <!-- Tên viết tắt -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="short_name" class="block text-sm font-medium text-gray-700 pt-2">
                    Tên viết tắt <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Tối đa 10 ký tự</p>
            </div>
            <div class="md:col-span-2">
                <input type="text" id="short_name" name="short_name" 
                       value="{{ old('short_name', $degree->short_name) }}" required maxlength="10"
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm uppercase"
                       placeholder="Nhập tên viết tắt">
                @error('short_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Hệ số lương -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="salary_coefficient" class="block text-sm font-medium text-gray-700 pt-2">
                    Hệ số lương <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Từ 1.00 đến 10.00</p>
            </div>
            <div class="md:col-span-2">
                <div class="relative rounded-md shadow-sm">
                    <input type="number" step="0.01" min="1" max="10" id="salary_coefficient" 
                           name="salary_coefficient" 
                           value="{{ old('salary_coefficient', number_format($degree->salary_coefficient, 2)) }}" required
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm pr-10"
                           placeholder="Nhập hệ số lương">
                </div>
                @error('salary_coefficient')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                
                <!-- Thông tin giáo viên -->
                <div class="mt-2 text-sm text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    Hiện có <span class="font-medium">{{ $degree->teachers_count ?? 0 }}</span> giáo viên sử dụng hệ số này
                </div>
            </div>
        </div>
    </div>
@endsection

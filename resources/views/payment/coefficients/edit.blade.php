@extends('templates.edit', [
    'entityName' => 'Hệ số sĩ số',
    'routePrefix' => 'class-size-coefficients',
    'model' => $coefficient
])

@section('form_fields')
    <div class="space-y-6">
        <!-- Sĩ số tối thiểu -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="min_students" class="block text-sm font-medium text-gray-700 pt-2">
                    Sĩ số tối thiểu <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Số học sinh tối thiểu trong lớp</p>
            </div>
            <div class="md:col-span-2">
                <input type="number" id="min_students" name="min_students" 
                       value="{{ old('min_students', $coefficient->min_students) }}" 
                       min="0" required
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                       placeholder="Nhập sĩ số tối thiểu">
                @error('min_students')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <!-- Sĩ số tối đa -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="max_students" class="block text-sm font-medium text-gray-700 pt-2">
                    Sĩ số tối đa <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Phải lớn hơn sĩ số tối thiểu</p>
            </div>
            <div class="md:col-span-2">
                <input type="number" id="max_students" name="max_students" 
                       value="{{ old('max_students', $coefficient->max_students) }}" 
                       min="{{ old('min_students', $coefficient->min_students) + 1 }}" required
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                       placeholder="Nhập sĩ số tối đa">
                @error('max_students')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Hệ số -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="coefficient" class="block text-sm font-medium text-gray-700 pt-2">
                    Hệ số <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Hệ số nhân cho lương</p>
            </div>
            <div class="md:col-span-2">
                <input type="number" step="0.01" min="0" id="coefficient" name="coefficient" 
                       value="{{ old('coefficient', $coefficient->coefficient) }}" required
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                       placeholder="Nhập hệ số">
                @error('coefficient')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
@endsection
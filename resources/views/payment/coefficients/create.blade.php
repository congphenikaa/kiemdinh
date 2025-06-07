@extends('templates.create', [
    'entityName' => 'Hệ số sĩ số lớp',
    'routePrefix' => 'class-size-coefficients'
])

@section('form_fields')
    <div class="space-y-6">
        <!-- Sĩ số tối thiểu -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="min_students" class="block text-sm font-medium text-gray-700 pt-2">
                    Sĩ số tối thiểu <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Số học sinh nhỏ nhất trong khoảng</p>
            </div>
            <div class="md:col-span-2">
                <input type="number" id="min_students" name="min_students" value="{{ old('min_students') }}" 
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
                <input type="number" id="max_students" name="max_students" value="{{ old('max_students') }}" 
                       min="1" required
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
                <p class="mt-1 text-xs text-gray-500">Hệ số nhân cho sĩ số lớp</p>
            </div>
            <div class="md:col-span-2">
                <div class="relative rounded-md shadow-sm">
                    <input type="number" step="0.01" min="0" id="coefficient" 
                           name="coefficient" value="{{ old('coefficient', 1.00) }}" required
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm pr-10"
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

        <!-- Thông báo lỗi phạm vi trùng lặp -->
        @if(session('error'))
        <div class="rounded-md bg-red-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">
                        {{ session('error') }}
                    </h3>
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tự động validate sĩ số tối đa phải lớn hơn tối thiểu
        const minStudentsInput = document.getElementById('min_students');
        const maxStudentsInput = document.getElementById('max_students');

        function validateRange() {
            const min = parseInt(minStudentsInput.value);
            const max = parseInt(maxStudentsInput.value);
            
            if (min && max && max <= min) {
                maxStudentsInput.setCustomValidity('Sĩ số tối đa phải lớn hơn sĩ số tối thiểu');
            } else {
                maxStudentsInput.setCustomValidity('');
            }
        }

        minStudentsInput.addEventListener('change', validateRange);
        maxStudentsInput.addEventListener('change', validateRange);
    });
</script>
@endpush
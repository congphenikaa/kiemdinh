@extends('templates.create', [
    'entityName' => 'Khoa',
    'routePrefix' => 'faculties'
])

@section('form_fields')
    <div class="space-y-6">
        <!-- Tên khoa -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="name" class="block text-sm font-medium text-gray-700 pt-2">
                    Tên khoa <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Ví dụ: Công nghệ thông tin</p>
            </div>
            <div class="md:col-span-2">
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                       placeholder="Nhập tên đầy đủ của khoa">
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
                <p class="mt-1 text-xs text-gray-500">Ví dụ: CNTT</p>
            </div>
            <div class="md:col-span-2">
                <input type="text" id="short_name" name="short_name" value="{{ old('short_name') }}" required maxlength="10"
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm uppercase"
                       placeholder="Nhập tên viết tắt">
                @error('short_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Mô tả -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="description" class="block text-sm font-medium text-gray-700 pt-2">
                    Mô tả
                </label>
                <p class="mt-1 text-xs text-gray-500">Tối đa 255 ký tự</p>
            </div>
            <div class="md:col-span-2">
                <textarea id="description" name="description" rows="3"
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                       placeholder="Nhập mô tả về khoa">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Tự động chuyển tên viết tắt thành chữ hoa
    document.getElementById('short_name').addEventListener('input', function(e) {
        this.value = this.value.toUpperCase();
    });
</script>
@endpush
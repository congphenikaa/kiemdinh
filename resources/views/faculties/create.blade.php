@extends('templates.create', [
    'entityName' => 'Khoa',
    'routePrefix' => 'faculties'
])
@section('form_fields')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
        <div class="md:col-span-1">
            <label for="name" class="block text-sm font-medium text-gray-700 pt-2">
                Tên đầy đủ <span class="text-red-500">*</span>
            </label>
        </div>
        <div class="md:col-span-2">
            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                   placeholder="Nhập tên đầy đủ khoa">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
        <div class="md:col-span-1">
            <label for="short_name" class="block text-sm font-medium text-gray-700 pt-2">
                Tên viết tắt <span class="text-red-500">*</span>
            </label>
        </div>
        <div class="md:col-span-2">
            <input type="text" id="short_name" name="short_name" value="{{ old('short_name') }}" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                   placeholder="Nhập tên viết tắt">
            @error('short_name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Mô tả nhiệm vụ -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
        <div class="md:col-span-1">
            <label for="description" class="block text-sm font-medium text-gray-700 pt-2">Mô tả nhiệm vụ</label>
        </div>
        <div class="md:col-span-2">
            <textarea id="description" name="description" rows="3"
                      class="w-full px -3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Nhập mô tả nhiệm vụ">{{ old('description') }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
@endsection

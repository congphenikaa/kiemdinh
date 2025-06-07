@extends('templates.edit', [
    'entityName' => 'Bằng cấp',
    'routePrefix' => 'degrees',
    'model' => $degree
])

@section('form_fields')
    <!-- Tên đầy đủ -->
    <div class="mb-4">
        <label for="name" class="block text-gray-700 text-sm font-medium mb-2">Tên đầy đủ <span class="text-red-500">*</span></label>
        <input type="text" id="name" name="name" value="{{ old('name', $degree->name) }}" required
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        @error('name')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Tên viết tắt -->
    <div class="mb-4">
        <label for="short_name" class="block text-gray-700 text-sm font-medium mb-2">Tên viết tắt <span class="text-red-500">*</span></label>
        <input type="text" id="short_name" name="short_name" value="{{ old('short_name', $degree->short_name) }}" required
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        @error('short_name')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>
@endsection
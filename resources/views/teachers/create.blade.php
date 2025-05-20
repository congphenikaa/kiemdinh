@extends('templates.create', [
    'entityName' => 'Giáo viên',
    'routePrefix' => 'teachers'
])

@section('form_fields')
     <!-- Mã số -->
    <div class="space-y-2">
        <label for="code" class="block text-sm font-medium text-gray-700">Mã số</label>
        <input type="text" id="code" name="code" value="{{ old('code') }}" 
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" 
                placeholder="Để trống để tự sinh">
        @error('code')
            <span class="text-sm text-red-600">{{ $message }}</span>
        @enderror
    </div>
    
    <!-- Họ tên -->
    <div class="space-y-2">
        <label for="name" class="block text-sm font-medium text-gray-700">
            Họ tên <span class="text-red-500">*</span>
        </label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
        @error('name')
            <span class="text-sm text-red-600">{{ $message }}</span>
        @enderror
    </div>
    
    <!-- Ngày sinh -->
    <div class="space-y-2">
        <label for="dob" class="block text-sm font-medium text-gray-700">
            Ngày sinh <span class="text-red-500">*</span>
        </label>
        <input type="date" id="dob" name="dob" value="{{ old('dob') }}" required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
        @error('dob')
            <span class="text-sm text-red-600">{{ $message }}</span>
        @enderror
    </div>
    
    <!-- Điện thoại -->
    <div class="space-y-2">
        <label for="phone" class="block text-sm font-medium text-gray-700">Điện thoại</label>
        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
        @error('phone')
            <span class="text-sm text-red-600">{{ $message }}</span>
        @enderror
    </div>
    
    <!-- Email -->
    <div class="space-y-2">
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
        @error('email')
            <span class="text-sm text-red-600">{{ $message }}</span>
        @enderror
    </div>
    
    <!-- Khoa -->
    <div class="space-y-2">
        <label for="faculty_id" class="block text-sm font-medium text-gray-700">
            Khoa <span class="text-red-500">*</span>
        </label>
        <select id="faculty_id" name="faculty_id" required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            <option value="">-- Chọn khoa --</option>
            @foreach($faculties as $faculty)
                <option value="{{ $faculty->id }}" {{ old('faculty_id') == $faculty->id ? 'selected' : '' }}>
                    {{ $faculty->name }}
                </option>
            @endforeach
        </select>
        @error('faculty_id')
            <span class="text-sm text-red-600">{{ $message }}</span>
        @enderror
    </div>
    
    <!-- Bằng cấp -->
    <div class="space-y-2">
        <label for="degree_id" class="block text-sm font-medium text-gray-700">
            Bằng cấp <span class="text-red-500">*</span>
        </label>
        <select id="degree_id" name="degree_id" required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            <option value="">-- Chọn bằng cấp --</option>
            @foreach($degrees as $degree)
                <option value="{{ $degree->id }}" {{ old('degree_id') == $degree->id ? 'selected' : '' }}>
                    {{ $degree->name }}
                </option>
            @endforeach
        </select>
        @error('degree_id')
            <span class="text-sm text-red-600">{{ $message }}</span>
        @enderror
    </div>
@endsection













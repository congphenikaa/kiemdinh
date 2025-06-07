@extends('templates.edit', [
    'entityName' => 'Giáo viên',
    'routePrefix' => 'teachers',
    'model' => $teacher
])

@section('form_fields')
    <!-- Mã số -->
    <div class="mb-4">
        <label for="code" class="block text-gray-700 text-sm font-medium mb-2">Mã số</label>
        <input type="text" id="code" name="code" value="{{ old('code', $teacher->code) }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        @error('code')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Họ tên -->
    <div class="mb-4">
        <label for="name" class="block text-gray-700 text-sm font-medium mb-2">Họ tên <span class="text-red-500">*</span></label>
        <input type="text" id="name" name="name" value="{{ old('name', $teacher->name) }}" required
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        @error('name')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Ngày sinh -->
    <div class="mb-4">
        <label for="dob" class="block text-gray-700 text-sm font-medium mb-2">Ngày sinh <span class="text-red-500">*</span></label>
        <input type="date" id="dob" name="dob" value="{{ old('dob', $teacher->dob->format('Y-m-d')) }}" required
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        @error('dob')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Điện thoại -->
    <div class="mb-4">
        <label for="phone" class="block text-gray-700 text-sm font-medium mb-2">Điện thoại</label>
        <input type="tel" id="phone" name="phone" value="{{ old('phone', $teacher->phone) }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        @error('phone')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Email -->
    <div class="mb-4">
        <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email', $teacher->email) }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        @error('email')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Khoa -->
    <div class="mb-4">
        <label for="faculty_id" class="block text-gray-700 text-sm font-medium mb-2">Khoa <span class="text-red-500">*</span></label>
        <select id="faculty_id" name="faculty_id" required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">-- Chọn khoa --</option>
            @foreach($faculties as $faculty)
                <option value="{{ $faculty->id }}" {{ (old('faculty_id', $teacher->faculty_id) == $faculty->id) ? 'selected' : '' }}>
                    {{ $faculty->name }}
                </option>
            @endforeach
        </select>
        @error('faculty_id')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Bằng cấp -->
    <div class="mb-4">
        <label for="degree_id" class="block text-gray-700 text-sm font-medium mb-2">Bằng cấp <span class="text-red-500">*</span></label>
        <select id="degree_id" name="degree_id" required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">-- Chọn bằng cấp --</option>
            @foreach($degrees as $degree)
                <option value="{{ $degree->id }}" {{ (old('degree_id', $teacher->degree_id) == $degree->id) ? 'selected' : '' }}>
                    {{ $degree->name }}
                </option>
            @endforeach
        </select>
        @error('degree_id')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>
@endsection
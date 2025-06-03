@extends('templates.edit', [
    'entityName' => 'Học phần',
    'routePrefix' => 'courses',
    'model' => $course
])

@section('form_fields')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
        <div class="md:col-span-1">
            <label for="course_code" class="block text-sm font-medium text-gray-700 pt-2">
                Mã học phần <span class="text-red-500">*</span>
            </label>
        </div>
        <div class="md:col-span-2">
            <input type="text" id="course_code" name="course_code" value="{{ old('course_code', $course->course_code) }}" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                   placeholder="Ví dụ: IT101">
            @error('course_code')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
        <div class="md:col-span-1">
            <label for="name" class="block text-sm font-medium text-gray-700 pt-2">
                Tên học phần <span class="text-red-500">*</span>
            </label>
        </div>
        <div class="md:col-span-2">
            <input type="text" id="name" name="name" value="{{ old('name', $course->name) }}" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                   placeholder="Nhập tên học phần">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
        <div class="md:col-span-1">
            <label for="total_sessions" class="block text-sm font-medium text-gray-700 pt-2">
                Số buổi học <span class="text-red-500">*</span>
            </label>
        </div>
        <div class="md:col-span-2">
            <input type="number" id="total_sessions" name="total_sessions" value="{{ old('total_sessions', $course->total_sessions) }}" required min="1" max="60"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                   placeholder="Nhập số buổi học">
            @error('total_sessions')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
        <div class="md:col-span-1">
            <label for="description" class="block text-sm font-medium text-gray-700 pt-2">
                Mô tả (tùy chọn)
            </label>
        </div>
        <div class="md:col-span-2">
            <textarea id="description" name="description"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                      rows="4"
                      placeholder="Nhập mô tả học phần">{{ old('description', $course->description) }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
        <div class="md:col-span-1">
            <label for="department_id" class="block text-sm font-medium text-gray-700 pt-2">
                Thuộc bộ môn <span class="text-red-500">*</span>
            </label>
        </div>
        <div class="md:col-span-2">
            <select id="department_id" name="department_id" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                <option value="">-- Chọn bộ môn --</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}" {{ old('department_id', $course->department_id) == $department->id ? 'selected' : '' }}>
                        {{ $department->name }} ({{ $department->faculty->name }})
                    </option>
                @endforeach
            </select>
            @error('department_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
@endsection

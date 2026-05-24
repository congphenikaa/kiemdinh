@extends('templates.edit', [
    'entityName' => 'Học phần',
    'routePrefix' => 'courses',
    'model' => $course
])

@section('form_fields')
<div class="space-y-6">
    <!-- Mã môn học -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="md:col-span-1">
            <label for="course_code" class="form-label">
                Mã môn học <span class="text-red-500">*</span>
            </label>
            <p class="form-hint">Ví dụ: CS101, MATH202</p>
        </div>
        <div class="md:col-span-2">
            <input type="text" id="course_code" name="course_code"
                   value="{{ old('course_code', $course->course_code) }}" required
                   class="form-input"
                   placeholder="Nhập mã môn học">
            @error('course_code')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Tên môn học -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="md:col-span-1">
            <label for="name" class="form-label">
                Tên môn học <span class="text-red-500">*</span>
            </label>
        </div>
        <div class="md:col-span-2">
            <input type="text" id="name" name="name"
                   value="{{ old('name', $course->name) }}" required
                   class="form-input"
                   placeholder="Nhập tên môn học">
            @error('name')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Số tín chỉ -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="md:col-span-1">
            <label for="credit_hours" class="form-label">
                Số tín chỉ <span class="text-red-500">*</span>
            </label>
        </div>
        <div class="md:col-span-2">
            <input type="number" id="credit_hours" name="credit_hours"
                   value="{{ old('credit_hours', $course->credit_hours) }}" min="1" max="10" required
                   class="form-input">
            @error('credit_hours')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Tổng số buổi học -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="md:col-span-1">
            <label for="total_sessions" class="form-label">
                Tổng số buổi học <span class="text-red-500">*</span>
            </label>
        </div>
        <div class="md:col-span-2">
            <input type="number" id="total_sessions" name="total_sessions"
                   value="{{ old('total_sessions', $course->total_sessions) }}" min="1" max="50" required
                   class="form-input">
            @error('total_sessions')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Mô tả -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="md:col-span-1">
            <label for="description" class="form-label">
                Mô tả môn học
            </label>
        </div>
        <div class="md:col-span-2">
            <textarea id="description" name="description" rows="4"
                      class="form-input"
                      placeholder="Nhập mô tả môn học">{{ old('description', $course->description) }}</textarea>
            @error('description')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Khoa (Faculty) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="md:col-span-1">
            <label for="faculty_id" class="form-label">
                Khoa <span class="text-red-500">*</span>
            </label>
        </div>
        <div class="md:col-span-2">
            <select id="faculty_id" name="faculty_id" required
                    class="form-input">
                <option value="">-- Chọn khoa --</option>
                @foreach($faculties as $faculty)
                    <option value="{{ $faculty->id }}"
                        {{ old('faculty_id', $course->faculty_id) == $faculty->id ? 'selected' : '' }}>
                        {{ $faculty->name }}
                    </option>
                @endforeach
            </select>
            @error('faculty_id')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
@endsection

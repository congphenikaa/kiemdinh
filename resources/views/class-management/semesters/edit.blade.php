@extends('templates.edit', [
    'entityName' => 'Kỳ học',
    'routePrefix' => 'semesters',
    'model' => $semester
])

@section('form_fields')
    <div class="space-y-6">
        <!-- Tên kỳ học -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="name" class="form-label">
                    Tên kỳ học <span class="text-red-500">*</span>
                </label>
                <p class="form-hint">Ví dụ: Kỳ I Đợt 1, Kỳ II Đợt 2</p>
            </div>
            <div class="md:col-span-2">
                <input type="text" id="name" name="name" value="{{ old('name', $semester->name) }}" required
                       class="form-input"
                       placeholder="Nhập tên kỳ học">
                @error('name')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Năm học -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="academic_year_id" class="form-label">
                    Năm học <span class="text-red-500">*</span>
                </label>
                <p class="form-hint">Chọn năm học tương ứng</p>
            </div>
            <div class="md:col-span-2">
                <select id="academic_year_id" name="academic_year_id" required
                        class="form-input">
                    <option value="">-- Chọn năm học --</option>
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ old('academic_year_id', $semester->academic_year_id) == $year->id ? 'selected' : '' }}>
                            {{ $year->name }} ({{ $year->start_date->format('d/m/Y') }} - {{ $year->end_date->format('d/m/Y') }})
                        </option>
                    @endforeach
                </select>
                @error('academic_year_id')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Ngày bắt đầu -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="start_date" class="form-label">
                    Ngày bắt đầu <span class="text-red-500">*</span>
                </label>
                <p class="form-hint">Ngày bắt đầu kỳ học</p>
            </div>
            <div class="md:col-span-2">
                <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $semester->start_date->format('Y-m-d')) }}" required
                       class="form-input">
                @error('start_date')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Ngày kết thúc -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="end_date" class="form-label">
                    Ngày kết thúc <span class="text-red-500">*</span>
                </label>
                <p class="form-hint">Ngày kết thúc kỳ học</p>
            </div>
            <div class="md:col-span-2">
                <input type="date" id="end_date" name="end_date" value="{{ old('end_date', $semester->end_date->format('Y-m-d')) }}" required
                       class="form-input">
                @error('end_date')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Loại kỳ học -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="type" class="form-label">
                    Loại kỳ học <span class="text-red-500">*</span>
                </label>
                <p class="form-hint">Chọn loại học kỳ</p>
            </div>
            <div class="md:col-span-2">
                <select id="type" name="type" required
                        class="form-input">
                    <option value="">-- Chọn loại kỳ học --</option>
                    <option value="1" {{ old('type', $semester->type) == '1' ? 'selected' : '' }}>Học kỳ I</option>
                    <option value="2" {{ old('type', $semester->type) == '2' ? 'selected' : '' }}>Học kỳ II</option>
                </select>
                @error('type')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Trạng thái hoạt động -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label class="form-label">
                    Trạng thái
                </label>
                <p class="form-hint">Kích hoạt làm học kỳ hiện tại</p>
            </div>
            <div class="md:col-span-2">
                <div class="relative flex items-start">
                    <div class="flex items-center h-5">
                        <input type="hidden" name="is_active" value="0">
                        <input id="is_active" name="is_active" type="checkbox" value="1"
                            {{ old('is_active', $semester->is_active) ? 'checked' : '' }}
                            class="form-checkbox">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="is_active" class="font-medium text-gray-700">
                            Kích hoạt học kỳ này
                        </label>
                        <p class="text-xs text-gray-500">
                            Khi chọn, tất cả các kỳ học khác sẽ được đặt thành không hoạt động
                        </p>
                    </div>
                </div>
                @error('is_active')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
@endsection
@extends('templates.edit', [
    'entityName' => 'Giảng viên',
    'routePrefix' => 'teachers',
    'model' => $teacher
])

@section('form_fields')
    <div class="space-y-6">
        <!-- Thông tin cơ bản -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="code" class="form-label">
                    Mã giảng viên <span class="text-red-500">*</span>
                </label>
                <p class="form-hint">Mã duy nhất để nhận diện</p>
            </div>
            <div class="md:col-span-2">
                <input type="text" id="code" name="code" value="{{ old('code', $teacher->code) }}" required
                       class="form-input uppercase"
                       placeholder="Nhập mã giảng viên">
                @error('code')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="name" class="form-label">
                    Họ và tên <span class="text-red-500">*</span>
                </label>
                <p class="form-hint">Nhập đầy đủ họ tên</p>
            </div>
            <div class="md:col-span-2">
                <input type="text" id="name" name="name" value="{{ old('name', $teacher->name) }}" required
                       class="form-input"
                       placeholder="Nhập họ và tên">
                @error('name')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Thông tin cá nhân -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="dob" class="form-label">
                    Ngày sinh <span class="text-red-500">*</span>
                </label>
                <p class="form-hint">Định dạng dd/mm/yyyy</p>
            </div>
            <div class="md:col-span-2">
                <input type="date" id="dob" name="dob" value="{{ old('dob', $teacher->dob->format('Y-m-d')) }}" required
                       class="form-input">
                @error('dob')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="gender" class="form-label">
                    Giới tính <span class="text-red-500">*</span>
                </label>
            </div>
            <div class="md:col-span-2">
                <select id="gender" name="gender" required
                        class="form-input">
                    <option value="">-- Chọn giới tính --</option>
                    <option value="male" {{ old('gender', $teacher->gender) == 'male' ? 'selected' : '' }}>Nam</option>
                    <option value="female" {{ old('gender', $teacher->gender) == 'female' ? 'selected' : '' }}>Nữ</option>
                    <option value="other" {{ old('gender', $teacher->gender) == 'other' ? 'selected' : '' }}>Khác</option>
                </select>
                @error('gender')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Thông tin liên hệ -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="phone" class="form-label">
                    Số điện thoại <span class="text-red-500">*</span>
                </label>
                <p class="form-hint">Số điện thoại liên hệ</p>
            </div>
            <div class="md:col-span-2">
                <input type="tel" id="phone" name="phone" value="{{ old('phone', $teacher->phone) }}" required
                       class="form-input"
                       placeholder="Nhập số điện thoại">
                @error('phone')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="email" class="form-label">
                    Email <span class="text-red-500">*</span>
                </label>
                <p class="form-hint">Email liên hệ</p>
            </div>
            <div class="md:col-span-2">
                <input type="email" id="email" name="email" value="{{ old('email', $teacher->email) }}" required
                       class="form-input"
                       placeholder="Nhập email">
                @error('email')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="address" class="form-label">
                    Địa chỉ <span class="text-red-500">*</span>
                </label>
                <p class="form-hint">Địa chỉ thường trú</p>
            </div>
            <div class="md:col-span-2">
                <textarea id="address" name="address" rows="2" required
                          class="form-input"
                          placeholder="Nhập địa chỉ">{{ old('address', $teacher->address) }}</textarea>
                @error('address')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Thông tin công việc -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="faculty_id" class="form-label">
                    Khoa <span class="text-red-500">*</span>
                </label>
                <p class="form-hint">Khoa công tác</p>
            </div>
            <div class="md:col-span-2">
                <select id="faculty_id" name="faculty_id" required
                        class="form-input">
                    <option value="">-- Chọn khoa --</option>
                    @foreach($faculties as $faculty)
                        <option value="{{ $faculty->id }}" {{ old('faculty_id', $teacher->faculty_id) == $faculty->id ? 'selected' : '' }}>
                            {{ $faculty->name }}
                        </option>
                    @endforeach
                </select>
                @error('faculty_id')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="degree_id" class="form-label">
                    Bằng cấp <span class="text-red-500">*</span>
                </label>
                <p class="form-hint">Trình độ chuyên môn</p>
            </div>
            <div class="md:col-span-2">
                <select id="degree_id" name="degree_id" required
                        class="form-input">
                    <option value="">-- Chọn bằng cấp --</option>
                    @foreach($degrees as $degree)
                        <option value="{{ $degree->id }}" {{ old('degree_id', $teacher->degree_id) == $degree->id ? 'selected' : '' }}>
                            {{ $degree->name }} ({{ $degree->short_name }})
                        </option>
                    @endforeach
                </select>
                @error('degree_id')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="start_date" class="form-label">
                    Ngày bắt đầu <span class="text-red-500">*</span>
                </label>
                <p class="form-hint">Ngày bắt đầu công tác</p>
            </div>
            <div class="md:col-span-2">
                <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $teacher->start_date->format('Y-m-d')) }}" required
                       class="form-input">
                @error('start_date')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Trạng thái và ghi chú -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label class="form-label">
                    Trạng thái
                </label>
                <p class="form-hint">Hoạt động/Không hoạt động</p>
            </div>
            <div class="md:col-span-2">
                <div class="flex items-center">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" id="is_active" name="is_active" value="1"
                        {{ old('is_active', $teacher->is_active) == '1' ? 'checked' : '' }}
                        class="form-checkbox">
                    <label for="is_active" class="ml-2 block text-sm text-gray-700">
                        Đang hoạt động
                    </label>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="notes" class="form-label">
                    Ghi chú
                </label>
                <p class="form-hint">Thông tin bổ sung</p>
            </div>
            <div class="md:col-span-2">
                <textarea id="notes" name="notes" rows="3"
                          class="form-input"
                          placeholder="Nhập ghi chú (nếu có)">{{ old('notes', $teacher->notes) }}</textarea>
                @error('notes')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
@endsection
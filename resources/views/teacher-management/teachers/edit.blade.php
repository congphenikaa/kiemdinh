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
                <label for="code" class="block text-sm font-medium text-gray-700 pt-2">
                    Mã giảng viên <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Mã duy nhất để nhận diện</p>
            </div>
            <div class="md:col-span-2">
                <input type="text" id="code" name="code" value="{{ old('code', $teacher->code) }}" required
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm uppercase"
                       placeholder="Nhập mã giảng viên">
                @error('code')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="name" class="block text-sm font-medium text-gray-700 pt-2">
                    Họ và tên <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Nhập đầy đủ họ tên</p>
            </div>
            <div class="md:col-span-2">
                <input type="text" id="name" name="name" value="{{ old('name', $teacher->name) }}" required
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                       placeholder="Nhập họ và tên">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Thông tin cá nhân -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="dob" class="block text-sm font-medium text-gray-700 pt-2">
                    Ngày sinh <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Định dạng dd/mm/yyyy</p>
            </div>
            <div class="md:col-span-2">
                <input type="date" id="dob" name="dob" value="{{ old('dob', $teacher->dob->format('Y-m-d')) }}" required
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                @error('dob')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="gender" class="block text-sm font-medium text-gray-700 pt-2">
                    Giới tính <span class="text-red-500">*</span>
                </label>
            </div>
            <div class="md:col-span-2">
                <select id="gender" name="gender" required
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">-- Chọn giới tính --</option>
                    <option value="male" {{ old('gender', $teacher->gender) == 'male' ? 'selected' : '' }}>Nam</option>
                    <option value="female" {{ old('gender', $teacher->gender) == 'female' ? 'selected' : '' }}>Nữ</option>
                    <option value="other" {{ old('gender', $teacher->gender) == 'other' ? 'selected' : '' }}>Khác</option>
                </select>
                @error('gender')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Thông tin liên hệ -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="phone" class="block text-sm font-medium text-gray-700 pt-2">
                    Số điện thoại <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Số điện thoại liên hệ</p>
            </div>
            <div class="md:col-span-2">
                <input type="tel" id="phone" name="phone" value="{{ old('phone', $teacher->phone) }}" required
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                       placeholder="Nhập số điện thoại">
                @error('phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="email" class="block text-sm font-medium text-gray-700 pt-2">
                    Email <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Email liên hệ</p>
            </div>
            <div class="md:col-span-2">
                <input type="email" id="email" name="email" value="{{ old('email', $teacher->email) }}" required
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                       placeholder="Nhập email">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="address" class="block text-sm font-medium text-gray-700 pt-2">
                    Địa chỉ <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Địa chỉ thường trú</p>
            </div>
            <div class="md:col-span-2">
                <textarea id="address" name="address" rows="2" required
                          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                          placeholder="Nhập địa chỉ">{{ old('address', $teacher->address) }}</textarea>
                @error('address')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Thông tin công việc -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="faculty_id" class="block text-sm font-medium text-gray-700 pt-2">
                    Khoa <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Khoa công tác</p>
            </div>
            <div class="md:col-span-2">
                <select id="faculty_id" name="faculty_id" required
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">-- Chọn khoa --</option>
                    @foreach($faculties as $faculty)
                        <option value="{{ $faculty->id }}" {{ old('faculty_id', $teacher->faculty_id) == $faculty->id ? 'selected' : '' }}>
                            {{ $faculty->name }}
                        </option>
                    @endforeach
                </select>
                @error('faculty_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="degree_id" class="block text-sm font-medium text-gray-700 pt-2">
                    Bằng cấp <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Trình độ chuyên môn</p>
            </div>
            <div class="md:col-span-2">
                <select id="degree_id" name="degree_id" required
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">-- Chọn bằng cấp --</option>
                    @foreach($degrees as $degree)
                        <option value="{{ $degree->id }}" {{ old('degree_id', $teacher->degree_id) == $degree->id ? 'selected' : '' }}>
                            {{ $degree->name }} ({{ $degree->short_name }})
                        </option>
                    @endforeach
                </select>
                @error('degree_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="start_date" class="block text-sm font-medium text-gray-700 pt-2">
                    Ngày bắt đầu <span class="text-red-500">*</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Ngày bắt đầu công tác</p>
            </div>
            <div class="md:col-span-2">
                <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $teacher->start_date->format('Y-m-d')) }}" required
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                @error('start_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Trạng thái và ghi chú -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label class="block text-sm font-medium text-gray-700 pt-2">
                    Trạng thái
                </label>
                <p class="mt-1 text-xs text-gray-500">Hoạt động/Không hoạt động</p>
            </div>
            <div class="md:col-span-2">
                <div class="flex items-center">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" id="is_active" name="is_active" value="1"
                        {{ old('is_active', $teacher->is_active) == '1' ? 'checked' : '' }}
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-700">
                        Đang hoạt động
                    </label>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="notes" class="block text-sm font-medium text-gray-700 pt-2">
                    Ghi chú
                </label>
                <p class="mt-1 text-xs text-gray-500">Thông tin bổ sung</p>
            </div>
            <div class="md:col-span-2">
                <textarea id="notes" name="notes" rows="3"
                          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                          placeholder="Nhập ghi chú (nếu có)">{{ old('notes', $teacher->notes) }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
@endsection
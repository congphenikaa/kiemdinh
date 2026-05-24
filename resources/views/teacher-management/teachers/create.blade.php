@extends('templates.create', [
    'entityName' => 'Giảng viên',
    'routePrefix' => 'teachers'
])

@section('form_fields')
    <div class="space-y-8">
        <x-form-section title="Thông tin cơ bản">
            <x-form-field label="Mã giảng viên" for="code" hint="Mã duy nhất" :required="true">
                <input type="text" id="code" name="code" value="{{ old('code') }}" required class="form-input uppercase" placeholder="Mã GV">
                <x-form-error field="code" />
            </x-form-field>
            <x-form-field label="Họ và tên" for="name" :required="true">
                <input type="text" id="name" name="name" value="{{ old('name') }}" required class="form-input" placeholder="Họ và tên">
                <x-form-error field="name" />
            </x-form-field>
        </x-form-section>

        <x-form-section title="Thông tin cá nhân">
            <x-form-field label="Ngày sinh" for="dob" :required="true">
                <input type="date" id="dob" name="dob" value="{{ old('dob') }}" required class="form-input">
                <x-form-error field="dob" />
            </x-form-field>
            <x-form-field label="Giới tính" for="gender" :required="true">
                <select id="gender" name="gender" required class="form-select">
                    <option value="">-- Chọn --</option>
                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Nam</option>
                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Nữ</option>
                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Khác</option>
                </select>
                <x-form-error field="gender" />
            </x-form-field>
        </x-form-section>

        <x-form-section title="Liên hệ">
            <x-form-field label="Số điện thoại" for="phone" :required="true">
                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required class="form-input">
                <x-form-error field="phone" />
            </x-form-field>
            <x-form-field label="Email" for="email" :required="true">
                <input type="email" id="email" name="email" value="{{ old('email') }}" required class="form-input">
                <x-form-error field="email" />
            </x-form-field>
            <x-form-field label="Địa chỉ" for="address" :required="true">
                <textarea id="address" name="address" rows="2" required class="form-textarea" placeholder="Địa chỉ">{{ old('address') }}</textarea>
                <x-form-error field="address" />
            </x-form-field>
        </x-form-section>

        <x-form-section title="Công tác">
            <x-form-field label="Khoa" for="faculty_id" :required="true">
                <select id="faculty_id" name="faculty_id" required class="form-select">
                    <option value="">-- Chọn khoa --</option>
                    @foreach($faculties as $faculty)
                        <option value="{{ $faculty->id }}" {{ old('faculty_id') == $faculty->id ? 'selected' : '' }}>{{ $faculty->name }}</option>
                    @endforeach
                </select>
                <x-form-error field="faculty_id" />
            </x-form-field>
            <x-form-field label="Bằng cấp" for="degree_id" :required="true">
                <select id="degree_id" name="degree_id" required class="form-select">
                    <option value="">-- Chọn bằng cấp --</option>
                    @foreach($degrees as $degree)
                        <option value="{{ $degree->id }}" {{ old('degree_id') == $degree->id ? 'selected' : '' }}>
                            {{ $degree->name }} ({{ $degree->short_name }})
                        </option>
                    @endforeach
                </select>
                <x-form-error field="degree_id" />
            </x-form-field>
            <x-form-field label="Ngày bắt đầu" for="start_date" :required="true">
                <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" required class="form-input">
                <x-form-error field="start_date" />
            </x-form-field>
            <x-form-field label="Trạng thái">
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" id="is_active" name="is_active" value="1" class="form-checkbox" {{ old('is_active', true) ? 'checked' : '' }}>
                    <span class="text-sm text-slate-700">Đang hoạt động</span>
                </label>
            </x-form-field>
            <x-form-field label="Ghi chú" for="notes">
                <textarea id="notes" name="notes" rows="3" class="form-textarea" placeholder="Ghi chú (tùy chọn)">{{ old('notes') }}</textarea>
                <x-form-error field="notes" />
            </x-form-field>
        </x-form-section>
    </div>
@endsection

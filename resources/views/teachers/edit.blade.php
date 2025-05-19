@extends('layouts.app')

@section('title', 'Chỉnh sửa giáo viên')

@section('breadcrumb', 'Trang chủ / Giáo viên / Chỉnh sửa')

@section('content')
<div class="content-section">
    <div class="content-header">
        <h3>Chỉnh sửa giáo viên</h3>
        <a href="{{ route('teachers.index') }}" class="btn btn-back">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>
    <div class="form-container">
        <form action="{{ route('teachers.update', $teacher) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="code">Mã số</label>
                <input type="text" id="code" name="code" value="{{ old('code', $teacher->code) }}">
                @error('code')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="name">Họ tên <span class="required">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name', $teacher->name) }}" required>
                @error('name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="dob">Ngày sinh <span class="required">*</span></label>
                <input type="date" id="dob" name="dob" value="{{ old('dob', $teacher->dob->format('Y-m-d')) }}" required>
                @error('dob')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="phone">Điện thoại</label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone', $teacher->phone) }}">
                @error('phone')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $teacher->email) }}">
                @error('email')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="faculty_id">Khoa <span class="required">*</span></label>
                <select id="faculty_id" name="faculty_id" required>
                    <option value="">-- Chọn khoa --</option>
                    @foreach($faculties as $faculty)
                        <option value="{{ $faculty->id }}" {{ (old('faculty_id', $teacher->faculty_id) == $faculty->id) ? 'selected' : '' }}>
                            {{ $faculty->name }}
                        </option>
                    @endforeach
                </select>
                @error('faculty_id')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="degree_id">Bằng cấp <span class="required">*</span></label>
                <select id="degree_id" name="degree_id" required>
                    <option value="">-- Chọn bằng cấp --</option>
                    @foreach($degrees as $degree)
                        <option value="{{ $degree->id }}" {{ (old('degree_id', $teacher->degree_id) == $degree->id) ? 'selected' : '' }}>
                            {{ $degree->name }}
                        </option>
                    @endforeach
                </select>
                @error('degree_id')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Cập nhật</button>
                <a href="{{ route('teachers.index') }}" class="btn btn-secondary">Hủy</a>
            </div>
        </form>
    </div>
</div>
@endsection 
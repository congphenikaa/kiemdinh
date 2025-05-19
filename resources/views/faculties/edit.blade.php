@extends('layouts.app')

@section('title', 'Chỉnh sửa khoa')

@section('breadcrumb', 'Trang chủ / Khoa / Chỉnh sửa')

@section('content')
<div class="content-section">
    <div class="content-header">
        <h3>Chỉnh sửa khoa</h3>
        <a href="{{ route('faculties.index') }}" class="btn btn-back">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>
    <div class="form-container">
        <form action="{{ route('faculties.update', $faculty) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Tên đầy đủ <span class="required">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name', $faculty->name) }}" required>
                @error('name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="short_name">Tên viết tắt <span class="required">*</span></label>
                <input type="text" id="short_name" name="short_name" value="{{ old('short_name', $faculty->short_name) }}" required>
                @error('short_name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="description">Mô tả nhiệm vụ</label>
                <textarea id="description" name="description" rows="3">{{ old('description', $faculty->description) }}</textarea>
                @error('description')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Cập nhật</button>
                <a href="{{ route('faculties.index') }}" class="btn btn-secondary">Hủy</a>
            </div>
        </form>
    </div>
</div>
@endsection 
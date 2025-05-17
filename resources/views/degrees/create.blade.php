@extends('layouts.app')

@section('title', 'Thêm bằng cấp mới')

@section('breadcrumb', 'Trang chủ / Bằng cấp / Thêm mới')

@section('content')
<div class="content-section">
    <div class="content-header">
        <h3>Thêm bằng cấp mới</h3>
        <a href="{{ route('degrees.index') }}" class="btn btn-back">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>
    <div class="form-container">
        <form action="{{ route('degrees.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Tên đầy đủ <span class="required">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="short_name">Tên viết tắt <span class="required">*</span></label>
                <input type="text" id="short_name" name="short_name" value="{{ old('short_name') }}" required>
                @error('short_name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Lưu</button>
                <a href="{{ route('degrees.index') }}" class="btn btn-secondary">Hủy</a>
            </div>
        </form>
    </div>
</div>
@endsection 
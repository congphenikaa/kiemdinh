@extends('layouts.app')

@section('title', 'Chi tiết giáo viên')

@section('breadcrumb', 'Trang chủ / Giáo viên / Chi tiết')

@section('content')
<div class="content-section">
    <div class="content-header">
        <h3>Chi tiết giáo viên</h3>
        <div class="actions">
            <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Chỉnh sửa
            </a>
            <a href="{{ route('teachers.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>
    <div class="teacher-details">
        <div class="detail-card">
            <div class="detail-header">
                <h4>Thông tin cá nhân</h4>
            </div>
            <div class="detail-body">
                <div class="detail-row">
                    <div class="detail-label">Mã số:</div>
                    <div class="detail-value">{{ $teacher->code }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Họ tên:</div>
                    <div class="detail-value">{{ $teacher->name }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Ngày sinh:</div>
                    <div class="detail-value">{{ $teacher->dob->format('d/m/Y') }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Điện thoại:</div>
                    <div class="detail-value">{{ $teacher->phone ?: 'N/A' }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Email:</div>
                    <div class="detail-value">{{ $teacher->email ?: 'N/A' }}</div>
                </div>
            </div>
        </div>

        <div class="detail-card">
            <div class="detail-header">
                <h4>Thông tin chuyên môn</h4>
            </div>
            <div class="detail-body">
                <div class="detail-row">
                    <div class="detail-label">Khoa:</div>
                    <div class="detail-value">{{ $teacher->faculty->name }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Bằng cấp:</div>
                    <div class="detail-value">{{ $teacher->degree->name }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.teacher-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-top: 1.5rem;
}

.detail-card {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    overflow: hidden;
}

.detail-header {
    background: #f8f9fa;
    padding: 1rem;
    border-bottom: 1px solid #dee2e6;
}

.detail-header h4 {
    margin: 0;
    color: #333;
    font-size: 1.1rem;
}

.detail-body {
    padding: 1rem;
}

.detail-row {
    display: flex;
    margin-bottom: 0.75rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #eee;
}

.detail-row:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.detail-label {
    flex: 0 0 120px;
    font-weight: 500;
    color: #666;
}

.detail-value {
    flex: 1;
    color: #333;
}

.actions {
    display: flex;
    gap: 0.5rem;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 4px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s;
}

.btn-primary {
    background: #007bff;
    color: #fff;
}

.btn-primary:hover {
    background: #0056b3;
}

.btn-secondary {
    background: #6c757d;
    color: #fff;
}

.btn-secondary:hover {
    background: #545b62;
}
</style>
@endsection 
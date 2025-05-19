@extends('layouts.app')

@section('title', 'Quản lý giáo viên')

@section('breadcrumb', 'Trang chủ / Giáo viên')

@section('content')
<div class="content-section">
    <div class="content-header">
        <h3>Danh sách giáo viên</h3>
        <div class="actions">
            <a href="{{ route('teachers.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Thêm mới
            </a>
            <a href="{{ route('teachers.export') }}" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Xuất Excel
            </a>
            <div class="search-box">
                <input type="text" placeholder="Tìm kiếm...">
                <i class="fas fa-search"></i>
            </div>
        </div>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Mã số</th>
                    <th>Họ tên</th>
                    <th>Ngày sinh</th>
                    <th>Điện thoại</th>
                    <th>Email</th>
                    <th>Khoa</th>
                    <th>Bằng cấp</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($teachers as $index => $teacher)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $teacher->code }}</td>
                    <td>{{ $teacher->name }}</td>
                    <td>{{ $teacher->dob->format('d/m/Y') }}</td>
                    <td>{{ $teacher->phone ?: 'N/A' }}</td>
                    <td>{{ $teacher->email ?: 'N/A' }}</td>
                    <td>{{ $teacher->faculty->short_name }}</td>
                    <td>{{ $teacher->degree->short_name }}</td>
                    <td>
                        <a href="{{ route('teachers.show', $teacher) }}" class="btn btn-info" title="Xem chi tiết">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-edit" title="Chỉnh sửa">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn btn-delete" data-id="{{ $teacher->id }}" title="Xóa">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center">Không có giáo viên nào</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')
<script>
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-delete')) {
            const id = e.target.closest('.btn-delete').dataset.id;
            showConfirmModal(
                'Xóa giáo viên',
                'Bạn có chắc chắn muốn xóa giáo viên này?',
                function() {
                    const form = document.getElementById('delete-form');
                    form.action = `/teachers/${id}`;
                    form.submit();
                }
            );
        }
    });
</script>
@endpush 
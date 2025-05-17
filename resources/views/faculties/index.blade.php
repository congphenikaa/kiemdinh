@extends('layouts.app')

@section('title', 'Quản lý khoa')

@section('breadcrumb', 'Trang chủ / Khoa')

@section('content')
<div class="content-section">
    <div class="content-header">
        <h3>Danh sách khoa</h3>
        <div class="actions">
            <a href="{{ route('faculties.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Thêm mới
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
                    <th>Tên đầy đủ</th>
                    <th>Tên viết tắt</th>
                    <th>Mô tả</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($faculties as $index => $faculty)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $faculty->name }}</td>
                    <td>{{ $faculty->short_name }}</td>
                    <td>{{ $faculty->description ?: 'N/A' }}</td>
                    <td>
                        <a href="{{ route('faculties.edit', $faculty) }}" class="btn btn-edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn btn-delete" data-id="{{ $faculty->id }}">    
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Không có khoa nào</td>
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
                'Xóa khoa',
                'Bạn có chắc chắn muốn xóa khoa này?',
                function() {
                    const form = document.getElementById('delete-form');
                    form.action = `/faculties/${id}`;
                    form.submit();
                }
            );
        }
    });
</script>
@endpush 
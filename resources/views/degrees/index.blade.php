@extends('layouts.app')

@section('title', 'Quản lý bằng cấp')

@section('breadcrumb', 'Trang chủ / Bằng cấp')

@section('content')
<div class="content-section">
    <div class="content-header">
        <h3>Danh sách bằng cấp</h3>
        <div class="actions">
            <a href="{{ route('degrees.create') }}" class="btn btn-primary">
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
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($degrees as $index => $degree)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $degree->name }}</td>
                    <td>{{ $degree->short_name }}</td>
                    <td>
                        <a href="{{ route('degrees.edit', $degree) }}" class="btn btn-edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn btn-delete" data-id="{{ $degree->id }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">Không có bằng cấp nào</td>
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
                'Xóa bằng cấp',
                'Bạn có chắc chắn muốn xóa bằng cấp này?',
                function() {
                    const form = document.getElementById('delete-form');
                    form.action = `/degrees/${id}`;
                    form.submit();
                }
            );
        }
    });
</script>
@endpush 
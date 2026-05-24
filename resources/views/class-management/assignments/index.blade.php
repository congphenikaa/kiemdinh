@extends('layouts.app')

@section('title', 'Phân công giảng dạy')
@section('breadcrumb', 'Lớp học / Phân công')

@section('content')
<div class="space-y-6">
    <div class="page-toolbar">
        <div>
            <p class="text-sm text-slate-500">Danh sách</p>
            <h2 class="text-xl font-semibold text-slate-900">Phân công giảng dạy</h2>
        </div>
        <a href="{{ route('teaching-assignments.create') }}" class="btn-primary shrink-0">
            <i class="fas fa-plus"></i> Thêm phân công
        </a>
    </div>

    <x-filter-panel>
        <form method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div>
                <label class="form-label">Lớp học</label>
                <select name="class_id" class="form-input">
                    <option value="">Tất cả lớp</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->class_code }} — {{ $class->course->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Giảng viên</label>
                <select name="teacher_id" class="form-input">
                    <option value="">Tất cả giảng viên</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->name }} ({{ $teacher->code }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="btn-primary w-full">
                    <i class="fas fa-filter"></i> Lọc
                </button>
            </div>
        </form>
    </x-filter-panel>

    <x-page-card title="Kết quả phân công">
        <div class="overflow-x-auto">
            <table class="data-table min-w-full">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Lớp học</th>
                        <th>Giảng viên</th>
                        <th>Khoa</th>
                        <th>Học kỳ</th>
                        <th class="text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assignments as $index => $assignment)
                        <tr>
                            <td class="text-slate-400">{{ $index + $assignments->firstItem() }}</td>
                            <td>
                                <div class="font-medium text-slate-900">{{ $assignment->class->class_code }}</div>
                                <div class="text-xs text-slate-500">{{ $assignment->class->course->name }}</div>
                            </td>
                            <td>
                                <div class="font-medium text-slate-900">{{ $assignment->teacher->name }}</div>
                                <div class="text-xs text-slate-500">{{ $assignment->teacher->code }} · {{ $assignment->teacher->degree->short_name ?? '' }}</div>
                            </td>
                            <td>{{ $assignment->teacher->faculty->short_name ?? '—' }}</td>
                            <td>{{ $assignment->class->semester->name }} ({{ $assignment->class->semester->academicYear->name }})</td>
                            <td class="text-right">
                                <button type="button"
                                        class="btn-delete btn-icon text-red-600 hover:bg-red-50"
                                        data-id="{{ $assignment->id }}"
                                        title="Xóa">
                                    <i class="fas fa-trash-can"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-500">Chưa có phân công</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($assignments->hasPages())
            <div class="mt-4 border-t border-slate-100 pt-4">{{ $assignments->appends(request()->query())->links() }}</div>
        @endif
    </x-page-card>
</div>

<form id="delete-form" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.dataset.id;
                window.showConfirmModal('Xóa phân công', 'Bạn có chắc muốn xóa phân công này?', function () {
                    const form = document.getElementById('delete-form');
                    form.action = `/teaching-assignments/${id}`;
                    form.submit();
                });
            });
        });
    });
</script>
@endpush

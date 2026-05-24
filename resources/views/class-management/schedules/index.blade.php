@extends('layouts.app')

@section('title', 'Thời khóa biểu')
@section('breadcrumb', 'Lớp học / Thời khóa biểu')

@section('content')
<div class="space-y-6">
    <div class="page-toolbar">
        <div>
            <p class="text-sm text-slate-500">Danh sách</p>
            <h2 class="text-xl font-semibold text-slate-900">Lịch học</h2>
        </div>
        <a href="{{ route('schedules.create') }}" class="btn-primary shrink-0">
            <i class="fas fa-plus"></i> Thêm lịch học
        </a>
    </div>

    @if(session('warning'))
        <x-flash-alert type="error" :message="session('warning')" />
    @endif

    <x-filter-panel>
        <form method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-4">
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
                <label class="form-label">Ngày học</label>
                <input type="date" name="date" value="{{ request('date') }}" class="form-input">
            </div>
            <div>
                <label class="form-label">Trạng thái</label>
                <select name="is_taught" class="form-input">
                    <option value="">Tất cả</option>
                    <option value="1" {{ request('is_taught') === '1' ? 'selected' : '' }}>Đã dạy</option>
                    <option value="0" {{ request('is_taught') === '0' ? 'selected' : '' }}>Chưa dạy</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="btn-primary w-full"><i class="fas fa-filter"></i> Lọc</button>
            </div>
        </form>
    </x-filter-panel>

    <x-page-card title="Danh sách buổi học">
        <div class="overflow-x-auto">
            <table class="data-table min-w-full">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Lớp học</th>
                        <th>Ngày học</th>
                        <th>Thời gian</th>
                        <th>Buổi</th>
                        <th>Trạng thái</th>
                        <th class="text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schedules as $index => $schedule)
                        <tr>
                            <td class="text-slate-400">{{ $index + $schedules->firstItem() }}</td>
                            <td>
                                <div class="font-medium text-slate-900">{{ $schedule->class->class_code }}</div>
                                <div class="text-xs text-slate-500">{{ $schedule->class->course->name }}</div>
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($schedule->date)->format('d/m/Y') }}
                                <div class="text-xs text-slate-500">{{ ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'][$schedule->day_of_week] }}</div>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</td>
                            <td>{{ $schedule->session_number }}</td>
                            <td>
                                <x-badge :variant="$schedule->is_taught ? 'success' : 'warning'">
                                    {{ $schedule->is_taught ? 'Đã dạy' : 'Chưa dạy' }}
                                </x-badge>
                            </td>
                            <td class="text-right">
                                <div class="inline-flex gap-1">
                                    <button type="button"
                                            class="btn-icon text-emerald-600 hover:bg-emerald-50 js-toggle-taught"
                                            data-url="{{ route('schedules.toggle-taught', $schedule) }}"
                                            title="Đổi trạng thái dạy">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                    <a href="{{ route('schedules.edit', $schedule->id) }}" class="btn-icon text-primary-600 hover:bg-primary-50" title="Sửa">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <button type="button" onclick="confirmDelete({{ $schedule->id }})" class="btn-icon text-red-600 hover:bg-red-50" title="Xóa">
                                        <i class="fas fa-trash-can"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="py-12 text-center text-slate-500">Chưa có lịch học</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($schedules->hasPages())
            <div class="mt-4 border-t border-slate-100 pt-4">{{ $schedules->appends(request()->query())->links() }}</div>
        @endif
    </x-page-card>
</div>

<form id="delete-form" method="POST" class="hidden">@csrf @method('DELETE')</form>
@endsection

@push('scripts')
<script>
    function confirmDelete(id) {
        window.showConfirmModal('Xóa lịch học', 'Bạn có chắc muốn xóa buổi học này?', function () {
            const form = document.getElementById('delete-form');
            form.action = `/schedules/${id}`;
            form.submit();
        });
    }
    document.querySelectorAll('.js-toggle-taught').forEach((button) => {
        button.addEventListener('click', function () {
            const url = this.dataset.url;
            if (!url) {
                return;
            }

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': @json(csrf_token()),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            })
                .then(async (response) => {
                    const data = await response.json().catch(() => ({}));
                    if (!response.ok) {
                        throw new Error(data.message || 'Không thể cập nhật trạng thái.');
                    }
                    return data;
                })
                .then((data) => {
                    if (data.success) {
                        location.reload();
                    }
                })
                .catch((error) => {
                    alert(error.message || 'Có lỗi khi đổi trạng thái buổi học.');
                });
        });
    });
</script>
@endpush

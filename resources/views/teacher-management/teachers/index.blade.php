@extends('templates.index', [
    'entityName' => 'Giảng viên',
    'routePrefix' => 'teachers'
])

@section('table_headers')
    <th>STT</th>
    <th>Giảng viên</th>
    <th>Liên hệ</th>
    <th>Khoa / Bằng cấp</th>
    <th class="text-right">Thao tác</th>
@endsection

@section('table_rows')
    @forelse($teachers as $index => $teacher)
    <tr>
        <td class="text-slate-400">
            {{ ($teachers->currentPage() - 1) * $teachers->perPage() + $index + 1 }}
        </td>
        <td>
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-primary-50 text-sm font-semibold text-primary-700">
                    {{ mb_strtoupper(mb_substr($teacher->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <div class="font-medium text-slate-900">{{ $teacher->name }}</div>
                    <div class="mt-0.5 truncate text-xs text-slate-500">
                        {{ $teacher->code }} · {{ $teacher->email }}
                    </div>
                </div>
            </div>
        </td>
        <td>
            <div class="space-y-0.5 text-sm">
                <div>{{ $teacher->phone ?: '—' }}</div>
                <div class="text-slate-500">
                    {{ $teacher->gender === 'male' ? 'Nam' : ($teacher->gender === 'female' ? 'Nữ' : 'Khác') }}
                    · {{ \Carbon\Carbon::parse($teacher->dob)->format('d/m/Y') }}
                </div>
            </div>
        </td>
        <td>
            <div class="space-y-0.5 text-sm">
                <div>{{ $teacher->faculty->name ?? 'Chưa phân khoa' }}</div>
                <div class="text-slate-500">{{ $teacher->degree->name ?? 'Chưa cập nhật' }}</div>
            </div>
        </td>
        <td class="text-right">
            <div class="inline-flex items-center justify-end gap-1">
                <a href="{{ route('teachers.edit', $teacher) }}"
                   class="btn-icon text-primary-600 hover:bg-primary-50"
                   title="Chỉnh sửa">
                    <i class="fas fa-pen"></i>
                </a>
                <button type="button"
                        class="btn-delete btn-icon text-red-600 hover:bg-red-50"
                        data-id="{{ $teacher->id }}"
                        title="Xóa">
                    <i class="fas fa-trash-can"></i>
                </button>
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="5" class="py-16 text-center">
            <i class="fas fa-chalkboard-user mb-3 text-3xl text-slate-300"></i>
            <p class="font-medium text-slate-600">Chưa có giảng viên</p>
            <p class="mt-1 text-sm text-slate-400">Thêm giảng viên đầu tiên cho hệ thống</p>
            <a href="{{ route('teachers.create') }}" class="btn-primary mt-4 inline-flex">
                <i class="fas fa-plus"></i>
                Thêm giảng viên
            </a>
        </td>
    </tr>
    @endforelse
@endsection

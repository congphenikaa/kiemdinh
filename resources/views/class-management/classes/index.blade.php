@extends('templates.index', [
    'entityName' => 'Lớp học',
    'routePrefix' => 'classes'
])

@section('table_headers')
    <th>STT</th>
    <th>Mã lớp</th>
    <th>Học phần</th>
    <th>Kỳ học</th>
    <th>Bắt đầu</th>
    <th>Kết thúc</th>
    <th>Trạng thái</th>
    <th class="text-right">Thao tác</th>
@endsection

@section('table_rows')
    @forelse($classes as $index => $clazz)
        <tr>
            <td class="text-slate-400">{{ ($classes->currentPage() - 1) * $classes->perPage() + $index + 1 }}</td>
            <td class="font-mono text-xs font-medium text-slate-900">{{ $clazz->class_code }}</td>
            <td>{{ $clazz->course->name ?? '—' }}</td>
            <td>{{ $clazz->semester->name ?? '—' }}</td>
            <td>{{ \Carbon\Carbon::parse($clazz->start_date)->format('d/m/Y') }}</td>
            <td>{{ \Carbon\Carbon::parse($clazz->end_date)->format('d/m/Y') }}</td>
            <td>
                <x-badge :variant="$clazz->status == 'open' ? 'success' : 'neutral'">
                    {{ $clazz->status == 'open' ? 'Đang mở' : 'Đã đóng' }}
                </x-badge>
            </td>
            <td class="text-right">
                <x-crud-actions :edit-route="route('classes.edit', $clazz)" :delete-id="$clazz->id" />
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="8" class="py-16 text-center text-slate-500">Chưa có lớp học</td>
        </tr>
    @endforelse
@endsection

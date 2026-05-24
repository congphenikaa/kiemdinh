@extends('templates.index', [
    'entityName' => 'Kỳ học',
    'routePrefix' => 'semesters'
])

@section('table_headers')
    <th>STT</th>
    <th>Kỳ học</th>
    <th>Năm học</th>
    <th>Bắt đầu</th>
    <th>Kết thúc</th>
    <th>Loại</th>
    <th>Trạng thái</th>
    <th class="text-right">Thao tác</th>
@endsection

@section('table_rows')
    @forelse($semesters as $index => $semester)
        <tr>
            <td class="text-slate-400">{{ ($semesters->currentPage() - 1) * $semesters->perPage() + $index + 1 }}</td>
            <td class="font-medium text-slate-900">{{ $semester->name }}</td>
            <td>{{ $semester->academicYear->name ?? '—' }}</td>
            <td>{{ $semester->start_date->format('d/m/Y') }}</td>
            <td>{{ $semester->end_date->format('d/m/Y') }}</td>
            <td>{{ $semester->type == 1 ? 'Học kỳ I' : 'Học kỳ II' }}</td>
            <td>
                <form action="{{ route('semesters.toggleActive', $semester) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="focus:outline-none">
                        <x-badge :variant="$semester->is_active ? 'success' : 'neutral'">
                            {{ $semester->is_active ? 'Đang hoạt động' : 'Không hoạt động' }}
                        </x-badge>
                    </button>
                </form>
            </td>
            <td class="text-right">
                <x-crud-actions :edit-route="route('semesters.edit', $semester)" :delete-id="$semester->id" />
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="8" class="py-16 text-center text-slate-500">Chưa có kỳ học</td>
        </tr>
    @endforelse
@endsection

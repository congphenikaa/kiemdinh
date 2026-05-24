@extends('templates.index', [
    'entityName' => 'Năm học',
    'routePrefix' => 'academic-years'
])

@section('table_headers')
    <th>STT</th>
    <th>Năm học</th>
    <th>Bắt đầu</th>
    <th>Kết thúc</th>
    <th>Trạng thái</th>
    <th class="text-right">Thao tác</th>
@endsection

@section('table_rows')
    @forelse($academicYears as $index => $year)
        <tr>
            <td class="text-slate-400">{{ ($academicYears->currentPage() - 1) * $academicYears->perPage() + $index + 1 }}</td>
            <td>
                <div class="font-medium text-slate-900">{{ $year->name }}</div>
                <div class="text-xs text-slate-500">{{ $year->semesters_count ?? 0 }} học kỳ</div>
            </td>
            <td>{{ $year->start_date->format('d/m/Y') }}</td>
            <td>{{ $year->end_date->format('d/m/Y') }}</td>
            <td>
                <x-badge :variant="$year->is_active ? 'success' : 'neutral'">
                    {{ $year->is_active ? 'Đang hoạt động' : 'Không hoạt động' }}
                </x-badge>
            </td>
            <td class="text-right">
                <x-crud-actions :edit-route="route('academic-years.edit', $year)" :delete-id="$year->id" />
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="6" class="py-16 text-center text-slate-500">Chưa có năm học</td>
        </tr>
    @endforelse
@endsection

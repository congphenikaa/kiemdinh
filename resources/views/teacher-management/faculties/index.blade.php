@extends('templates.index', [
    'entityName' => 'Khoa',
    'routePrefix' => 'faculties'
])

@section('table_headers')
    <th>STT</th>
    <th>Tên khoa</th>
    <th>Viết tắt</th>
    <th>Thống kê</th>
    <th class="text-right">Thao tác</th>
@endsection

@section('table_rows')
    @forelse($faculties as $index => $faculty)
        <tr>
            <td class="text-slate-400">{{ ($faculties->currentPage() - 1) * $faculties->perPage() + $index + 1 }}</td>
            <td>
                <div class="font-medium text-slate-900">{{ $faculty->name }}</div>
                @if($faculty->description)
                    <div class="mt-0.5 line-clamp-1 text-xs text-slate-500">{{ $faculty->description }}</div>
                @endif
            </td>
            <td><x-badge variant="neutral">{{ $faculty->short_name }}</x-badge></td>
            <td class="text-sm text-slate-600">{{ $faculty->teachers_count }} GV · {{ $faculty->courses_count }} HP</td>
            <td class="text-right">
                <x-crud-actions :edit-route="route('faculties.edit', $faculty)" :delete-id="$faculty->id" />
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="py-16 text-center">
                <p class="font-medium text-slate-600">Chưa có khoa</p>
                <a href="{{ route('faculties.create') }}" class="btn-primary mt-4 inline-flex">
                    <i class="fas fa-plus"></i> Thêm khoa
                </a>
            </td>
        </tr>
    @endforelse
@endsection

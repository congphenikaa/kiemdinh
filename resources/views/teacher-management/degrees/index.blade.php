@extends('templates.index', [
    'entityName' => 'Bằng cấp',
    'routePrefix' => 'degrees'
])

@section('table_headers')
    <th>STT</th>
    <th>Tên bằng cấp</th>
    <th>Viết tắt</th>
    <th>Hệ số lương</th>
    <th class="text-right">Thao tác</th>
@endsection

@section('table_rows')
    @forelse($degrees as $index => $degree)
        <tr>
            <td class="text-slate-400">{{ ($degrees->currentPage() - 1) * $degrees->perPage() + $index + 1 }}</td>
            <td>
                <div class="font-medium text-slate-900">{{ $degree->name }}</div>
                <div class="text-xs text-slate-500">{{ $degree->teachers_count ?? 0 }} giảng viên</div>
            </td>
            <td><x-badge variant="neutral">{{ $degree->short_name }}</x-badge></td>
            <td class="font-mono">{{ number_format($degree->salary_coefficient, 2, '.', '') }}</td>
            <td class="text-right">
                <x-crud-actions :edit-route="route('degrees.edit', $degree)" :delete-id="$degree->id" />
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="py-16 text-center">
                <i class="fas fa-graduation-cap mb-3 text-3xl text-slate-300"></i>
                <p class="font-medium text-slate-600">Chưa có bằng cấp</p>
                <a href="{{ route('degrees.create') }}" class="btn-primary mt-4 inline-flex">
                    <i class="fas fa-plus"></i> Thêm bằng cấp
                </a>
            </td>
        </tr>
    @endforelse
@endsection

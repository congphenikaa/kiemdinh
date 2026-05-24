@extends('templates.index', [
    'entityName' => 'Học phần',
    'routePrefix' => 'courses'
])

@section('table_headers')
    <th>STT</th>
    <th>Mã HP</th>
    <th>Tên học phần</th>
    <th>Khoa</th>
    <th>Tín chỉ</th>
    <th>Buổi</th>
    <th class="text-right">Thao tác</th>
@endsection

@section('table_rows')
    @forelse($courses as $index => $course)
        <tr>
            <td class="text-slate-400">{{ ($courses->currentPage() - 1) * $courses->perPage() + $index + 1 }}</td>
            <td class="font-mono text-xs font-medium text-primary-700">{{ $course->course_code }}</td>
            <td>
                <div class="font-medium text-slate-900">{{ $course->name }}</div>
                @if($course->description)
                    <div class="mt-0.5 line-clamp-1 text-xs text-slate-500">{{ $course->description }}</div>
                @endif
            </td>
            <td>{{ $course->faculty->name ?? '—' }}</td>
            <td>{{ $course->credit_hours }}</td>
            <td>{{ $course->total_sessions }}</td>
            <td class="text-right">
                <x-crud-actions :edit-route="route('courses.edit', $course)" :delete-id="$course->id" />
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="7" class="py-16 text-center text-slate-500">Chưa có học phần</td>
        </tr>
    @endforelse
@endsection

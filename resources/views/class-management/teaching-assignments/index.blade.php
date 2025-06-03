@extends('templates.index', [
    'entityName' => 'Phân công giảng dạy',
    'routePrefix' => 'teaching-assignments'
])

@section('table_headers')
    <th>STT</th>
    <th>Lớp học</th>
    <th>Giáo viên</th>
    <th>GV chủ nhiệm</th>
    <th>Số buổi phân công</th>
    <th>Hành động</th>
@endsection

@section('table_rows')
    @foreach($assignments as $index => $assignment)
        <tr>
            <td>{{ $index + $assignments->firstItem() }}</td>
            <td>{{ $assignment->class->name }}</td>
            <td>{{ $assignment->teacher->name }}</td>
            <td>
                @if($assignment->main_teacher)
                    <span class="badge bg-success">Có</span>
                @else
                    <span class="badge bg-secondary">Không</span>
                @endif
            </td>
            <td>{{ $assignment->assigned_sessions }}</td>
            <td>
                <a href="{{ route('class-management.assignments.edit', $assignment->id) }}" 
                   class="btn btn-sm btn-warning">
                    <i class="fas fa-edit"></i>
                </a>
                <form action="{{ route('class-management.assignments.destroy', $assignment->id) }}" 
                      method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" 
                            onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </td>
        </tr>
    @endforeach
@endsection
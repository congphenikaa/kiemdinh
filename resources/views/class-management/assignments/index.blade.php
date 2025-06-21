@extends('templates.index', [
    'entityName' => 'Phân công giảng dạy',
    'routePrefix' => 'teaching-assignments'
])

@section('table_headers')
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STT</th>
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã lớp</th>
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khóa học</th>
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Học kỳ</th>
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giảng viên chính</th>
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số giảng viên</th>
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái phân công</th>
    <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
@endsection

@section('table_rows')
    @forelse($classes as $index => $clazz)
    <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-gray-100 transition-colors">
        <td class="px-4 py-4 text-sm text-gray-500">
            {{ ($classes->currentPage() - 1) * $classes->perPage() + $index + 1 }}
        </td>
        <td class="px-4 py-4 font-medium text-sm text-gray-900">{{ $clazz->class_code }}</td>
        <td class="px-4 py-4 text-sm text-gray-700">
            {{ $clazz->course->name ?? 'Chưa có' }}
        </td>
        <td class="px-4 py-4 text-sm text-gray-700">
            {{ $clazz->semester->name ?? 'Chưa có' }}
        </td>
        <td class="px-4 py-4 text-sm text-gray-700">
            {{ $clazz->mainTeacher->first()->name ?? 'Chưa phân công' }}
        </td>
        <td class="px-4 py-4 text-sm text-gray-700">
            {{ $clazz->teachingAssignments->count() }}
        </td>
        <td class="px-4 py-4 text-sm">
            @if($clazz->mainTeacher->count() > 0)
                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                    <i class="fas fa-check-circle mr-1"></i> Đã phân công
                </span>
            @else
                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">
                    <i class="fas fa-exclamation-circle mr-1"></i> Chưa phân công
                </span>
            @endif
        </td>
        <td class="px-4 py-4 text-right text-sm font-medium space-x-2">
            <a href="{{ route('teaching-assignments.create', $clazz) }}" 
               class="inline-flex items-center px-3 py-1 border border-blue-300 rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors"
               title="Thêm phân công">
                <i class="fas fa-plus mr-1"></i> Thêm
            </a>
            @if($clazz->teachingAssignments->count() > 0)
                <a href="{{ route('teaching-assignments.edit', $clazz) }}" 
                   class="inline-flex items-center px-3 py-1 border border-yellow-300 rounded-md text-yellow-700 bg-yellow-50 hover:bg-yellow-100 transition-colors"
                   title="Chỉnh sửa phân công">
                    <i class="fas fa-edit mr-1"></i> Sửa
                </a>
                <form action="{{ route('teaching-assignments.destroy', $clazz) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-3 py-1 border border-red-300 rounded-md text-red-700 bg-red-50 hover:bg-red-100 transition-colors" 
                            title="Xóa phân công">
                        <i class="fas fa-trash-alt mr-1"></i> Xóa
                    </button>
                </form>
            @endif
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="8" class="px-6 py-12 text-center">
            <div class="flex flex-col items-center justify-center">
                <i class="fas fa-chalkboard-teacher text-4xl text-gray-300 mb-4"></i>
                <h4 class="text-lg font-medium text-gray-500">Không có lớp học nào đang mở</h4>
                <p class="text-gray-400 mt-1">Hiện tại không có lớp học nào ở trạng thái "Đang mở"</p>
            </div>
        </td>
    </tr>
    @endforelse
@endsection

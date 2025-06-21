@extends('templates.index', [
    'entityName' => 'Học kỳ',
    'routePrefix' => 'semesters'
])

@section('table_headers')
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STT</th>
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên học kỳ</th>
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Năm học</th>
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày bắt đầu</th>
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày kết thúc</th>
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loại</th>
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
    <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
@endsection

@section('table_rows')
    @forelse($semesters as $index => $semester)
    <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-gray-100 transition-colors">
        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
            {{ ($semesters->currentPage() - 1) * $semesters->perPage() + $index + 1 }}
        </td>
        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
            {{ $semester->name }}
        </td>
        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">
            {{ $semester->academicYear->name ?? '---' }}
        </td>
        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
            {{ $semester->start_date->format('d/m/Y') }}
        </td>
        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
            {{ $semester->end_date->format('d/m/Y') }}
        </td>
        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
            {{ $semester->type == 1 ? 'Học kỳ I' : 'Học kỳ II' }}
        </td>
        <td class="px-4 py-4 whitespace-nowrap text-sm">
            <form action="{{ route('semesters.toggleActive', $semester) }}" method="POST" class="inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="focus:outline-none">
                    <span class="px-2 py-1 rounded-full text-xs font-medium cursor-pointer 
                        {{ $semester->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $semester->is_active ? 'Đang hoạt động' : 'Không hoạt động' }}
                    </span>
                </button>
            </form>
        </td>
        <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
            <a href="{{ route('semesters.edit', $semester) }}" 
               class="inline-flex items-center px-3 py-1 border border-blue-300 rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors"
               title="Chỉnh sửa">
                <i class="fas fa-edit mr-1"></i> Sửa
            </a>
            <form action="{{ route('semesters.destroy', $semester) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="inline-flex items-center px-3 py-1 border border-red-300 rounded-md text-red-700 bg-red-50 hover:bg-red-100 transition-colors"
                        onclick="return confirm('Bạn có chắc chắn muốn xóa học kỳ này?')"
                        title="Xóa">
                    <i class="fas fa-trash-alt mr-1"></i> Xóa
                </button>
            </form>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="8" class="px-6 py-12 text-center">
            <div class="flex flex-col items-center justify-center">
                <i class="fas fa-book-open text-4xl text-gray-300 mb-4"></i>
                <h4 class="text-lg font-medium text-gray-500">Không có học kỳ nào</h4>
                <p class="text-gray-400 mt-1">Nhấn "Thêm mới" để tạo học kỳ đầu tiên</p>
                <a href="{{ route('semesters.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i> Thêm học kỳ
                </a>
            </div>
        </td>
    </tr>
    @endforelse
@endsection
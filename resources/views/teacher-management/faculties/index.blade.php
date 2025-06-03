@extends('templates.index', [
    'entityName' => 'Khoa',
    'routePrefix' => 'faculties'
])

@section('table_headers')
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STT</th>
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên khoa</th>
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên viết tắt</th>
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thống kê</th>
    <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
@endsection

@section('table_rows')
    @forelse($faculties as $index => $faculty)
    <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-gray-100 transition-colors">
        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
            {{ ($faculties->currentPage() - 1) * $faculties->perPage() + $index + 1 }}
        </td>
        <td class="px-4 py-4 whitespace-nowrap">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                    <i class="fas fa-university text-green-600"></i>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-900 hover:text-blue-600 transition-colors">
                        {{ $faculty->name }}
                    </div>
                    <div class="text-gray-500 text-xs mt-1 line-clamp-1">
                        {{ $faculty->description ?? 'Không có mô tả' }}
                    </div>
                </div>
            </div>
        </td>
        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
            <span class="px-2 py-1 bg-gray-100 rounded-md">{{ $faculty->short_name }}</span>
        </td>
        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
            <div class="flex space-x-4">
                <div class="flex items-center">
                    <i class="fas fa-chalkboard-teacher text-blue-400 mr-1"></i>
                    <span>{{ $faculty->teachers_count }} GV</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-book-open text-purple-400 mr-1"></i>
                    <span>{{ $faculty->courses_count }} HP</span>
                </div>
            </div>
        </td>
        <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
            <a href="{{ route('faculties.edit', $faculty) }}" 
               class="inline-flex items-center px-3 py-1 border border-blue-300 rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors"
               title="Chỉnh sửa">
                <i class="fas fa-edit mr-1"></i> Sửa
            </a>
            <button class="btn-delete inline-flex items-center px-3 py-1 border border-red-300 rounded-md text-red-700 bg-red-50 hover:bg-red-100 transition-colors" 
                    data-id="{{ $faculty->id }}"
                    title="Xóa">
                <i class="fas fa-trash-alt mr-1"></i> Xóa
            </button>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="5" class="px-6 py-12 text-center">
            <div class="flex flex-col items-center justify-center">
                <i class="fas fa-university text-4xl text-gray-300 mb-4"></i>
                <h4 class="text-lg font-medium text-gray-500">Không có khoa nào</h4>
                <p class="text-gray-400 mt-1">Nhấn "Thêm mới" để tạo khoa đầu tiên</p>
                <a href="{{ route('faculties.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i> Thêm khoa
                </a>
            </div>
        </td>
    </tr>
    @endforelse
@endsection
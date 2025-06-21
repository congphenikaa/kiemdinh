@extends('templates.index', [
    'entityName' => 'Năm học',
    'routePrefix' => 'academic-years'
])

@section('table_headers')
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STT</th>
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên năm học</th>
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày bắt đầu</th>
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày kết thúc</th>
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
    <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
@endsection

@section('table_rows')
    @forelse($academicYears as $index => $year)
    <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-gray-100 transition-colors">
        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
            {{ ($academicYears->currentPage() - 1) * $academicYears->perPage() + $index + 1 }}
        </td>
        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                    <i class="fas fa-calendar-alt text-blue-600"></i>
                </div>
                <div>
                    {{ $year->name }}
                    <div class="text-gray-500 text-xs mt-1">
                        {{ $year->semesters_count ?? 0 }} học kỳ
                    </div>
                </div>
            </div>
        </td>
        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
            {{ $year->start_date->format('d/m/Y') }}
        </td>
        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
            {{ $year->end_date->format('d/m/Y') }}
        </td>
        <td class="px-4 py-4 whitespace-nowrap text-sm">
            @if($year->is_active)
                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                    <i class="fas fa-check-circle mr-1"></i> Đang hoạt động
                </span>
            @else
                <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-medium">
                    <i class="fas fa-times-circle mr-1"></i> Không hoạt động
                </span>
            @endif
        </td>
        <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
            <a href="{{ route('academic-years.edit', $year) }}" 
               class="inline-flex items-center px-3 py-1 border border-blue-300 rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors"
               title="Chỉnh sửa">
                <i class="fas fa-edit mr-1"></i> Sửa
            </a>
            <button class="btn-delete inline-flex items-center px-3 py-1 border border-red-300 rounded-md text-red-700 bg-red-50 hover:bg-red-100 transition-colors" 
                    data-id="{{ $year->id }}"
                    title="Xóa">
                <i class="fas fa-trash-alt mr-1"></i> Xóa
            </button>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="6" class="px-6 py-12 text-center">
            <div class="flex flex-col items-center justify-center">
                <i class="fas fa-calendar-alt text-4xl text-gray-300 mb-4"></i>
                <h4 class="text-lg font-medium text-gray-500">Không có năm học nào</h4>
                <p class="text-gray-400 mt-1">Nhấn "Thêm mới" để tạo năm học đầu tiên</p>
                <a href="{{ route('academic-years.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i> Thêm năm học
                </a>
            </div>
        </td>
    </tr>
    @endforelse
@endsection
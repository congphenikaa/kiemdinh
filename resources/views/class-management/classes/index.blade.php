@extends('templates.index', [
    'entityName' => 'Lớp học',
    'routePrefix' => 'classes'
])

@section('table_headers')
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STT</th>
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã lớp</th>
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khóa học</th>
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Học kỳ</th>
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày bắt đầu</th>
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày kết thúc</th>
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
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
        <td class="px-4 py-4 text-sm text-gray-500">
            {{ \Carbon\Carbon::parse($clazz->start_date)->format('d/m/Y') }}
        </td>
        <td class="px-4 py-4 text-sm text-gray-500">
            {{ \Carbon\Carbon::parse($clazz->end_date)->format('d/m/Y') }}
        </td>
        <td class="px-4 py-4 text-sm">
            @if($clazz->status)
                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                    <i class="fas fa-check-circle mr-1"></i> Đang mở
                </span>
            @else
                <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-medium">
                    <i class="fas fa-times-circle mr-1"></i> Đã đóng
                </span>
            @endif
        </td>
        <td class="px-4 py-4 text-right text-sm font-medium space-x-2">
            <a href="{{ route('classes.edit', $clazz) }}" 
               class="inline-flex items-center px-3 py-1 border border-blue-300 rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors"
               title="Chỉnh sửa">
                <i class="fas fa-edit mr-1"></i> Sửa
            </a>
            <button class="btn-delete inline-flex items-center px-3 py-1 border border-red-300 rounded-md text-red-700 bg-red-50 hover:bg-red-100 transition-colors" 
                    data-id="{{ $clazz->id }}"
                    title="Xóa">
                <i class="fas fa-trash-alt mr-1"></i> Xóa
            </button>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="8" class="px-6 py-12 text-center">
            <div class="flex flex-col items-center justify-center">
                <i class="fas fa-school text-4xl text-gray-300 mb-4"></i>
                <h4 class="text-lg font-medium text-gray-500">Không có lớp học nào</h4>
                <p class="text-gray-400 mt-1">Nhấn "Thêm mới" để tạo lớp học đầu tiên</p>
                <a href="{{ route('classes.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i> Thêm lớp học
                </a>
            </div>
        </td>
    </tr>
    @endforelse
@endsection

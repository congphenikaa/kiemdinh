@extends('templates.index', [
    'entityName' => 'Hệ số sĩ số lớp',
    'routePrefix' => 'class-size-coefficients'
])

@section('table_headers')
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STT</th>
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sĩ số tối thiểu</th>
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sĩ số tối đa</th>
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hệ số</th>
    <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
@endsection

@section('table_rows')
    @forelse($coefficients as $index => $coefficient)
    <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-gray-100 transition-colors">
        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
            {{ $index + 1 }}
        </td>
        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
            {{ $coefficient->min_students }}
        </td>
        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
            {{ $coefficient->max_students }}
        </td>
        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
            <span class="font-mono">{{ number_format($coefficient->coefficient, 2, '.', '') }}</span>
        </td>
        <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
            <a href="{{ route('class-size-coefficients.edit', $coefficient) }}" 
               class="inline-flex items-center px-3 py-1 border border-blue-300 rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors"
               title="Chỉnh sửa">
                <i class="fas fa-edit mr-1"></i> Sửa
            </a>
            <button class="btn-delete inline-flex items-center px-3 py-1 border border-red-300 rounded-md text-red-700 bg-red-50 hover:bg-red-100 transition-colors" 
                    data-id="{{ $coefficient->id }}"
                    title="Xóa">
                <i class="fas fa-trash-alt mr-1"></i> Xóa
            </button>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="5" class="px-6 py-12 text-center">
            <div class="flex flex-col items-center justify-center">
                <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                <h4 class="text-lg font-medium text-gray-500">Không có hệ số sĩ số nào</h4>
                <p class="text-gray-400 mt-1">Nhấn "Thêm mới" để tạo hệ số sĩ số đầu tiên</p>
                <a href="{{ route('class-size-coefficients.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i> Thêm hệ số sĩ số
                </a>
            </div>
        </td>
    </tr>
    @endforelse
@endsection

@extends('templates.index', [
    'entityName' => 'Môn học',
    'routePrefix' => 'courses'
])

@section('table_headers')
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STT</th>
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã môn</th>
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên môn học</th>
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khoa/Viện</th>
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số tín chỉ</th>
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số buổi</th>
    <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
@endsection

@section('table_rows')
    @forelse($courses as $index => $course)
    <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-gray-100 transition-colors">
        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
            {{ ($courses->currentPage() - 1) * $courses->perPage() + $index + 1 }}
        </td>
        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-md font-mono">
                {{ $course->course_code }}
            </span>
        </td>
        <td class="px-4 py-4 whitespace-nowrap">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                    <i class="fas fa-book text-indigo-600"></i>
                </div>
                <div>
                    <div class="font-medium text-gray-900">{{ $course->name }}</div>
                    @if($course->description)
                    <div class="text-gray-500 text-xs mt-1 truncate max-w-xs">
                        {{ $course->description }}
                    </div>
                    @endif
                </div>
            </div>
        </td>
        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
            @if($course->faculty)
                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-md text-xs">
                    {{ $course->faculty->name }}
                </span>
            @else
                <span class="text-gray-400">Chưa phân khoa</span>
            @endif
        </td>
        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full">
                {{ $course->credit_hours }} tín chỉ
            </span>
        </td>
        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
            {{ $course->total_sessions }} buổi
        </td>
        <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
            <a href="{{ route('courses.edit', $course) }}" 
               class="inline-flex items-center px-3 py-1 border border-blue-300 rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors"
               title="Chỉnh sửa">
                <i class="fas fa-edit mr-1"></i> Sửa
            </a>
            <button class="btn-delete inline-flex items-center px-3 py-1 border border-red-300 rounded-md text-red-700 bg-red-50 hover:bg-red-100 transition-colors" 
                    data-id="{{ $course->id }}"
                    title="Xóa">
                <i class="fas fa-trash-alt mr-1"></i> Xóa
            </button>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="7" class="px-6 py-12 text-center">
            <div class="flex flex-col items-center justify-center">
                <i class="fas fa-book text-4xl text-gray-300 mb-4"></i>
                <h4 class="text-lg font-medium text-gray-500">Không có môn học nào</h4>
                <p class="text-gray-400 mt-1">Nhấn "Thêm mới" để tạo môn học đầu tiên</p>
                <a href="{{ route('courses.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i> Thêm môn học
                </a>
            </div>
        </td>
    </tr>
    @endforelse
@endsection
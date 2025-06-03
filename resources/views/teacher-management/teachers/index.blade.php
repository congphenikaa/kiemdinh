@extends('templates.index', [
    'entityName' => 'Giảng viên',
    'routePrefix' => 'teachers'
])

@section('table_headers')
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STT</th>
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giảng viên</th>
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thông tin</th>
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khoa/Bằng cấp</th>
    <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
@endsection

@section('table_rows')
    @forelse($teachers as $index => $teacher)
    <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-gray-100 transition-colors">
        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
            {{ ($teachers->currentPage() - 1) * $teachers->perPage() + $index + 1 }}
        </td>
        <td class="px-4 py-4 whitespace-nowrap">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                    <i class="fas fa-chalkboard-teacher text-blue-600"></i>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-900 hover:text-blue-600 transition-colors">
                        {{ $teacher->name }}
                    </div>
                    <div class="text-gray-500 text-xs mt-1">
                        <span class="mr-2"><i class="fas fa-id-card text-gray-400 mr-1"></i> {{ $teacher->code }}</span>
                        <span><i class="fas fa-envelope text-gray-400 mr-1"></i> {{ $teacher->email }}</span>
                    </div>
                </div>
            </div>
        </td>
        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
            <div class="space-y-1">
                <div class="flex items-center">
                    <i class="fas fa-phone text-green-500 mr-2 w-4"></i>
                    <span>{{ $teacher->phone }}</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-{{ $teacher->gender === 'male' ? 'male' : ($teacher->gender === 'female' ? 'female' : 'genderless') }} text-purple-500 mr-2 w-4"></i>
                    <span>{{ $teacher->gender === 'male' ? 'Nam' : ($teacher->gender === 'female' ? 'Nữ' : 'Khác') }}</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-calendar-day text-yellow-500 mr-2 w-4"></i>
                    <span>{{ \Carbon\Carbon::parse($teacher->dob)->format('d/m/Y') }} ({{ \Carbon\Carbon::parse($teacher->dob)->age }} tuổi)</span>
                </div>
            </div>
        </td>
        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
            <div class="space-y-2">
                <div class="flex items-start">
                    <i class="fas fa-university text-blue-400 mr-2 mt-1 w-4"></i>
                    <span>{{ $teacher->faculty->name ?? 'Chưa phân khoa' }}</span>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-graduation-cap text-purple-400 mr-2 mt-1 w-4"></i>
                    <span>{{ $teacher->degree->name ?? 'Chưa cập nhật' }}</span>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-calendar-check text-green-400 mr-2 mt-1 w-4"></i>
                    <span>NV từ {{ \Carbon\Carbon::parse($teacher->start_date)->format('d/m/Y') }}</span>
                </div>
            </div>
        </td>
        <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
            <a href="{{ route('teachers.edit', $teacher) }}" 
               class="inline-flex items-center px-3 py-1 border border-blue-300 rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors"
               title="Chỉnh sửa">
                <i class="fas fa-edit mr-1"></i> Sửa
            </a>
            <button class="btn-delete inline-flex items-center px-3 py-1 border border-red-300 rounded-md text-red-700 bg-red-50 hover:bg-red-100 transition-colors" 
                    data-id="{{ $teacher->id }}"
                    title="Xóa">
                <i class="fas fa-trash-alt mr-1"></i> Xóa
            </button>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="5" class="px-6 py-12 text-center">
            <div class="flex flex-col items-center justify-center">
                <i class="fas fa-chalkboard-teacher text-4xl text-gray-300 mb-4"></i>
                <h4 class="text-lg font-medium text-gray-500">Không có giảng viên nào</h4>
                <p class="text-gray-400 mt-1">Nhấn "Thêm mới" để tạo giảng viên đầu tiên</p>
                <a href="{{ route('teachers.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i> Thêm giảng viên
                </a>
            </div>
        </td>
    </tr>
    @endforelse
@endsection
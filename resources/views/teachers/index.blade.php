@extends('templates.index', [
    'entityName' => 'Giáo viên',
    'routePrefix' => 'teachers',
])

@section('table_headers')
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STT</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã số</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Họ tên</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày sinh</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Điện thoại</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khoa</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bằng cấp</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
@endsection

@section('table_rows')
    @forelse($teachers as $index => $teacher)
    <tr>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $teacher->code }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $teacher->name }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $teacher->dob->format('d/m/Y') }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $teacher->phone ?: 'N/A' }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $teacher->email ?: 'N/A' }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $teacher->faculty->short_name }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $teacher->degree->short_name }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
            <div class="flex space-x-2">
                <a href="{{ route('teachers.show', $teacher) }}" class="text-blue-400 hover:text-blue-600" title="Xem chi tiết">
                    <i class="fas fa-eye"></i>
                </a>
                <a href="{{ route('teachers.edit', $teacher) }}" class="text-yellow-500 hover:text-yellow-700" title="Chỉnh sửa">
                    <i class="fas fa-edit"></i>
                </a>
                <button class="text-red-600 hover:text-red-900 btn-delete" data-id="{{ $teacher->id }}" title="Xóa">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="9" class="px-6 py-4 text-center text-sm text-gray-500">Không có giáo viên nào</td>
    </tr>
    @endforelse
@endsection
@extends('templates.index', [
    'entityName' => 'Khoa',
    'routePrefix' => 'faculties'
])

@section('table_headers')
    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STT</th>
    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên đầy đủ</th>
    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên viết tắt</th>
    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mô tả</th>
    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
@endsection

@section('table_rows')
    @forelse($faculties as $index => $faculty)
    <tr>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
            {{ $index + 1 }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
            {{ $faculty->name }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
            {{ $faculty->short_name }}
        </td>
        <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
            {{ $faculty->description ?: 'N/A' }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
            <a href="{{ route('faculties.edit', $faculty) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                <i class="fas fa-edit"></i>
            </a>
            <button class="btn-delete text-red-600 hover:text-red-900" data-id="{{ $faculty->id }}">
                <i class="fas fa-trash-alt"></i>
            </button>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
            Không có khoa nào
        </td>
    </tr>
    @endforelse
@endsection
@extends('templates.index', [
    'entityName' => 'Bằng cấp',
    'routePrefix' => 'degrees'
])

@section('table_headers')
    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STT</th>
    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên đầy đủ</th>
    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên viết tắt</th>
    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
@endsection

@section('table_rows')
    @forelse($degrees as $index => $degree)
    <tr>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
            {{ $index + 1 }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
            {{ $degree->name }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
            {{ $degree->short_name }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
            <a href="{{ route('degrees.edit', $degree) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                <i class="fas fa-edit"></i>
            </a>
            <button class="btn-delete text-red-600 hover:text-red-900" data-id="{{ $degree->id }}">
                <i class="fas fa-trash-alt"></i>
            </button>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
            Không có bằng cấp nào
        </td>
    </tr>
    @endforelse
@endsection
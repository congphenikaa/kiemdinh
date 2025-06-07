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
        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
            {{ $coefficient->min_students }}
        </td>
        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
            {{ $coefficient->max_students }}
        </td>
        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
            <span class="font-mono">{{ number_format($coefficient->coefficient, 2, '.', '') }}</span>
        </td>
        <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
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
                <h4 class="text-lg font-medium text-gray-500">Chưa có hệ số sĩ số nào</h4>
                <p class="text-gray-400 mt-1">Thêm hệ số sĩ số để bắt đầu</p>
                <button onclick="openCreateModal()" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i> Thêm hệ số
                </button>
            </div>
        </td>
    </tr>
    @endforelse
@endsection

@section('modals')
<!-- Create Modal -->
<div class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-modal md:h-full" id="create-modal">
    <div class="relative p-4 w-full max-w-md h-full md:h-auto">
        <div class="relative bg-white rounded-lg shadow">
            <button type="button" onclick="closeCreateModal()" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                <i class="fas fa-times"></i>
                <span class="sr-only">Đóng</span>
            </button>
            <div class="py-6 px-6 lg:px-8">
                <h3 class="mb-4 text-xl font-medium text-gray-900">Thêm hệ số sĩ số</h3>
                <form class="space-y-6" action="{{ route('class-size-coefficients.store') }}" method="POST">
                    @csrf
                    <div>
                        <label for="min_students" class="block mb-2 text-sm font-medium text-gray-900">Sĩ số tối thiểu</label>
                        <input type="number" name="min_students" id="min_students" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                    </div>
                    <div>
                        <label for="max_students" class="block mb-2 text-sm font-medium text-gray-900">Sĩ số tối đa</label>
                        <input type="number" name="max_students" id="max_students" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                    </div>
                    <div>
                        <label for="coefficient" class="block mb-2 text-sm font-medium text-gray-900">Hệ số</label>
                        <input type="number" step="0.01" name="coefficient" id="coefficient" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                    </div>
                    <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Thêm mới</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
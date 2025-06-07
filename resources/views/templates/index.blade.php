@extends('layouts.app')

@section('title', 'Quản lý ' . $entityName)

@section('breadcrumb', 'Trang chủ / ' . $entityName)

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-2xl font-semibold text-gray-800">Danh sách {{ $entityName }}</h3>
        <div class="flex items-center space-x-4">
            <div class="relative search-box">
                <input type="text" placeholder="Tìm kiếm..." 
                    class="search-input pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
            @if ($routePrefix !== 'teaching-assignments')
                <a href="{{ route($routePrefix.'.create') }}" class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i> Thêm mới
                </a>
            @endif
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        @yield('table_headers')
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @yield('table_rows')
                </tbody>
            </table>
        </div>
    </div>
    @if(isset($records) && $records->isNotEmpty())
        <div class="flex flex-col md:flex-row justify-between items-center px-4 py-3 bg-gray-50 border-t text-sm text-gray-600 gap-2 mt-4 rounded-b-xl">
            <div class="text-center md:text-left">
                Hiển thị <b>{{ $records->firstItem() }}</b> đến <b>{{ $records->lastItem() }}</b> trong tổng số <b>{{ $records->total() }}</b> bản ghi
            </div>
            <div>
                {{ $records->appends(request()->query())->links() }}
            </div>
        </div>
    @endif
</div>

<!-- Delete Form -->
<form id="delete-form" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
    // Thêm biến entityName và routePrefix để sử dụng trong script
    const entityName = "{{ $entityName }}";
    const routePrefix = "{{ $routePrefix }}";
    
    function showConfirmModal(title, message, confirmCallback) {
        document.getElementById('modal-title').textContent = title;
        document.getElementById('modal-message').textContent = message;
        const confirmBtn = document.getElementById('modal-confirm');
        
        // Remove previous event listeners
        const newConfirmBtn = confirmBtn.cloneNode(true);
        confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);
        
        newConfirmBtn.addEventListener('click', () => {
            confirmCallback();
            document.getElementById('confirm-modal').classList.add('hidden');
        });
        
        document.getElementById('confirm-modal').classList.remove('hidden');
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Xử lý sự kiện click nút xóa
        document.addEventListener('click', function(e) {
            if (e.target.closest('.btn-delete')) {
                e.preventDefault();
                const id = e.target.closest('.btn-delete').dataset.id;
                showConfirmModal(
                    'Xóa ' + entityName,
                    'Bạn có chắc chắn muốn xóa ' + entityName.toLowerCase() + ' này?',
                    function() {
                        const form = document.getElementById('delete-form');
                        form.action = `/${routePrefix}/${id}`;
                        form.submit();
                    }
                );
            }
        });
    });
</script>
@endpush
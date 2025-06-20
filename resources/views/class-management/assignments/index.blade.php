@extends('layouts.app')

@section('title', 'Quản lý phân công giảng dạy')
@section('breadcrumb', 'Danh sách phân công')

@section('content')
<div class="content-section">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Danh sách phân công giảng dạy</h2>
            <a href="{{ route('teaching-assignments.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>Thêm phân công
            </a>
        </div>

        <!-- Filter Form -->
        <form method="GET" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lớp học</label>
                    <select name="class_id" class="w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">Tất cả lớp</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->class_code }} - {{ $class->course->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Giáo viên</label>
                    <select name="teacher_id" class="w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">Tất cả giáo viên</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }} ({{ $teacher->code }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 w-full">
                        <i class="fas fa-filter mr-2"></i>Lọc
                    </button>
                </div>
            </div>
        </form>

        <!-- Assignments Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STT</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lớp học</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giáo viên</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khoa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Học kỳ</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($assignments as $index => $assignment)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $index + $assignments->firstItem() }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900">{{ $assignment->class->class_code }}</div>
                            <div class="text-sm text-gray-500">{{ $assignment->class->course->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900">{{ $assignment->teacher->name }}</div>
                            <div class="text-sm text-gray-500">{{ $assignment->teacher->code }}</div>
                            <div class="text-sm text-gray-500">{{ $assignment->teacher->degree->short_name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $assignment->teacher->faculty->short_name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $assignment->class->semester->name }} ({{ $assignment->class->semester->academicYear->name }})
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button onclick="confirmDelete({{ $assignment->id }})" class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $assignments->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="delete-form" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<!-- Confirmation Modal -->
<div id="confirm-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg p-6 max-w-md w-full">
        <h3 class="text-lg font-medium text-gray-900 mb-4" id="modal-title">Xác nhận</h3>
        <p class="text-sm text-gray-500 mb-6" id="modal-message">Bạn có chắc chắn muốn xóa phân công này?</p>
        <div class="flex justify-end space-x-3">
            <button type="button" onclick="hideModal()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                Hủy
            </button>
            <button type="button" onclick="submitDelete()" class="px-4 py-2 bg-red-600 text-white rounded-md text-sm font-medium hover:bg-red-700">
                Xóa
            </button>
        </div>
    </div>
</div>

<script>
    function confirmDelete(id) {
        document.getElementById('delete-form').action = `/teaching-assignments/${id}`;
        document.getElementById('modal-message').textContent = 'Bạn có chắc chắn muốn xóa phân công này?';
        document.getElementById('confirm-modal').classList.remove('hidden');
    }

    function hideModal() {
        document.getElementById('confirm-modal').classList.add('hidden');
    }

    function submitDelete() {
        document.getElementById('delete-form').submit();
    }
</script>
@endsection
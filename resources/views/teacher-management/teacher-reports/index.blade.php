@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-6">Thống kê Giảng viên</h1>

    @if($hasNoResults)
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
            <p>Không tìm thấy giảng viên phù hợp với tiêu chí lọc.</p>
        </div>
    @endif

    <!-- Filter Section -->
    <div class="bg-white p-4 rounded shadow mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Faculty Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Khoa</label>
                <select name="faculty" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">Tất cả khoa</option>
                    @foreach($faculties as $faculty)
                        <option value="{{ $faculty->id }}" 
                            {{ $currentFilters['faculty'] == $faculty->id ? 'selected' : '' }}>
                            {{ $faculty->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Degree Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Bằng cấp</label>
                <select name="degree" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">Tất cả bằng cấp</option>
                    @foreach($degrees as $degree)
                        <option value="{{ $degree->id }}"
                            {{ $currentFilters['degree'] == $degree->id ? 'selected' : '' }}>
                            {{ $degree->name }} (Hệ số: {{ $degree->salary_coefficient }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Trạng thái</label>
                <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="all" {{ $currentFilters['status'] == 'all' ? 'selected' : '' }}>Tất cả</option>
                    <option value="active" {{ $currentFilters['status'] == 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                    <option value="inactive" {{ $currentFilters['status'] == 'inactive' ? 'selected' : '' }}>Ngừng hoạt động</option>
                </select>
            </div>

            <!-- Submit Button -->
            <div class="flex items-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                    Áp dụng
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-gray-500 text-sm font-medium">Tổng số giảng viên</h3>
            <p class="text-2xl font-bold">{{ $total }}</p>
            <p class="text-xs text-gray-500 mt-1">Theo bộ lọc hiện tại</p>
        </div>

        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-gray-500 text-sm font-medium">Đang hoạt động</h3>
            <p class="text-2xl font-bold text-green-600">{{ $active }}</p>
            <p class="text-xs text-gray-500 mt-1">Theo bộ lọc hiện tại</p>
        </div>

        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-gray-500 text-sm font-medium">Ngừng hoạt động</h3>
            <p class="text-2xl font-bold text-red-600">{{ $inactive }}</p>
            <p class="text-xs text-gray-500 mt-1">Theo bộ lọc hiện tại</p>
        </div>
    </div>

    <!-- Thống kê theo khoa -->
    <div class="bg-white p-4 rounded shadow mb-6">
        <h2 class="text-xl font-semibold mb-4">Phân bố theo Khoa</h2>
        @if($byFaculty->isEmpty())
            <p class="text-gray-500">Không có dữ liệu</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tên khoa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Số giảng viên</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tỷ lệ</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($byFaculty as $faculty)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $faculty->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $faculty->teachers_count }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($total > 0)
                                    {{ round(($faculty->teachers_count / $total) * 100, 1) }}%
                                @else
                                    0%
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Thống kê theo bằng cấp -->
    <div class="bg-white p-4 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Phân bố theo Bằng cấp</h2>
        @if($byDegree->isEmpty())
            <p class="text-gray-500">Không có dữ liệu</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tên bằng cấp</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hệ số lương</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Số giảng viên</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tỷ lệ</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($byDegree as $degree)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $degree->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $degree->salary_coefficient }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $degree->teachers_count }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($total > 0)
                                    {{ round(($degree->teachers_count / $total) * 100, 1) }}%
                                @else
                                    0%
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
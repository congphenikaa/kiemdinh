@extends('layouts.app')

@section('title', 'Dashboard')

@section('breadcrumb', 'Trang chủ')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    <!-- Cards Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Degree Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="flex items-center p-6">
                <div class="p-4 rounded-full bg-blue-500 text-white mr-4">
                    <i class="fas fa-graduation-cap text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-500">Bằng cấp</h3>
                    <p class="text-2xl font-semibold text-gray-800">{{ $degrees->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Faculty Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="flex items-center p-6">
                <div class="p-4 rounded-full bg-green-500 text-white mr-4">
                    <i class="fas fa-university text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-500">Khoa</h3>
                    <p class="text-2xl font-semibold text-gray-800">{{ $faculties->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Teacher Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="flex items-center p-6">
                <div class="p-4 rounded-full bg-orange-500 text-white mr-4">
                    <i class="fas fa-chalkboard-teacher text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-500">Giáo viên</h3>
                    <p class="text-2xl font-semibold text-gray-800">{{ $teachers->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Added Section -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Thêm gần đây</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã số</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Họ tên</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khoa</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bằng cấp</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày thêm</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($recentTeachers as $teacher)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $teacher->code }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $teacher->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $teacher->faculty->short_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $teacher->degree->short_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $teacher->created_at->format('d/m/Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
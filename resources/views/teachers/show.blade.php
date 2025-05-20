@extends('layouts.app')

@section('title', 'Chi tiết giáo viên')

@section('breadcrumb', 'Trang chủ / Giáo viên / Chi tiết')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <h3 class="text-2xl font-semibold text-gray-800">Chi tiết giáo viên</h3>
        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
            <a href="{{ route('teachers.edit', $teacher) }}" class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-edit mr-2"></i> Chỉnh sửa
            </a>
            <a href="{{ route('teachers.index') }}" class="flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Quay lại
            </a>
        </div>
    </div>

    <!-- Detail Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Thông tin cá nhân -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h4 class="text-lg font-medium text-gray-800">Thông tin cá nhân</h4>
            </div>
            <div class="divide-y divide-gray-200">
                <div class="px-6 py-4 flex">
                    <div class="w-1/3 font-medium text-gray-500">Mã số:</div>
                    <div class="w-2/3 text-gray-800">{{ $teacher->code }}</div>
                </div>
                <div class="px-6 py-4 flex">
                    <div class="w-1/3 font-medium text-gray-500">Họ tên:</div>
                    <div class="w-2/3 text-gray-800">{{ $teacher->name }}</div>
                </div>
                <div class="px-6 py-4 flex">
                    <div class="w-1/3 font-medium text-gray-500">Ngày sinh:</div>
                    <div class="w-2/3 text-gray-800">{{ $teacher->dob->format('d/m/Y') }}</div>
                </div>
                <div class="px-6 py-4 flex">
                    <div class="w-1/3 font-medium text-gray-500">Điện thoại:</div>
                    <div class="w-2/3 text-gray-800">{{ $teacher->phone ?: 'N/A' }}</div>
                </div>
                <div class="px-6 py-4 flex">
                    <div class="w-1/3 font-medium text-gray-500">Email:</div>
                    <div class="w-2/3 text-gray-800">{{ $teacher->email ?: 'N/A' }}</div>
                </div>
            </div>
        </div>

        <!-- Thông tin chuyên môn -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h4 class="text-lg font-medium text-gray-800">Thông tin chuyên môn</h4>
            </div>
            <div class="divide-y divide-gray-200">
                <div class="px-6 py-4 flex">
                    <div class="w-1/3 font-medium text-gray-500">Khoa:</div>
                    <div class="w-2/3 text-gray-800">{{ $teacher->faculty->name }}</div>
                </div>
                <div class="px-6 py-4 flex">
                    <div class="w-1/3 font-medium text-gray-500">Bằng cấp:</div>
                    <div class="w-2/3 text-gray-800">{{ $teacher->degree->name }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
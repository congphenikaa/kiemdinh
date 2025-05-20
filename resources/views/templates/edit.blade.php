@extends('layouts.app')

@section('title', "Chỉnh sửa $entityName")

@section('breadcrumb', 'Trang chủ / '.$entityName.' / Chỉnh sửa')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-2xl font-semibold text-gray-800">Chỉnh sửa {{$entityName}}</h3>
        <a href="{{ route($routePrefix.'.index') }}" class="flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Quay lại
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white p-6 rounded-xl shadow-md">
        <form action="{{ route($routePrefix.'.update', $model) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            @yield('form_fields')
            
            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 mt-6">
                <a href="{{ route($routePrefix.'.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors">
                    Hủy bỏ
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    Cập nhật
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
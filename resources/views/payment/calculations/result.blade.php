@extends('layouts.app')

@section('title', 'Kết quả tính toán thanh toán')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('payment-calculations.index') }}" class="text-blue-600 hover:text-blue-800">Tính toán thanh toán</a></li>
    <li class="breadcrumb-item active">Kết quả</li>
@endsection

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gradient-to-r from-blue-600 to-blue-800">
            <h2 class="text-xl font-semibold text-white">
                Kết quả tính toán thanh toán - {{ $semester->name }} ({{ $semester->academicYear->name }})
            </h2>
            <div class="space-x-2">
                <a href="{{ route('payment-batches.create', $semester) }}" 
                   class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Tạo đợt thanh toán
                </a>
                <a href="{{ route('payment-calculations.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Quay lại
                </a>
            </div>
        </div>

        <div class="p-6">
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Thông tin cấu hình</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Mức lương cơ bản: <span class="font-bold">{{ number_format($paymentConfig->base_salary_per_session) }} VNĐ/buổi</span></li>
                                <li>Tổng số giảng viên: <span class="font-bold">{{ $groupedPayments->count() }}</span></li>
                                <li>Tổng số lớp: <span class="font-bold">{{ count($paymentData) }}</span></li>
                                <li>Tổng số buổi dạy: <span class="font-bold">{{ $groupedPayments->sum('total_sessions') }}</span></li>
                                <li>Tổng số tiền: <span class="font-bold text-lg">{{ number_format($groupedPayments->sum('total_amount')) }} VNĐ</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Công thức tính</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p class="font-mono bg-gray-100 p-2 rounded inline-block">
                                Số buổi × (Hệ số HP + Hệ số lớp) × Hệ số GV × Lương cơ bản/buổi
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                @foreach($groupedPayments as $teacherId => $payment)
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <button class="w-full px-4 py-3 bg-gray-50 hover:bg-gray-100 transition-colors text-left flex justify-between items-center"
                            type="button"
                            data-toggle="collapse"
                            data-target="#teacher-{{ $teacherId }}"
                            aria-expanded="true"
                            aria-controls="teacher-{{ $teacherId }}">
                        <div class="flex items-center">
                            <div class="bg-blue-100 text-blue-800 rounded-full w-8 h-8 flex items-center justify-center mr-3">
                                {{ substr($payment['teacher']['code'], -2) }}
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900">{{ $payment['teacher']['name'] }}</h3>
                                <p class="text-sm text-gray-500">{{ $payment['classes']->count() }} lớp</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-blue-600">{{ number_format($payment['total_amount']) }} VNĐ</p>
                            <p class="text-sm text-gray-500">{{ $payment['total_sessions'] }} buổi</p>
                        </div>
                    </button>

                    <div id="teacher-{{ $teacherId }}" class="border-t border-gray-200 collapse show">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã lớp</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Học phần</th>
                                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Số buổi</th>
                                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Số SV</th>
                                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Hệ số HP</th>
                                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Hệ số lớp</th>
                                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng hệ số</th>
                                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Hệ số GV</th>
                                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($payment['classes'] as $class)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ $class['class_code'] }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $class['course_name'] }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-center text-gray-500">{{ $class['total_sessions'] }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-center text-gray-500">{{ $class['current_students'] }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-center text-gray-500">{{ number_format($class['course_coefficient'], 2) }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-center text-gray-500">{{ number_format($class['size_coefficient'], 2) }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-center font-medium text-blue-600">{{ number_format($class['course_coefficient'] + $class['size_coefficient'], 2) }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-center text-gray-500">{{ number_format($class['degree_coefficient'], 2) }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-right font-medium text-green-600">{{ number_format($class['total_amount']) }} VNĐ</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50 font-medium">
                                    <tr>
                                        <td colspan="2" class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">Tổng cộng</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-center text-gray-900">{{ $payment['total_sessions'] }}</td>
                                        <td colspan="4"></td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-center text-gray-900">{{ number_format($payment['classes']->avg('degree_coefficient'), 2) }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-right text-green-600">{{ number_format($payment['total_amount']) }} VNĐ</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .collapse:not(.show) {
        display: none;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mở accordion đầu tiên
        document.querySelector('.collapse').classList.add('show');
        
        // Thêm sự kiện click cho các nút toggle
        document.querySelectorAll('[data-toggle="collapse"]').forEach(button => {
            button.addEventListener('click', function() {
                const target = document.querySelector(this.getAttribute('data-target'));
                target.classList.toggle('show');
            });
        });
    });
</script>
@endpush
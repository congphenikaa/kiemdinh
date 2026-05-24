@extends('layouts.app')

@section('title', 'Kết quả tính toán thanh toán')
@section('breadcrumb', 'Thanh toán / Kết quả tính toán')

@section('content')
<div class="space-y-6">
    <x-page-card :title="'Kết quả: ' . $semester->name . ' (' . $semester->academicYear->name . ')'">
        <x-slot name="actions">
            <a href="{{ route('payment-batches.create', $semester) }}" class="btn-success !py-2 !text-sm">
                <i class="fas fa-file-invoice-dollar"></i> Tạo đợt thanh toán
            </a>
            <a href="{{ route('payment-calculations.index') }}" class="btn-secondary !py-2 !text-sm">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </x-slot>

        <div class="info-box mb-6">
            <p class="mb-2 font-semibold">Thông tin cấu hình</p>
            <ul class="list-inside list-disc space-y-1 text-sm">
                <li>Mức lương cơ bản: <strong>{{ number_format($paymentConfig->base_salary_per_session) }} ₫/buổi</strong></li>
                <li>Giảng viên: <strong>{{ $groupedPayments->count() }}</strong> · Lớp: <strong>{{ count($paymentData) }}</strong></li>
                <li>Buổi đã dạy: <strong>{{ $groupedPayments->sum('total_sessions') }}</strong></li>
                <li>Tổng tiền: <strong class="text-base">{{ number_format($groupedPayments->sum('total_amount')) }} ₫</strong></li>
            </ul>
        </div>

        <div class="warning-box mb-6">
            <p class="mb-1 font-semibold">Công thức tính</p>
            <code class="rounded bg-white/60 px-2 py-1 text-xs">Số buổi đã dạy × (Hệ số HP + Hệ số lớp) × Hệ số GV × Lương cơ bản/buổi</code>
        </div>

            <div class="space-y-4">
                @foreach($groupedPayments as $teacherId => $payment)
                <div class="overflow-hidden rounded-lg border border-slate-200">
                    <button class="flex w-full items-center justify-between bg-slate-50 px-4 py-3 text-left transition hover:bg-slate-100"
                            type="button"
                            data-toggle="collapse"
                            data-target="#teacher-{{ $teacherId }}"
                            aria-expanded="true"
                            aria-controls="teacher-{{ $teacherId }}">
                        <div class="flex items-center">
                            <div class="mr-3 flex h-8 w-8 items-center justify-center rounded-full bg-primary-100 text-sm font-semibold text-primary-800">
                                {{ substr($payment['teacher']['code'], -2) }}
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900">{{ $payment['teacher']['name'] }}</h3>
                                <p class="text-sm text-gray-500">{{ $payment['classes']->count() }} lớp</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-blue-600">{{ number_format($payment['total_amount']) }} VNĐ</p>
                            <p class="text-sm text-gray-500">{{ $payment['total_sessions'] }} buổi đã dạy</p>
                        </div>
                    </button>

                    <div id="teacher-{{ $teacherId }}" class="border-t border-gray-200 collapse show">
                        <div class="overflow-x-auto">
                            <table class="data-table min-w-full">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã lớp</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Học phần</th>
                                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Buổi đã dạy</th>
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
    </x-page-card>
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
@extends('layouts.app')

@section('title', 'Chi tiết đợt thanh toán')
@section('breadcrumb', 'Đợt thanh toán / Chi tiết')

@section('content')
<div class="space-y-6">
    <x-page-card :title="'Đợt: ' . $paymentBatch->name">
        <x-slot name="actions">
            @if($paymentBatch->status === 'pending')
                <a href="{{ route('payment-batches.edit', $paymentBatch->id) }}" class="btn-secondary !py-2 !text-sm">
                    <i class="fas fa-pen"></i> Sửa
                </a>
            @endif
            <a href="{{ route('payment-batches.index') }}" class="btn-secondary !py-2 !text-sm">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </x-slot>
            <!-- Thông tin đợt thanh toán -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Thông tin đợt thanh toán</h3>
                    <div class="space-y-2">
                        <p><span class="font-medium text-gray-700">Kỳ học:</span> {{ $paymentBatch->semester->name }}</p>
                        <p><span class="font-medium text-gray-700">Năm học:</span> {{ $paymentBatch->semester->academicYear->name }}</p>
                        <p><span class="font-medium text-gray-700">Ngày xử lý:</span> {{ $paymentBatch->processed_date->format('d/m/Y') }}</p>
                        <p>
                            <span class="font-medium text-gray-700">Trạng thái:</span> 
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($paymentBatch->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($paymentBatch->status === 'completed') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($paymentBatch->status) }}
                            </span>
                        </p>
                        @if($paymentBatch->notes)
                        <p><span class="font-medium text-gray-700">Ghi chú:</span> {{ $paymentBatch->notes }}</p>
                        @endif
                    </div>
                </div>
                
                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Thống kê thanh toán</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white p-3 rounded shadow">
                            <p class="text-sm text-gray-500">Tổng số thanh toán</p>
                            <p class="text-xl font-bold">{{ $summary['total_payments'] }}</p>
                        </div>
                        <div class="bg-white p-3 rounded shadow">
                            <p class="text-sm text-gray-500">Đã thanh toán</p>
                            <p class="text-xl font-bold text-green-600">{{ $summary['paid_count'] }}</p>
                        </div>
                        <div class="bg-white p-3 rounded shadow">
                            <p class="text-sm text-gray-500">Chờ xử lý</p>
                            <p class="text-xl font-bold text-yellow-600">{{ $summary['pending_count'] }}</p>
                        </div>
                        <div class="bg-white p-3 rounded shadow">
                            <p class="text-sm text-gray-500">Đã hủy</p>
                            <p class="text-xl font-bold text-red-600">{{ $summary['cancelled_count'] }}</p>
                        </div>
                        <div class="bg-white p-3 rounded shadow col-span-2">
                            <p class="text-sm text-gray-500">Tổng số tiền</p>
                            <p class="text-xl font-bold">{{ number_format($summary['total_amount']) }} VNĐ</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Danh sách thanh toán -->
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Danh sách thanh toán</h3>
                <p class="text-sm text-gray-500">Tổng cộng {{ $paymentBatch->payments->count() }} khoản thanh toán</p>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STT</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giảng viên</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lớp</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Học phần</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Buổi đã dạy</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Hệ số</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thành tiền</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày thanh toán</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($paymentBatch->payments as $index => $payment)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $payment->teacher->code }} - {{ $payment->teacher->name }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $payment->class->class_code }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $payment->class->course->name }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-center text-gray-500">{{ $payment->total_sessions }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-center text-gray-500">
                                <div class="flex flex-col space-y-1">
                                    <span class="text-xs">Bằng: {{ number_format($payment->degree_coefficient, 2) }}</span>
                                    <span class="text-xs">Lớp: {{ number_format($payment->size_coefficient, 2) }}</span>
                                    <span class="text-xs">HP: {{ number_format($payment->course_coefficient, 2) }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-right font-medium text-green-600">{{ number_format($payment->total_amount) }} VNĐ</td>
                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($payment->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($payment->status === 'paid') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-center text-gray-500">
                                {{ $payment->payment_date ? $payment->payment_date->format('d/m/Y') : 'N/A' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 font-medium">
                        <tr>
                            <td colspan="6" class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 text-right">Tổng cộng</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-right text-green-600">{{ number_format($paymentBatch->payments->sum('total_amount')) }} VNĐ</td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
    </x-page-card>
</div>
@endsection
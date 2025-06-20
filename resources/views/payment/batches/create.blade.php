@extends('layouts.app')

@section('title', 'Tạo đợt thanh toán')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('payment-batches.index') }}" class="text-blue-600 hover:text-blue-800">Đợt thanh toán</a></li>
    <li class="breadcrumb-item active">Tạo mới</li>
@endsection

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gradient-to-r from-blue-600 to-blue-800">
            <h2 class="text-xl font-semibold text-white">
                Tạo đợt thanh toán - {{ $semester->name }} ({{ $semester->academicYear->name }})
            </h2>
            <a href="{{ route('payment-calculations.calculate', $semester) }}" class="inline-flex items-center px-3 py-1 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Quay lại
            </a>
        </div>
        <div class="p-6">
            <form action="{{ route('payment-batches.store', $semester->id) }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="batch_name" class="block text-sm font-medium text-gray-700 mb-1">Tên đợt thanh toán <span class="text-red-500">*</span></label>
                        <input type="text" id="batch_name" name="batch_name" value="{{ old('batch_name', 'Đợt thanh toán tháng ' . now()->format('m/Y')) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('batch_name') border-red-500 @enderror" required>
                        @error('batch_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-1">Ngày thanh toán <span class="text-red-500">*</span></label>
                        <input type="date" id="payment_date" name="payment_date" value="{{ old('payment_date', now()->format('Y-m-d')) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('payment_date') border-red-500 @enderror" required>
                        @error('payment_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Ghi chú</label>
                    <textarea id="notes" name="notes" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Thông tin thanh toán</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    <li>Tổng số giảng viên: <span class="font-bold">{{ $groupedPayments->count() }}</span></li>
                                    <li>Tổng số lớp: <span class="font-bold">{{ $totalClasses }}</span></li>
                                    <li>Tổng số buổi dạy: <span class="font-bold">{{ $groupedPayments->sum('total_sessions') }}</span></li>
                                    <li>Tổng số tiền: <span class="font-bold text-lg">{{ number_format($totalAmount) }} VNĐ</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="window.history.back()" 
                            class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Hủy bỏ
                    </button>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Xác nhận tạo đợt thanh toán
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
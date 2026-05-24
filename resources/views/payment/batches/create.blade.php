@extends('layouts.app')

@section('title', 'Tạo đợt thanh toán')
@section('breadcrumb', 'Đợt thanh toán / Tạo mới')

@section('content')
<div class="mx-auto max-w-3xl space-y-6">
    <x-page-card :title="'Tạo đợt: ' . $semester->name">
        <x-slot name="actions">
            <a href="{{ route('payment-calculations.calculate', $semester) }}" class="btn-secondary !py-2 !text-sm">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </x-slot>
            <form id="create-batch-form" action="{{ route('payment-batches.store', $semester) }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="batch_name" class="form-label">Tên đợt thanh toán <span class="text-red-500">*</span></label>
                        <input type="text" id="batch_name" name="batch_name" 
                               value="{{ old('batch_name', 'Đợt thanh toán tháng ' . now()->format('m/Y')) }}"
                               class="form-input w-full @error('batch_name') border-red-500 @enderror" 
                               required>
                        @error('batch_name')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="payment_date" class="form-label">Ngày thanh toán <span class="text-red-500">*</span></label>
                        <input type="date" id="payment_date" name="payment_date" 
                               value="{{ old('payment_date', now()->format('Y-m-d')) }}"
                               min="{{ now()->format('Y-m-d') }}"
                               class="form-input w-full @error('payment_date') !border-red-500 @enderror" 
                               required>
                        @error('payment_date')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-6">
                    <label for="notes" class="form-label">Ghi chú</label>
                    <textarea id="notes" name="notes" rows="3"
                              class="form-textarea @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="info-box mb-6">
                    <p class="mb-2 font-semibold">Thông tin thanh toán</p>
                    <ul class="list-inside list-disc space-y-1 text-sm">
                        <li>Giảng viên: <strong>{{ $groupedPayments->count() }}</strong></li>
                        <li>Lớp: <strong>{{ $totalClasses }}</strong> · Buổi: <strong>{{ $groupedPayments->sum('total_sessions') }}</strong></li>
                        <li>Tổng: <strong>{{ number_format($totalAmount) }} ₫</strong></li>
                    </ul>
                </div>

                <div class="flex flex-col-reverse justify-end gap-3 border-t border-slate-100 pt-6 sm:flex-row">
                    <button type="button" onclick="window.history.back()" class="btn-secondary">Hủy</button>
                    <button type="submit" id="submit-btn" class="btn-success">Xác nhận tạo đợt</button>
                </div>
            </form>
    </x-page-card>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('create-batch-form');
    const submitBtn = document.getElementById('submit-btn');
    
    form.addEventListener('submit', function(e) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Đang xử lý...
        `;
    });
});
</script>
@endpush
@endsection
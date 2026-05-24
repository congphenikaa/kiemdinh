@extends('layouts.app')

@section('title', 'Chỉnh sửa đợt thanh toán')
@section('breadcrumb', 'Đợt thanh toán / Chỉnh sửa')

@section('content')
<div class="mx-auto max-w-3xl space-y-6">
    <x-page-card :title="'Sửa đợt: ' . $batch->name">
        <x-slot name="actions">
            <a href="{{ route('payment-batches.show', $batch) }}" class="btn-secondary !py-2 !text-sm">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </x-slot>
            <form action="{{ route('payment-batches.update', $batch) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="name" class="form-label">Tên đợt thanh toán <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" 
                               value="{{ old('name', $batch->name) }}"
                               class="form-input @error('name') border-red-500 @enderror" 
                               required>
                        @error('name')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="processed_date" class="form-label">Ngày xử lý <span class="text-red-500">*</span></label>
                        <input type="date" id="processed_date" name="processed_date" 
                               value="{{ old('processed_date', $batch->processed_date->format('Y-m-d')) }}"
                               class="form-input @error('processed_date') border-red-500 @enderror" 
                               required>
                        @error('processed_date')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-6">
                    <label for="status" class="form-label">Trạng thái <span class="text-red-500">*</span></label>
                    <select id="status" name="status" class="form-select @error('status') border-red-500 @enderror" required>
                        <option value="pending" {{ old('status', $batch->status) === 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                        <option value="completed" {{ old('status', $batch->status) === 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                        <option value="cancelled" {{ old('status', $batch->status) === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                    @error('status')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label for="notes" class="form-label">Ghi chú</label>
                    <textarea id="notes" name="notes" rows="3"
                              class="form-textarea @error('notes') border-red-500 @enderror">{{ old('notes', $batch->notes) }}</textarea>
                    @error('notes')
                        <p class="form-error">{{ $message }}</p>
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
                                    <li>Tổng số giảng viên: <span class="font-bold">{{ $summary['total_payments'] }}</span></li>
                                    <li>Tổng số tiền: <span class="font-bold text-lg">{{ number_format($summary['total_amount']) }} VNĐ</span></li>
                                    <li>Trạng thái hiện tại: <span class="font-bold">{{ ucfirst($batch->status) }}</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col-reverse justify-end gap-3 border-t border-slate-100 pt-6 sm:flex-row">
                    <button type="button" onclick="window.history.back()" class="btn-secondary">Hủy</button>
                    <button type="submit" id="submit-btn" class="btn-primary">Cập nhật</button>
                </div>
            </form>
    </x-page-card>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
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
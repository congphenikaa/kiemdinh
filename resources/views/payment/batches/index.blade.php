@extends('layouts.app')

@section('title', 'Đợt thanh toán')
@section('breadcrumb', 'Thanh toán / Đợt thanh toán')

@section('content')
<div class="space-y-6">
    <x-page-card title="Danh sách đợt thanh toán">
        <x-slot name="actions">
            <a href="{{ route('payment-calculations.index') }}" class="btn-secondary !py-2 !text-sm">
                <i class="fas fa-calculator"></i>
                Tính toán lương
            </a>
        </x-slot>

        <div class="overflow-x-auto">
            <table class="data-table min-w-full">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tên đợt</th>
                        <th>Kỳ học</th>
                        <th>Năm học</th>
                        <th>Ngày xử lý</th>
                        <th class="text-right">Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th class="text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($batches as $index => $batch)
                        <tr>
                            <td class="text-slate-400">{{ $batches->firstItem() + $index }}</td>
                            <td class="font-medium text-slate-900">{{ $batch->name }}</td>
                            <td>{{ $batch->semester->name }}</td>
                            <td>{{ $batch->semester->academicYear->name }}</td>
                            <td>{{ $batch->processed_date->format('d/m/Y') }}</td>
                            <td class="text-right font-medium text-slate-900">{{ number_format($batch->total_amount) }} ₫</td>
                            <td>
                                @if($batch->status == 'pending')
                                    <x-badge variant="warning">Chờ xử lý</x-badge>
                                @elseif($batch->status == 'completed')
                                    <x-badge variant="success">Hoàn thành</x-badge>
                                @else
                                    <x-badge variant="danger">Đã hủy</x-badge>
                                @endif
                            </td>
                            <td class="text-right">
                                <div class="inline-flex items-center gap-1">
                                    <a href="{{ route('payment-batches.show', $batch->id) }}" class="btn-icon text-primary-600 hover:bg-primary-50" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($batch->status == 'pending')
                                        <a href="{{ route('payment-batches.edit', $batch->id) }}" class="btn-icon text-amber-600 hover:bg-amber-50" title="Sửa">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                        <form action="{{ route('payment-batches.destroy', $batch->id) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Bạn có chắc muốn xóa đợt thanh toán này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-icon text-red-600 hover:bg-red-50" title="Xóa">
                                                <i class="fas fa-trash-can"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-500">Chưa có đợt thanh toán</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($batches->hasPages())
            <div class="mt-4 border-t border-slate-100 pt-4">{{ $batches->links() }}</div>
        @endif
    </x-page-card>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Tính toán thanh toán')
@section('breadcrumb', 'Thanh toán / Tính toán')

@section('content')
<div class="space-y-6">
    <x-page-card title="Chọn kỳ học để tính toán" description="Tính tiền dạy theo cấu hình lương và hệ số sĩ số hiện tại">
        <div class="overflow-x-auto">
            <table class="data-table min-w-full">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Kỳ học</th>
                        <th>Năm học</th>
                        <th>Bắt đầu</th>
                        <th>Kết thúc</th>
                        <th>Trạng thái</th>
                        <th class="text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($semesters as $index => $semester)
                        <tr>
                            <td class="text-slate-400">{{ $semesters->firstItem() + $index }}</td>
                            <td class="font-medium text-slate-900">{{ $semester->name }}</td>
                            <td>{{ $semester->academicYear->name }}</td>
                            <td>{{ $semester->start_date->format('d/m/Y') }}</td>
                            <td>{{ $semester->end_date->format('d/m/Y') }}</td>
                            <td>
                                <x-badge :variant="$semester->is_active ? 'success' : 'neutral'">
                                    {{ $semester->is_active ? 'Đang hoạt động' : 'Không hoạt động' }}
                                </x-badge>
                            </td>
                            <td class="text-right">
                                <a href="{{ route('payment-calculations.calculate', $semester->id) }}" class="btn-primary !py-1.5 !text-xs">
                                    <i class="fas fa-calculator"></i>
                                    Tính toán
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-500">Chưa có kỳ học</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($semesters->hasPages())
            <div class="mt-4 border-t border-slate-100 pt-4">{{ $semesters->links() }}</div>
        @endif
    </x-page-card>
</div>
@endsection

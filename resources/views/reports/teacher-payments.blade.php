@extends('layouts.app')

@section('title', 'Báo cáo tiền dạy giáo viên')
@section('breadcrumb', 'Báo cáo tiền dạy giáo viên')

@section('content')
<div class="space-y-6">
    <x-filter-panel>
        <form method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-12">
            <div class="md:col-span-5">
                <label class="form-label">Học kỳ</label>
                <select name="semester" class="form-input">
                        @foreach($semesters as $semester)
                            <option value="{{ $semester->id }}" 
                                {{ (string)$semester->id === (string)$semesterId ? 'selected' : '' }}>
                                {{ $semester->name }} ({{ $semester->academicYear->name ?? '' }})
                            </option>
                        @endforeach
                    </select>
                </div>
            <div class="md:col-span-5">
                <label class="form-label">Giáo viên</label>
                <select name="teacher" class="form-input">
                        <option value="">-- Chọn giáo viên --</option>
                        @foreach($teachers as $t)
                            <option value="{{ $t->id }}" 
                                {{ (string)$t->id === (string)$teacherId ? 'selected' : '' }}>
                                {{ $t->code ?? '' }} - {{ $t->name ?? '' }} 
                                @isset($t->faculty)
                                    ({{ $t->faculty->short_name ?? '' }})
                                @endisset
                            </option>
                        @endforeach
                    </select>
                </div>
            <div class="flex items-end md:col-span-2">
                <button type="submit" class="btn-primary w-full">
                    <i class="fas fa-search"></i> Xem báo cáo
                </button>
            </div>
        </form>
    </x-filter-panel>

    @if($teacherId && $semesterId && $teacher)
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <x-stat-card label="Tổng tiền" :value="number_format($stats->total_amount) . ' ₫'" icon="fa-coins" color="primary" />
            <x-stat-card label="Số lớp dạy" :value="$stats->total_classes" icon="fa-school" color="emerald" />
            <x-stat-card label="Số buổi dạy" :value="$stats->total_sessions" icon="fa-calendar" color="violet" />
        </div>

        <!-- Teacher Info and Chart -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Chart Column -->
            <div class="lg:col-span-2">
                <div class="app-card overflow-hidden">
                <div class="border-b border-slate-100 px-5 py-4">
                    <h2 class="text-base font-semibold text-slate-900">Biểu đồ thanh toán theo tháng</h2>
                    </div>
                    <div class="p-6">
                        @if($monthlyData->isNotEmpty())
                            <div class="h-64">
                                <canvas id="monthlyChart"></canvas>
                            </div>
                        @else
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700">Không có dữ liệu thanh toán theo tháng</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Teacher Info Column -->
            <div class="lg:col-span-1">
                <div class="app-card overflow-hidden">
                <div class="border-b border-slate-100 px-5 py-4">
                    <h2 class="text-base font-semibold text-slate-900">Thông tin giáo viên</h2>
                    </div>
                    <div class="p-6">
                        <h4 class="text-xl font-bold text-gray-900 mb-1">{{ $teacher->name ?? 'N/A' }}</h4>
                        <p class="text-gray-600 mb-3">{{ $teacher->code ?? 'N/A' }}</p>
                        
                        <div class="flex space-x-2 mb-4">
                            @isset($teacher->degree)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $teacher->degree->short_name ?? 'N/A' }}
                                </span>
                            @endisset
                            
                            @isset($teacher->faculty)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $teacher->faculty->short_name ?? 'N/A' }}
                                </span>
                            @endisset
                        </div>
                        
                        <hr class="my-4">
                        
                        <div class="text-left space-y-3">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-5 w-5 text-blue-500">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div class="ml-2 text-sm text-gray-700">
                                    {{ $teacher->email ?? 'N/A' }}
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-5 w-5 text-blue-500">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                </div>
                                <div class="ml-2 text-sm text-gray-700">
                                    {{ $teacher->phone ?? 'N/A' }}
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-5 w-5 text-blue-500">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                </div>
                                <div class="ml-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $teacher->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $teacher->is_active ? 'Đang hoạt động' : 'Ngừng hoạt động' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Details -->
        <div class="app-card overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h5 class="text-lg font-semibold text-gray-800">Chi tiết thanh toán</h2>
                <span class="text-sm text-gray-500">Tổng: {{ $payments->count() }} bản ghi</span>
            </div>
            <div class="p-6">
                @if($payments->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã lớp</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Môn học</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Số buổi</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Hệ số</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Đơn giá</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thành tiền</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày thanh toán</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($payments as $index => $payment)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $payment->class->class_code ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $payment->class->course->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">{{ $payment->total_sessions }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                            {{ $payment->degree_coefficient }} × {{ $payment->size_coefficient }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">{{ number_format($payment->base_rate) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-600 text-right">
                                            {{ number_format($payment->total_amount) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            {{ $payment->payment_date ? $payment->payment_date->format('d/m/Y') : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $payment->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ $payment->status === 'paid' ? 'Đã thanh toán' : 'Chờ xử lý' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="6" class="px-6 py-3 text-sm font-medium text-gray-900 text-right">Tổng cộng:</td>
                                    <td class="px-6 py-3 text-sm font-semibold text-blue-600 text-right">{{ number_format($payments->sum('total_amount')) }}</td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">Không có dữ liệu thanh toán nào trong học kỳ này</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Teaching Assignments -->
        <div class="app-card overflow-hidden">
                <div class="border-b border-slate-100 px-5 py-4">
                    <h2 class="text-base font-semibold text-slate-900">Danh sách lớp giảng dạy</h2>
            </div>
            <div class="p-6">
                @if($teachingAssignments->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã lớp</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Môn học</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Số buổi đã dạy</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Sĩ số</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($teachingAssignments as $assignment)
                                    @if($assignment->class)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $assignment->class->class_code ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $assignment->class->course->name ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                                {{ $assignment->class->schedules->count() }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                                {{ $assignment->class->current_students ?? 0 }}/{{ $assignment->class->max_students }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                @php
                                                    $statusClass = [
                                                        'open' => ['bg-green-100 text-green-800', 'Đang mở'],
                                                        'finished' => ['bg-blue-100 text-blue-800', 'Đã kết thúc'],
                                                        'closed' => ['bg-gray-100 text-gray-800', 'Đã đóng']
                                                    ][$assignment->class->status ?? 'closed'] ?? ['bg-gray-100 text-gray-800', 'N/A'];
                                                @endphp
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass[0] }}">
                                                    {{ $statusClass[1] }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">Không có lớp giảng dạy nào trong học kỳ này</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="app-card overflow-hidden">
            <div class="p-12 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-gray-100 mb-4">
                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Chưa chọn dữ liệu báo cáo</h3>
                <p class="text-gray-500 mb-6">
                    Vui lòng chọn học kỳ và giáo viên để xem báo cáo chi tiết
                </p>
                <button type="button" disabled class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 opacity-50 cursor-not-allowed">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Xem báo cáo
                </button>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if($teacherId && $semesterId && $monthlyData->isNotEmpty())
            // Monthly Payment Chart
            const ctx = document.getElementById('monthlyChart').getContext('2d');
            const monthlyChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($monthlyData->pluck('month')->map(function($month) {
                        return 'Tháng ' . $month; // PHP concatenation (.) instead of JS (+)
                    })) !!},
                    datasets: [{
                        label: 'Tổng tiền (VNĐ)',
                        data: {!! json_encode($monthlyData->pluck('total')) !!},
                        backgroundColor: 'rgba(59, 130, 246, 0.7)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString() + ' VNĐ';
                                }
                            },
                            grid: {
                                drawBorder: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y.toLocaleString() + ' VNĐ';
                                }
                            }
                        }
                    }
                }
            });
        @endif
    });
</script>
@endpush
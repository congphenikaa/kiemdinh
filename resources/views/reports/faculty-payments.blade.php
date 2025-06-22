@extends('layouts.app')

@section('title', 'Báo cáo tiền dạy theo khoa')
@section('breadcrumb', 'Báo cáo tiền dạy theo khoa')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Filter Card -->
    <div class="bg-white rounded-lg shadow-md mb-6">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h5 class="text-lg font-semibold text-gray-800">Lọc dữ liệu</h5>
        </div>
        <div class="p-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <div class="md:col-span-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Học kỳ</label>
                    <select name="semester" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @foreach($semesters as $semester)
                            <option value="{{ $semester->id }}" {{ $semesterId == $semester->id ? 'selected' : '' }}>
                                {{ $semester->name }} ({{ $semester->academicYear->name }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Khoa</label>
                    <select name="faculty" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">-- Chọn khoa --</option>
                        @foreach($faculties as $faculty)
                            <option value="{{ $faculty->id }}" {{ $facultyId == $faculty->id ? 'selected' : '' }}>
                                {{ $faculty->name }} ({{ $faculty->short_name }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2 flex items-end">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Xem báo cáo
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if($facultyId && $semesterId)
        @if($payments->isNotEmpty())
            <!-- Summary Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <!-- Total Payment Card -->
                <div class="bg-white rounded-lg shadow-md border-l-4 border-blue-500 overflow-hidden">
                    <div class="p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-100 p-3 rounded-full">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 truncate">Tổng tiền</p>
                                <p class="text-xl font-semibold text-gray-900">
                                    {{ number_format($stats->total_amount) }} VNĐ
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    Tổng thanh toán học kỳ
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Teachers Count Card -->
                <div class="bg-white rounded-lg shadow-md border-l-4 border-green-500 overflow-hidden">
                    <div class="p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-100 p-3 rounded-full">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 truncate">Số giáo viên</p>
                                <p class="text-xl font-semibold text-gray-900">
                                    {{ $stats->teacher_count }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    Giáo viên được thanh toán
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Average Payment Card -->
                <div class="bg-white rounded-lg shadow-md border-l-4 border-indigo-500 overflow-hidden">
                    <div class="p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-indigo-100 p-3 rounded-full">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 truncate">Trung bình</p>
                                <p class="text-xl font-semibold text-gray-900">
                                    {{ number_format($stats->average_payment) }} VNĐ
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    Trung bình mỗi giáo viên
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Classes Count Card -->
                <div class="bg-white rounded-lg shadow-md border-l-4 border-purple-500 overflow-hidden">
                    <div class="p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-purple-100 p-3 rounded-full">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 19.477 5.754 19 7.5 19s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 19.477 18.247 19 16.5 19c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 truncate">Số lớp</p>
                                <p class="text-xl font-semibold text-gray-900">
                                    {{ $stats->class_count }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    Tổng số lớp giảng dạy
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Faculty Info and Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Charts Column -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Department Distribution Chart -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h5 class="text-lg font-semibold text-gray-800">Phân bổ tiền dạy theo bộ môn</h5>
                        </div>
                        <div class="p-6">
                            <div class="h-64">
                                <canvas id="departmentChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Trend Chart -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h5 class="text-lg font-semibold text-gray-800">Xu hướng thanh toán qua các học kỳ</h5>
                        </div>
                        <div class="p-6">
                            <div class="h-64">
                                <canvas id="trendChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Faculty Info and Top Teachers Column -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Faculty Info -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h5 class="text-lg font-semibold text-gray-800">Thông tin khoa</h5>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="flex-shrink-0 bg-blue-100 p-3 rounded-full">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-xl font-bold text-gray-900">{{ $faculty->name }}</h4>
                                    <p class="text-gray-600">{{ $faculty->short_name }}</p>
                                </div>
                            </div>
                            
                            <div class="space-y-3">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 h-5 w-5 text-blue-500">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-2 text-sm text-gray-700">
                                        {{ $faculty->description ?? 'Không có mô tả' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top Teachers -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h5 class="text-lg font-semibold text-gray-800">Top 5 giáo viên</h5>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                @foreach($topTeachers as $index => $teacher)
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 pt-1">
                                        <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-blue-100 text-blue-800 font-medium">
                                            {{ $index + 1 }}
                                        </span>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <div class="flex items-center justify-between">
                                            <h4 class="text-sm font-medium text-gray-900">{{ $teacher['teacher']->name }}</h4>
                                            <span class="text-sm font-semibold text-blue-600">
                                                {{ number_format($teacher['total_amount']) }} VNĐ
                                            </span>
                                        </div>
                                        <div class="mt-1 flex items-center justify-between">
                                            <p class="text-xs text-gray-500">
                                                {{ $teacher['teacher']->code }} • {{ $teacher['class_count'] }} lớp
                                            </p>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $teacher['teacher']->degree->short_name ?? 'N/A' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Details -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h5 class="text-lg font-semibold text-gray-800">Chi tiết thanh toán</h5>
                    <span class="text-sm text-gray-500">Tổng: {{ $payments->count() }} bản ghi</span>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giáo viên</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã lớp</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Môn học</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Số buổi</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Hệ số</th>
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
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full" src="{{ asset('images/default-avatar.jpg') }}" alt="">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $payment->teacher->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $payment->teacher->code }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $payment->class->class_code }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $payment->class->course->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">{{ $payment->total_sessions }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                            {{ $payment->degree_coefficient }} × {{ $payment->size_coefficient }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-600 text-right">
                                            {{ number_format($payment->total_amount) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            {{ $payment->payment_date->format('d/m/Y') }}
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
                </div>
            </div>
        @else
            <!-- No Data State -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-12 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 mb-4">
                        <svg class="h-6 w-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Không có dữ liệu thanh toán</h3>
                    <p class="text-gray-500 mb-6">
                        Không có thanh toán nào trong khoa này cho học kỳ đã chọn
                    </p>
                </div>
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-12 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-gray-100 mb-4">
                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Chưa chọn dữ liệu báo cáo</h3>
                <p class="text-gray-500 mb-6">
                    Vui lòng chọn học kỳ và khoa để xem báo cáo chi tiết
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
        @if($facultyId && $semesterId && $payments->isNotEmpty())
            // Department Distribution Pie Chart
            const deptCtx = document.getElementById('departmentChart').getContext('2d');
            const deptChart = new Chart(deptCtx, {
                type: 'pie',
                data: {
                    labels: {!! json_encode($departmentData->pluck('faculty')) !!},
                    datasets: [{
                        data: {!! json_encode($departmentData->pluck('total')) !!},
                        backgroundColor: {!! json_encode($departmentData->pluck('color')) !!},
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value.toLocaleString()} VNĐ (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });

            // Payment Trend Line Chart
            const trendCtx = document.getElementById('trendChart').getContext('2d');
            const trendChart = new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($trendData->pluck('semester')) !!},
                    datasets: [
                        {
                            label: 'Tổng tiền (VNĐ)',
                            data: {!! json_encode($trendData->pluck('total_amount')) !!},
                            borderColor: '#3B82F6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.3,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Trung bình/GV (VNĐ)',
                            data: {!! json_encode($trendData->pluck('average_per_teacher')) !!},
                            borderColor: '#10B981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            tension: 0.3,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString() + ' VNĐ';
                                }
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            grid: {
                                drawOnChartArea: false
                            },
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString() + ' VNĐ';
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    label += context.raw.toLocaleString() + ' VNĐ';
                                    return label;
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
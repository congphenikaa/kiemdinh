@extends('layouts.app')

@section('title', 'Báo cáo tiền dạy theo khoa')
@section('breadcrumb', 'Báo cáo tiền dạy theo khoa')

@section('content')
<div class="space-y-6">
    <x-filter-panel>
        <form method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-12">
            <div class="md:col-span-5">
                <label class="form-label">Học kỳ</label>
                <select name="semester" class="form-input">
                        @foreach($semesters as $semester)
                            <option value="{{ $semester->id }}" {{ (string)$semester->id === (string)$semesterId ? 'selected' : '' }}>
                                {{ $semester->name }} ({{ $semester->academicYear->name ?? '' }})
                            </option>
                        @endforeach
                    </select>
                </div>
            <div class="md:col-span-5">
                <label class="form-label">Khoa</label>
                <select name="faculty" class="form-input">
                        <option value="">-- Chọn khoa --</option>
                        @foreach($faculties as $f)
                            <option value="{{ $f->id }}" {{ (string)$f->id === (string)$facultyId ? 'selected' : '' }}>
                                {{ $f->name ?? '' }} ({{ $f->short_name ?? '' }})
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

    @if($facultyId && $semesterId && $faculty)
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-stat-card label="Tổng tiền khoa" :value="number_format($stats->total_amount) . ' ₫'" icon="fa-coins" color="primary" />
            <x-stat-card label="Số giáo viên" :value="$stats->teacher_count" icon="fa-users" color="emerald" />
            <x-stat-card label="Trung bình/GV" :value="number_format($stats->average_payment) . ' ₫'" icon="fa-chart-line" color="violet" />
            <x-stat-card label="Số lớp học" :value="$stats->class_count" icon="fa-school" color="amber" />
        </div>

        <!-- Faculty Info and Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Faculty Info Column -->
            <div class="lg:col-span-1">
                <div class="app-card overflow-hidden">
                    <div class="border-b border-slate-100 px-5 py-4">
                        <h2 class="text-base font-semibold text-slate-900">Thông tin khoa</h2>
                    </div>
                    <div class="p-6 text-center">
                        <div class="mb-4">
                            <div class="relative inline-block">
                                <div class="flex h-24 w-24 items-center justify-center rounded-full bg-primary-600 text-3xl font-bold text-white">
                                    {{ substr($faculty->short_name ?? '', 0, 2) }}
                                </div>
                            </div>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-1">{{ $faculty->name ?? 'N/A' }}</h4>
                        <p class="text-gray-600 mb-3">{{ $faculty->short_name ?? 'N/A' }}</p>
                        
                        <hr class="my-4">
                        
                        <div class="text-left space-y-3">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-5 w-5 text-blue-500">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-2 text-sm text-gray-700">
                                    {{ $faculty->teachers()->count() ?? 0 }} giáo viên
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-5 w-5 text-blue-500">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                                <div class="ml-2 text-sm text-gray-700">
                                    {{ $faculty->courses()->count() ?? 0 }} môn học
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-5 w-5 text-blue-500">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <div class="ml-2 text-sm text-gray-700">
                                    {{ $stats->class_count ?? 0 }} lớp học trong kỳ này
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Top Teachers -->
                <div class="app-card overflow-hidden mt-6">
                    <div class="border-b border-slate-100 px-5 py-4">
                        <h2 class="text-base font-semibold text-slate-900">Top 5 giáo viên</h2>
                    </div>
                    <div class="p-6">
                        @if($topTeachers->isNotEmpty())
                            <div class="space-y-4">
                                @foreach($topTeachers as $index => $teacher)
                                    @if($teacher->teacher)
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-800 font-bold">
                                                {{ $index + 1 }}
                                            </div>
                                            <div class="ml-4 flex-1">
                                                <div class="flex items-center justify-between">
                                                    <h4 class="text-sm font-medium text-gray-900">{{ $teacher->teacher->name ?? 'N/A' }}</h4>
                                                    <span class="text-sm font-semibold text-blue-600">
                                                        {{ number_format($teacher->total_amount) }} VNĐ
                                                    </span>
                                                </div>
                                                <div class="mt-1 flex items-center justify-between text-xs text-gray-500">
                                                    <span>{{ $teacher->teacher->degree->short_name ?? 'N/A' }}</span>
                                                    <span>{{ $teacher->total_sessions }} buổi</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
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
                                        <p class="text-sm text-yellow-700">Không có dữ liệu giáo viên</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Charts Column -->
            <div class="lg:col-span-2">
                <!-- Department Distribution Chart -->
                <div class="app-card overflow-hidden mb-6">
                    <div class="border-b border-slate-100 px-5 py-4">
                        <h2 class="text-base font-semibold text-slate-900">Phân bổ theo bộ môn</h2>
                    </div>
                    <div class="p-6">
                        @if($departmentData->isNotEmpty())
                            <div class="h-64">
                                <canvas id="departmentChart"></canvas>
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
                                        <p class="text-sm text-yellow-700">Không có dữ liệu phân bổ theo bộ môn</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Payment Trend Chart -->
                <div class="app-card overflow-hidden">
                    <div class="border-b border-slate-100 px-5 py-4">
                        <h2 class="text-base font-semibold text-slate-900">Xu hướng thanh toán</h2>
                    </div>
                    <div class="p-6">
                        @if($trendData->isNotEmpty())
                            <div class="h-64">
                                <canvas id="trendChart"></canvas>
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
                                        <p class="text-sm text-yellow-700">Không có dữ liệu xu hướng thanh toán</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
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
        @if($facultyId && $semesterId)
            // Department Distribution Chart
            @if($departmentData->isNotEmpty())
                const deptCtx = document.getElementById('departmentChart').getContext('2d');
                const departmentChart = new Chart(deptCtx, {
                    type: 'pie',
                    data: {
                        labels: {!! json_encode($departmentData->pluck('class.course.faculty.name')) !!},
                        datasets: [{
                            data: {!! json_encode($departmentData->pluck('total_amount')) !!},
                            backgroundColor: [
                                '#3B82F6', '#10B981', '#F59E0B', '#6366F1', '#EC4899',
                                '#14B8A6', '#F97316', '#8B5CF6', '#EF4444', '#06B6D4'
                            ],
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
            @endif
            
            // Payment Trend Chart
            @if($trendData->isNotEmpty())
                const trendCtx = document.getElementById('trendChart').getContext('2d');
                const trendChart = new Chart(trendCtx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($trendData->pluck('semester_name')) !!},
                        datasets: [{
                            label: 'Tổng tiền (VNĐ)',
                            data: {!! json_encode($trendData->pluck('total_amount')) !!},
                            backgroundColor: 'rgba(59, 130, 246, 0.05)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 2,
                            tension: 0.1,
                            fill: true
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
        @endif
    });
</script>
@endpush
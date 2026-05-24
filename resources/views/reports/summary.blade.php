@extends('layouts.app')

@section('title', 'Báo cáo tổng hợp toàn trường')
@section('breadcrumb', 'Báo cáo tổng hợp toàn trường')

@section('content')
<div class="space-y-6">
    <x-filter-panel>
        <form method="GET" class="flex flex-col gap-4 sm:flex-row sm:items-end">
            <div class="flex-1">
                <label class="form-label">Học kỳ</label>
                <select name="semester" class="form-input">
                    @foreach($semesters as $semester)
                        <option value="{{ $semester->id }}" {{ $semesterId == $semester->id ? 'selected' : '' }}>
                            {{ $semester->name }} ({{ $semester->academicYear->name }})
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-primary shrink-0">
                <i class="fas fa-search"></i> Xem báo cáo
            </button>
        </form>
    </x-filter-panel>

    @if($semesterId)
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-stat-card label="Tổng tiền toàn trường" :value="number_format($stats->total_amount) . ' ₫'" icon="fa-coins" color="primary" />
            <x-stat-card label="Số giáo viên" :value="$stats->teacher_count" icon="fa-chalkboard-user" color="emerald" />
            <x-stat-card label="Số lớp học" :value="$stats->class_count" icon="fa-school" color="violet" />
            <x-stat-card label="Số khoa" :value="$stats->faculty_count" icon="fa-building-columns" color="amber" />
        </div>

        <!-- Charts and Data -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Faculty Comparison Chart -->
            <div class="app-card overflow-hidden">
        <div class="border-b border-slate-100 px-5 py-4">
            <h2 class="text-base font-semibold text-slate-900">So sánh giữa các khoa</h2>
                </div>
                <div class="p-6">
                    @if($facultyComparison->isNotEmpty())
                        <div class="h-80">
                            <canvas id="facultyComparisonChart"></canvas>
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
                                    <p class="text-sm text-yellow-700">Không có dữ liệu so sánh giữa các khoa</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Teacher Scatter Plot -->
            <div class="app-card overflow-hidden">
        <div class="border-b border-slate-100 px-5 py-4">
            <h2 class="text-base font-semibold text-slate-900">Phân bổ giáo viên</h2>
                </div>
                <div class="p-6">
                    @if($teacherScatterData->isNotEmpty())
                        <div class="h-80">
                            <canvas id="teacherScatterChart"></canvas>
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
                                    <p class="text-sm text-yellow-700">Không có dữ liệu phân bổ giáo viên</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Faculty Ranking Table -->
        <div class="app-card overflow-hidden mb-6">
        <div class="border-b border-slate-100 px-5 py-4">
            <h2 class="text-base font-semibold text-slate-900">Xếp hạng các khoa</h2>
            </div>
            <div class="p-6">
                @if($facultyRanking->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khoa</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng tiền</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Số GV</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Số lớp</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Trung bình/GV</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($facultyRanking as $index => $faculty)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-800 font-bold">
                                                {{ substr($faculty->short_name, 0, 2) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $faculty->faculty_name }}</div>
                                                <div class="text-sm text-gray-500">{{ $faculty->short_name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-600 text-right">
                                        {{ number_format($faculty->total_amount) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                        {{ $faculty->teacher_count }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                        {{ $faculty->class_count }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                        {{ number_format($faculty->average_per_teacher) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
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
                                <p class="text-sm text-blue-700">Không có dữ liệu xếp hạng các khoa</p>
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
                    Vui lòng chọn học kỳ để xem báo cáo tổng hợp
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
        @if($semesterId)
            // Faculty Comparison Chart
            @if($facultyComparison->isNotEmpty())
                const facultyCtx = document.getElementById('facultyComparisonChart').getContext('2d');
                const facultyChart = new Chart(facultyCtx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($facultyComparison->pluck('short_name')) !!},
                        datasets: [
                            {
                                label: 'Tổng tiền (VNĐ)',
                                data: {!! json_encode($facultyComparison->pluck('total_amount')) !!},
                                backgroundColor: 'rgba(59, 130, 246, 0.7)',
                                borderColor: 'rgba(59, 130, 246, 1)',
                                borderWidth: 1,
                                borderRadius: 4,
                                yAxisID: 'y'
                            },
                            {
                                label: 'Số giáo viên',
                                data: {!! json_encode($facultyComparison->pluck('teacher_count')) !!},
                                backgroundColor: 'rgba(16, 185, 129, 0.7)',
                                borderColor: 'rgba(16, 185, 129, 1)',
                                borderWidth: 1,
                                borderRadius: 4,
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
                                title: {
                                    display: true,
                                    text: 'Tổng tiền (VNĐ)'
                                },
                                ticks: {
                                    callback: function(value) {
                                        return value.toLocaleString();
                                    }
                                }
                            },
                            y1: {
                                type: 'linear',
                                display: true,
                                position: 'right',
                                title: {
                                    display: true,
                                    text: 'Số giáo viên'
                                },
                                grid: {
                                    drawOnChartArea: false
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label.includes('tiền')) {
                                            return `${label}: ${context.parsed.y.toLocaleString()} VNĐ`;
                                        } else {
                                            return `${label}: ${context.parsed.y}`;
                                        }
                                    }
                                }
                            }
                        }
                    }
                });
            @endif
            
            // Teacher Scatter Chart
            @if($teacherScatterData->isNotEmpty())
                const scatterCtx = document.getElementById('teacherScatterChart').getContext('2d');
                
                // Prepare data by faculty for different colors
                const facultyGroups = {!! json_encode($teacherScatterData->groupBy('faculty_name')) !!};
                const datasets = [];
                const colors = [
                    '#3B82F6', '#10B981', '#F59E0B', '#6366F1', '#EC4899',
                    '#14B8A6', '#F97316', '#8B5CF6', '#EF4444', '#06B6D4'
                ];
                
                let colorIndex = 0;
                for (const [facultyName, teachers] of Object.entries(facultyGroups)) {
                    datasets.push({
                        label: facultyName,
                        data: teachers.map(teacher => ({
                            x: teacher.class_count,
                            y: teacher.total_amount,
                            teacher: teacher.teacher_name
                        })),
                        backgroundColor: colors[colorIndex % colors.length],
                        borderColor: colors[colorIndex % colors.length],
                        borderWidth: 1,
                        pointRadius: 8,
                        pointHoverRadius: 10
                    });
                    colorIndex++;
                }
                
                const scatterChart = new Chart(scatterCtx, {
                    type: 'scatter',
                    data: {
                        datasets: datasets
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Số lớp dạy'
                                },
                                ticks: {
                                    stepSize: 1
                                }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: 'Tổng tiền (VNĐ)'
                                },
                                ticks: {
                                    callback: function(value) {
                                        return value.toLocaleString();
                                    }
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return [
                                            `Giáo viên: ${context.raw.teacher}`,
                                            `Số lớp: ${context.parsed.x}`,
                                            `Tổng tiền: ${context.parsed.y.toLocaleString()} VNĐ`
                                        ];
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
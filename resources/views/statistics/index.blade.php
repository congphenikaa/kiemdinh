@extends('layouts.app')

@section('title', 'Thống kê')

@section('breadcrumb', 'Trang chủ / Thống kê')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="mb-8">
        <h3 class="text-2xl font-semibold text-gray-800 mb-4">Thống kê giáo viên</h3>
        
        <!-- Filter Options -->
        <div class="bg-white p-6 rounded-xl shadow-md mb-8">
            <form action="{{ route('statistics.index') }}" method="GET" id="filter-form" class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label for="faculty_id" class="block text-sm font-medium text-gray-700 mb-1">Khoa:</label>
                    <select id="faculty_id" name="faculty_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="all">Tất cả</option>
                        @foreach($faculties as $faculty)
                            <option value="{{ $faculty->id }}" {{ request('faculty_id') == $faculty->id ? 'selected' : '' }}>
                                {{ $faculty->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex-1 min-w-[200px]">
                    <label for="degree_id" class="block text-sm font-medium text-gray-700 mb-1">Bằng cấp:</label>
                    <select id="degree_id" name="degree_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="all">Tất cả</option>
                        @foreach($degrees as $degree)
                            <option value="{{ $degree->id }}" {{ request('degree_id') == $degree->id ? 'selected' : '' }}>
                                {{ $degree->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <button type="submit" class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-filter mr-2"></i> Áp dụng
                </button>
            </form>
        </div>
    </div>

    <!-- Stats Container -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chart Section -->
        <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-md">
            <h4 class="text-lg font-semibold text-gray-800 mb-4">Thống kê giáo viên theo khoa</h4>
            <div class="h-80">
                <canvas id="faculty-chart" data-faculty-data="{{ json_encode($facultyData) }}"></canvas>
            </div>
        </div>

        <!-- Summary Section -->
        <div class="space-y-4">
            <!-- Total Teachers Card -->
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white p-6 rounded-xl shadow-md">
                <div class="flex items-center gap-4">
                    <div class="text-3xl opacity-90">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <h4 class="text-lg font-medium mb-1">Tổng số giáo viên</h4>
                        <p class="text-3xl font-bold">{{ $teachers->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Faculty Stats Card -->
            <div class="bg-white p-6 rounded-xl shadow-md">
                <h4 class="text-lg font-semibold text-gray-800 mb-3">Theo khoa</h4>
                <ul class="divide-y divide-gray-200">
                    @foreach($facultyStats as $faculty => $count)
                        <li class="py-3 flex justify-between items-center">
                            <span class="text-gray-700 font-medium">{{ $faculty }}</span>
                            <span class="px-3 py-1 bg-gray-100 text-gray-800 font-semibold rounded-full text-sm">
                                {{ $count }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Degree Stats Card -->
            <div class="bg-white p-6 rounded-xl shadow-md">
                <h4 class="text-lg font-semibold text-gray-800 mb-3">Theo bằng cấp</h4>
                <ul class="divide-y divide-gray-200">
                    @foreach($degreeStats as $degree => $count)
                        <li class="py-3 flex justify-between items-center">
                            <span class="text-gray-700 font-medium">{{ $degree }}</span>
                            <span class="px-3 py-1 bg-gray-100 text-gray-800 font-semibold rounded-full text-sm">
                                {{ $count }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const facultyChart = document.getElementById('faculty-chart');
    if (facultyChart) {
        const ctx = facultyChart.getContext('2d');
        const facultyData = JSON.parse(facultyChart.dataset.facultyData || '{}');
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: Object.keys(facultyData),
                datasets: [{
                    label: 'Số giáo viên theo khoa',
                    data: Object.values(facultyData),
                    backgroundColor: 'rgba(66, 153, 225, 0.5)',
                    borderColor: 'rgba(66, 153, 225, 1)',
                    borderWidth: 1,
                    borderRadius: 6,
                    barThickness: 40
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
@endsection
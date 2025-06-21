@extends('layouts.app')

@section('title', 'Báo cáo tiền dạy theo khoa')
@section('breadcrumb', 'Báo cáo tiền dạy theo khoa')

@section('content')
<div class="container-fluid">
    <!-- Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-white border-bottom-0">
            <h6 class="m-0 font-weight-bold text-primary">Lọc dữ liệu</h6>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-5">
                    <label class="form-label fw-bold">Học kỳ</label>
                    <select name="semester" class="form-select select2">
                        @foreach($semesters as $semester)
                            <option value="{{ $semester->id }}" {{ $semesterId == $semester->id ? 'selected' : '' }}>
                                {{ $semester->name }} ({{ $semester->academicYear->name }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label fw-bold">Khoa</label>
                    <select name="faculty" class="form-select select2">
                        <option value="">-- Chọn khoa --</option>
                        @foreach($faculties as $faculty)
                            <option value="{{ $faculty->id }}" {{ $facultyId == $faculty->id ? 'selected' : '' }}>
                                {{ $faculty->name }} ({{ $faculty->short_name }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i> Xem báo cáo
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if($facultyId && $semesterId)
        <!-- Faculty Info -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white border-bottom-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Thông tin khoa {{ $faculty->name }}
                    </h6>
                    <span class="badge bg-primary">
                        {{ $faculty->short_name }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <p>{{ $faculty->description ?? 'Không có mô tả' }}</p>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="flex-shrink-0 me-3">
                                        <i class="fas fa-users fa-2x text-gray-300"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs fw-bold text-gray-600">Tổng giáo viên</div>
                                        <div class="h5 fw-bold mb-0">
                                            {{ $faculty->teachers->count() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="flex-shrink-0 me-3">
                                        <i class="fas fa-book fa-2x text-gray-300"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs fw-bold text-gray-600">Môn học</div>
                                        <div class="h5 fw-bold mb-0">
                                            {{ $faculty->courses->count() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="flex-shrink-0 me-3">
                                        <i class="fas fa-chalkboard fa-2x text-gray-300"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs fw-bold text-gray-600">Lớp học</div>
                                        <div class="h5 fw-bold mb-0">
                                            {{ $faculty->courses->flatMap->classes->count() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light border-0 h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-university fa-3x text-primary mb-3"></i>
                                <h5 class="fw-bold">{{ $faculty->name }}</h5>
                                <p class="text-muted small mb-0">
                                    {{ $faculty->short_name }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($hasData)
            <!-- Summary Stats -->
            <div class="row mb-4">
                <div class="col-md-4 mb-4">
                    <div class="card border-start-primary h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                                        Tổng tiền
                                    </div>
                                    <div class="h5 fw-bold mb-0">
                                        {{ number_format($stats->total_amount) }} VNĐ
                                    </div>
                                    <div class="mt-2 text-xs text-muted">
                                        <i class="fas fa-info-circle me-1"></i> Tổng thanh toán học kỳ
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card border-start-success h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="text-xs fw-bold text-success text-uppercase mb-1">
                                        Giáo viên
                                    </div>
                                    <div class="h5 fw-bold mb-0">
                                        {{ $stats->teacher_count }}
                                    </div>
                                    <div class="mt-2 text-xs text-muted">
                                        <i class="fas fa-info-circle me-1"></i> Số GV được thanh toán
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <i class="fas fa-users fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card border-start-info h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="text-xs fw-bold text-info text-uppercase mb-1">
                                        Trung bình
                                    </div>
                                    <div class="h5 fw-bold mb-0">
                                        {{ number_format($stats->average_payment) }} VNĐ
                                    </div>
                                    <div class="mt-2 text-xs text-muted">
                                        <i class="fas fa-info-circle me-1"></i> Trung bình mỗi giáo viên
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <i class="fas fa-calculator fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row mb-4">
                <!-- Department Distribution -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header py-3 bg-white border-bottom-0">
                            <h6 class="m-0 font-weight-bold text-primary">
                                Phân bổ theo bộ môn
                            </h6>
                        </div>
                        <div class="card-body">
                            @if($paymentData->isNotEmpty())
                                <div class="chart-pie">
                                    <canvas id="departmentChart" height="250"></canvas>
                                </div>
                            @else
                                <div class="alert alert-warning mb-0">
                                    <i class="fas fa-exclamation-circle me-1"></i> Không có dữ liệu phân bổ theo bộ môn
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Monthly Trends -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header py-3 bg-white border-bottom-0">
                            <h6 class="m-0 font-weight-bold text-primary">
                                Xu hướng thanh toán
                            </h6>
                        </div>
                        <div class="card-body">
                            @if($monthlyTrends->isNotEmpty())
                                <div class="chart-area">
                                    <canvas id="trendChart" height="250"></canvas>
                                </div>
                            @else
                                <div class="alert alert-warning mb-0">
                                    <i class="fas fa-exclamation-circle me-1"></i> Không có dữ liệu xu hướng
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Teachers -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-white border-bottom-0">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Top 5 giáo viên có tổng tiền cao nhất
                    </h6>
                </div>
                <div class="card-body">
                    @if($topTeachers->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Giáo viên</th>
                                        <th>Mã GV</th>
                                        <th class="text-end">Tổng tiền (VNĐ)</th>
                                        <th class="text-center">Chi tiết</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topTeachers as $index => $teacher)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $teacher->name }}</td>
                                            <td>{{ $teacher->code }}</td>
                                            <td class="text-end fw-bold text-primary">
                                                {{ number_format($teacher->total_amount) }}
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('reports.teacher-payments', [
                                                    'semester' => $semesterId,
                                                    'teacher' => $teacher->id
                                                ]) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-1"></i> Không có dữ liệu giáo viên
                        </div>
                    @endif
                </div>
            </div>
        @else
            <!-- No Data -->
            <div class="card shadow">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-chart-pie fa-3x text-gray-400"></i>
                    </div>
                    <h5 class="fw-bold text-gray-800 mb-3">Không có dữ liệu thanh toán</h5>
                    <p class="text-muted mb-4">
                        Khoa này không có giáo viên nào được thanh toán trong học kỳ đã chọn
                    </p>
                </div>
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-university fa-3x text-gray-400"></i>
                </div>
                <h5 class="fw-bold text-gray-800 mb-3">Chưa chọn dữ liệu báo cáo</h5>
                <p class="text-muted mb-4">
                    Vui lòng chọn học kỳ và khoa để xem báo cáo chi tiết
                </p>
                <button class="btn btn-primary" disabled>
                    <i class="fas fa-chart-bar me-1"></i> Xem báo cáo
                </button>
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .chart-area, .chart-pie {
        position: relative;
        height: 250px;
        width: 100%;
    }
    .select2-container--default .select2-selection--single {
        height: calc(2.25rem + 2px);
        padding-top: 0.375rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: calc(2.25rem + 2px);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: 'Chọn khoa',
            allowClear: true
        });
        
        @if($facultyId && $semesterId && $hasData)
            // Department Distribution Chart
            @if($paymentData->isNotEmpty())
                var ctx = document.getElementById('departmentChart').getContext('2d');
                var departmentChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: {!! json_encode($paymentData->pluck('department')) !!},
                        datasets: [{
                            data: {!! json_encode($paymentData->pluck('total_amount')) !!},
                            backgroundColor: [
                                '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', 
                                '#e74a3b', '#858796', '#5a5c69', '#3a3b45'
                            ],
                            hoverBackgroundColor: [
                                '#2e59d9', '#17a673', '#2c9faf', '#dda20a', 
                                '#be2617', '#6b6d7d', '#42444e', '#2a2b32'
                            ],
                            hoverBorderColor: "rgba(234, 236, 244, 1)",
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.label + ': ' + 
                                            context.raw.toLocaleString() + ' VNĐ';
                                    }
                                }
                            },
                            legend: {
                                position: 'right',
                                labels: {
                                    usePointStyle: true,
                                    padding: 20
                                }
                            }
                        }
                    }
                });
            @endif
            
            // Monthly Trends Chart
            @if($monthlyTrends->isNotEmpty())
                var ctx = document.getElementById('trendChart').getContext('2d');
                var trendChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($monthlyTrends->pluck('semester_name')) !!},
                        datasets: [{
                            label: 'Tổng tiền (VNĐ)',
                            data: {!! json_encode($monthlyTrends->pluck('total_amount')) !!},
                            backgroundColor: 'rgba(78, 115, 223, 0.05)',
                            borderColor: 'rgba(78, 115, 223, 1)',
                            pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                            pointBorderColor: '#fff',
                            pointHoverBackgroundColor: '#fff',
                            pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                            fill: true
                        }]
                    },
                    options: {
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
@extends('layouts.app')

@section('title', 'Báo cáo tiền dạy giáo viên')
@section('breadcrumb', 'Báo cáo tiền dạy giáo viên')

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
                    <label class="form-label fw-bold">Giáo viên</label>
                    <select name="teacher" class="form-select select2">
                        <option value="">-- Chọn giáo viên --</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ $teacherId == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->code }} - {{ $teacher->name }} ({{ $teacher->faculty->short_name }})
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

    @if($teacherId && $semesterId)
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
                                    Số lớp dạy
                                </div>
                                <div class="h5 fw-bold mb-0">
                                    {{ $stats->total_classes }}
                                </div>
                                <div class="mt-2 text-xs text-muted">
                                    <i class="fas fa-info-circle me-1"></i> Tổng số lớp giảng dạy
                                </div>
                            </div>
                            <div class="ms-3">
                                <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
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
                                    Số buổi dạy
                                </div>
                                <div class="h5 fw-bold mb-0">
                                    {{ $stats->total_sessions }}
                                </div>
                                <div class="mt-2 text-xs text-muted">
                                    <i class="fas fa-info-circle me-1"></i> Tổng số buổi đã giảng dạy
                                </div>
                            </div>
                            <div class="ms-3">
                                <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Teacher Info and Chart -->
        <div class="row mb-4">
            <!-- Chart Column -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header py-3 bg-white border-bottom-0">
                        <h6 class="m-0 font-weight-bold text-primary">
                            Biểu đồ thanh toán theo tháng
                        </h6>
                    </div>
                    <div class="card-body">
                        @if($monthlyData->isNotEmpty())
                            <div class="chart-area">
                                <canvas id="monthlyChart" height="250"></canvas>
                            </div>
                        @else
                            <div class="alert alert-warning mb-0">
                                <i class="fas fa-exclamation-circle me-1"></i> Không có dữ liệu thanh toán theo tháng
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Teacher Info Column -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header py-3 bg-white border-bottom-0">
                        <h6 class="m-0 font-weight-bold text-primary">Thông tin giáo viên</h6>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <div class="avatar avatar-xl position-relative">
                                <img src="{{ asset('images/default-avatar.jpg') }}" 
                                     class="rounded-circle border" 
                                     width="100" 
                                     height="100"
                                     alt="Avatar">
                                @if($teacher->is_active)
                                    <span class="position-absolute bottom-0 end-0 bg-success rounded-circle p-1 border-2 border-white"></span>
                                @endif
                            </div>
                        </div>
                        <h5 class="fw-bold mb-1">{{ $teacher->name }}</h5>
                        <p class="text-muted mb-2">{{ $teacher->code }}</p>
                        
                        <div class="d-flex justify-content-center mb-3">
                            <span class="badge bg-primary me-2">
                                {{ $teacher->degree->short_name ?? 'N/A' }}
                            </span>
                            <span class="badge bg-secondary">
                                {{ $teacher->faculty->short_name }}
                            </span>
                        </div>
                        
                        <hr class="my-3">
                        
                        <div class="text-start">
                            <div class="d-flex mb-2">
                                <div class="flex-shrink-0 me-2">
                                    <i class="fas fa-envelope text-primary"></i>
                                </div>
                                <div>
                                    {{ $teacher->email ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="d-flex mb-2">
                                <div class="flex-shrink-0 me-2">
                                    <i class="fas fa-phone text-primary"></i>
                                </div>
                                <div>
                                    {{ $teacher->phone ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-2">
                                    <i class="fas fa-user-tag text-primary"></i>
                                </div>
                                <div>
                                    <span class="badge {{ $teacher->is_active ? 'bg-success' : 'bg-secondary' }}">
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
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white border-bottom-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Chi tiết thanh toán</h6>
                    <div class="text-muted small">
                        Tổng: {{ $payments->count() }} bản ghi
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if($payments->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Mã lớp</th>
                                    <th>Môn học</th>
                                    <th class="text-end">Số buổi</th>
                                    <th class="text-end">Hệ số</th>
                                    <th class="text-end">Đơn giá</th>
                                    <th class="text-end">Thành tiền</th>
                                    <th class="text-center">Ngày thanh toán</th>
                                    <th class="text-center">Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payments as $index => $payment)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>
                                            <span class="badge bg-primary">
                                                {{ $payment->class->class_code }}
                                            </span>
                                        </td>
                                        <td>{{ $payment->class->course->name }}</td>
                                        <td class="text-end">{{ $payment->total_sessions }}</td>
                                        <td class="text-end">
                                            {{ $payment->degree_coefficient }} x {{ $payment->size_coefficient }}
                                        </td>
                                        <td class="text-end">{{ number_format($payment->base_rate) }}</td>
                                        <td class="text-end fw-bold text-primary">
                                            {{ number_format($payment->total_amount) }}
                                        </td>
                                        <td class="text-center">
                                            {{ $payment->payment_date->format('d/m/Y') }}
                                        </td>
                                        <td class="text-center">
                                            <span class="badge {{ $payment->status === 'completed' ? 'bg-success' : 'bg-warning' }}">
                                                {{ $payment->status === 'completed' ? 'Đã thanh toán' : 'Chờ xử lý' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-active">
                                    <th colspan="6" class="text-end">Tổng cộng:</th>
                                    <th class="text-end">{{ number_format($payments->sum('total_amount')) }}</th>
                                    <th colspan="2"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-1"></i> Không có dữ liệu thanh toán nào trong học kỳ này
                    </div>
                @endif
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-chart-pie fa-3x text-gray-400"></i>
                </div>
                <h5 class="fw-bold text-gray-800 mb-3">Chưa chọn dữ liệu báo cáo</h5>
                <p class="text-muted mb-4">
                    Vui lòng chọn học kỳ và giáo viên để xem báo cáo chi tiết
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
    .avatar {
        position: relative;
        display: inline-block;
    }
    .avatar img {
        object-fit: cover;
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
            placeholder: 'Chọn giáo viên',
            allowClear: true
        });
        
        @if($teacherId && $semesterId && $monthlyData->isNotEmpty())
            // Monthly Payment Chart
            var ctx = document.getElementById('monthlyChart').getContext('2d');
            var monthlyChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($monthlyData->pluck('month')->map(function($month) {
                        return 'Tháng ' + $month;
                    })) !!},
                    datasets: [{
                        label: 'Tổng tiền (VNĐ)',
                        data: {!! json_encode($monthlyData->pluck('total')) !!},
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
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
@extends('layouts.app')

@section('title', 'Thống kê giáo viên')

@section('breadcrumb', 'Trang chủ / Thống kê')

@section('content')
<div class="content-section">
    <div class="content-header">
        <h3>Thống kê giáo viên</h3>
        <div class="filter-options">
            <form action="{{ route('teachers.statistics') }}" method="GET" id="filter-form">
                <div class="filter-group">
                    <label for="faculty_id">Khoa:</label>
                    <select id="faculty_id" name="faculty_id">
                        <option value="all">Tất cả</option>
                        @foreach($faculties as $faculty)
                            <option value="{{ $faculty->id }}" {{ request('faculty_id') == $faculty->id ? 'selected' : '' }}>
                                {{ $faculty->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label for="year">Năm:</label>
                    <select id="year" name="year">
                        <option value="all">Tất cả</option>
                        @php
                            $currentYear = date('Y');
                            for($i = 0; $i < 5; $i++) {
                                $year = $currentYear - $i;
                                $selected = request('year') == $year ? 'selected' : '';
                                echo "<option value='$year' $selected>$year</option>";
                            }
                        @endphp
                    </select>
                </div>
                <div class="filter-group">
                    <label for="degree_id">Bằng cấp:</label>
                    <select id="degree_id" name="degree_id">
                        <option value="all">Tất cả</option>
                        @foreach($degrees as $degree)
                            <option value="{{ $degree->id }}" {{ request('degree_id') == $degree->id ? 'selected' : '' }}>
                                {{ $degree->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter"></i> Áp dụng
                </button>
            </form>
        </div>
    </div>
    <div class="stats-container">
        <div class="stats-chart">
            <canvas id="teacher-chart" data-faculty-data="{{ json_encode($facultyData) }}"></canvas>
        </div>
        <div class="stats-summary">
            <div class="summary-card">
                <h4>Tổng số giáo viên</h4>
                <p>{{ $teachers->count() }}</p>
            </div>
            <div class="summary-card">
                <h4>Theo khoa</h4>
                <ul>
                    @foreach($facultyStats as $faculty => $count)
                        <li><span>{{ $faculty }}:</span> {{ $count }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="summary-card">
                <h4>Theo bằng cấp</h4>
                <ul>
                    @foreach($degreeStats as $degree => $count)
                        <li><span>{{ $degree }}:</span> {{ $count }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize chart with data from server
        const teacherChart = document.getElementById('teacher-chart');
        if (teacherChart) {
            const ctx = teacherChart.getContext('2d');
            const facultyData = JSON.parse(teacherChart.dataset.facultyData || '{}');
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: Object.keys(facultyData),
                    datasets: [{
                        label: 'Số giáo viên theo khoa',
                        data: Object.values(facultyData),
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    });
</script>
@endpush 
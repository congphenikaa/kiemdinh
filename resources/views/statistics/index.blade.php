@extends('layouts.app')

@section('title', 'Thống kê')

@section('breadcrumb', 'Trang chủ / Thống kê')

@section('content')
<div class="content-section">
    <div class="content-header">
        <h3>Thống kê giáo viên</h3>
        <div class="filter-options">
            <form action="{{ route('statistics.index') }}" method="GET" id="filter-form">
                <div class="filter-group">
                    <label for="faculty_id">Khoa:</label>
                    <select id="faculty_id" name="faculty_id" class="form-select">
                        <option value="all">Tất cả</option>
                        @foreach($faculties as $faculty)
                            <option value="{{ $faculty->id }}" {{ request('faculty_id') == $faculty->id ? 'selected' : '' }}>
                                {{ $faculty->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label for="degree_id">Bằng cấp:</label>
                    <select id="degree_id" name="degree_id" class="form-select">
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
        <!-- Biểu đồ thống kê theo khoa -->
        <div class="stats-chart">
            <h4>Thống kê giáo viên theo khoa</h4>
            <canvas id="faculty-chart" data-faculty-data="{{ json_encode($facultyData) }}"></canvas>
        </div>

        <!-- Thống kê chi tiết -->
        <div class="stats-summary">
            <div class="summary-card total-teachers">
                <div class="card-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="card-content">
                    <h4>Tổng số giáo viên</h4>
                    <p class="total-count">{{ $teachers->count() }}</p>
                </div>
            </div>
            <div class="summary-card">
                <h4>Theo khoa</h4>
                <ul class="stats-list">
                    @foreach($facultyStats as $faculty => $count)
                        <li>
                            <span class="faculty-name">{{ $faculty }}</span>
                            <span class="count">{{ $count }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="summary-card">
                <h4>Theo bằng cấp</h4>
                <ul class="stats-list">
                    @foreach($degreeStats as $degree => $count)
                        <li>
                            <span class="degree-name">{{ $degree }}</span>
                            <span class="count">{{ $count }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
.content-section {
    padding: 2rem;
    background: #f8f9fa;
    min-height: calc(100vh - 60px);
}

.content-header {
    margin-bottom: 2rem;
}

.content-header h3 {
    color: #2c3e50;
    font-size: 1.8rem;
    margin-bottom: 1.5rem;
    font-weight: 600;
}

.filter-options {
    background: #fff;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    margin-bottom: 2rem;
}

.filter-group {
    display: inline-block;
    margin-right: 1.5rem;
    margin-bottom: 0.5rem;
}

.filter-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: #2c3e50;
}

.form-select {
    padding: 0.75rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    min-width: 200px;
    font-size: 0.95rem;
    color: #4a5568;
    transition: all 0.2s;
}

.form-select:focus {
    border-color: #4299e1;
    box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
    outline: none;
}

.stats-container {
    display: grid;
    grid-template-columns: 1.5fr 1fr;
    gap: 2rem;
}

.stats-chart {
    background: #fff;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    height: 400px;
}

.stats-chart h4 {
    margin: 0 0 1.5rem 0;
    color: #2c3e50;
    font-size: 1.2rem;
    font-weight: 600;
}

.stats-summary {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.5rem;
}

.summary-card {
    background: #fff;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
}

.summary-card h4 {
    margin: 0 0 1rem 0;
    color: #2c3e50;
    font-size: 1.1rem;
    font-weight: 600;
}

.total-teachers {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    background: linear-gradient(135deg, #4299e1, #667eea);
    color: #fff;
}

.card-icon {
    font-size: 2.5rem;
    opacity: 0.9;
}

.card-content h4 {
    color: #fff;
    margin-bottom: 0.5rem;
}

.total-count {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0;
    color: #fff;
}

.stats-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.stats-list li {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #e2e8f0;
}

.stats-list li:last-child {
    border-bottom: none;
}

.faculty-name, .degree-name {
    color: #4a5568;
    font-weight: 500;
}

.count {
    font-weight: 600;
    color: #2c3e50;
    background: #edf2f7;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.9rem;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 0.95rem;
}

.btn-primary {
    background: #4299e1;
    color: #fff;
}

.btn-primary:hover {
    background: #3182ce;
    transform: translateY(-1px);
}

@media (max-width: 1024px) {
    .stats-container {
        grid-template-columns: 1fr;
    }
    
    .stats-chart {
        height: 350px;
    }
}

@media (max-width: 768px) {
    .content-section {
        padding: 1rem;
    }
    
    .filter-group {
        display: block;
        margin-right: 0;
        margin-bottom: 1rem;
    }
    
    .form-select {
        width: 100%;
    }
    
    .stats-summary {
        grid-template-columns: 1fr;
    }
}
</style>

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
@extends('layouts.app')

@section('title', 'Dashboard')

@section('breadcrumb', 'Trang chủ')

@section('content')
<div class="content-section">
    <div class="cards">
        <div class="card">
            <div class="card-icon bg-blue">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="card-info">
                <h3>Bằng cấp</h3>
                <p>{{ $degrees->count() }}</p>
            </div>
        </div>
        <div class="card">
            <div class="card-icon bg-green">
                <i class="fas fa-university"></i>
            </div>
            <div class="card-info">
                <h3>Khoa</h3>
                <p>{{ $faculties->count() }}</p>
            </div>
        </div>
        <div class="card">
            <div class="card-icon bg-orange">
                <i class="fas fa-chalkboard-teacher"></i>
                </div>
            <div class="card-info">
                <h3>Giáo viên</h3>
                <p>{{ $teachers->count() }}</p>
            </div>
        </div>
    </div>
    <div class="recent-added">
        <h3>Thêm gần đây</h3>
        <div class="recent-table">
            <table>
                <thead>
                    <tr>
                        <th>Mã số</th>
                        <th>Họ tên</th>
                        <th>Khoa</th>
                        <th>Bằng cấp</th>
                        <th>Ngày thêm</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentTeachers as $teacher)
                    <tr>
                        <td>{{ $teacher->code }}</td>
                        <td>{{ $teacher->name }}</td>
                        <td>{{ $teacher->faculty->short_name }}</td>
                        <td>{{ $teacher->degree->short_name }}</td>
                        <td>{{ $teacher->created_at->format('d/m/Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

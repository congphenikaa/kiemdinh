<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Báo cáo thống kê lớp {{ $class->class_code }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .title { font-size: 18px; font-weight: bold; }
        .subtitle { font-size: 14px; margin-bottom: 10px; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .info-table td { padding: 5px; border: 1px solid #ddd; }
        .info-table .label { font-weight: bold; width: 30%; background-color: #f5f5f5; }
        .stat-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .stat-table th, .stat-table td { padding: 8px; border: 1px solid #ddd; text-align: center; }
        .stat-table th { background-color: #f5f5f5; }
        .footer { margin-top: 30px; text-align: right; font-style: italic; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">BÁO CÁO THỐNG KÊ LỚP HỌC</div>
        <div class="subtitle">Mã lớp: {{ $class->class_code }}</div>
        <div class="subtitle">Ngày xuất báo cáo: {{ now()->format('d/m/Y') }}</div>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Tên môn học:</td>
            <td>{{ $class->course->name }}</td>
        </tr>
        <tr>
            <td class="label">Học kỳ:</td>
            <td>{{ $class->semester->name }} ({{ $class->semester->academicYear->name }})</td>
        </tr>
        <tr>
            <td class="label">Khoa:</td>
            <td>{{ $class->course->faculty->name }}</td>
        </tr>
        <tr>
            <td class="label">Số buổi học:</td>
            <td>{{ $class->course->total_sessions }}</td>
        </tr>
    </table>

    <h3>THỐNG KÊ CHUNG</h3>
    <table class="stat-table">
        <tr>
            <th>Số buổi đã dạy</th>
            <th>Tỷ lệ điểm danh TB</th>
        </tr>
        <tr>
            <td>{{ $class->statistics->total_sessions_taught ?? 0 }}</td>
            <td>{{ $class->statistics->average_attendance ?? 0 }}%</td>
        </tr>
    </table>

    <h3>GIÁO VIÊN PHỤ TRÁCH</h3>
    <table class="stat-table">
        <tr>
            <th>STT</th>
            <th>Tên giáo viên</th>
            <th>Học vị</th>
            <th>Khoa</th>
        </tr>
        @foreach($class->teachers as $index => $teacher)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $teacher->name }}</td>
            <td>{{ $teacher->degree->short_name }}</td>
            <td>{{ $teacher->faculty->short_name }}</td>
        </tr>
        @endforeach
    </table>

    <div class="footer">
        <p>Người lập báo cáo</p>
        <p>........................................</p>
    </div>
</body>
</html>
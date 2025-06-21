<?php

namespace App\Http\Controllers;

use App\Models\CourseClass;
use App\Models\Semester;
use App\Models\ClassStatistic;
use App\Models\TeacherPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassReportController extends Controller
{
    // 2.6.1 - Thống kê tổng quan
    public function index(Request $request)
    {
        // Lọc theo học kỳ
        $semesterId = $request->input('semester');
        $status = $request->input('status', 'all');

        // Query cơ bản
        $query = CourseClass::with([
                'course',
                'semester',
                'teacher',
                'statistics'
            ])
            ->when($semesterId, function($q) use ($semesterId) {
                return $q->where('semester_id', $semesterId);
            })
            ->when($status !== 'all', function($q) use ($status) {
                return $q->where('status', $status);
            });

        // Thống kê chính
        $stats = [
            'totalClasses' => $query->count(),
            'totalStudents' => $query->sum('current_students'),
            'avgAttendance' => ClassStatistic::when($semesterId, function($q) use ($semesterId) {
                    return $q->whereHas('classes', function($q) use ($semesterId) {
                        $q->where('semester_id', $semesterId);
                    });
                })
                ->avg('average_attendance'),
            'cancelledSessions' => ClassStatistic::when($semesterId, function($q) use ($semesterId) {
                    return $q->whereHas('classes', function($q) use ($semesterId) {
                        $q->where('semester_id', $semesterId);
                    });
                })
                ->sum('total_sessions_cancelled')
        ];

        // Thống kê theo trạng thái
        $byStatus = CourseClass::select('status', DB::raw('COUNT(*) as count'))
            ->when($semesterId, function($q) use ($semesterId) {
                return $q->where('semester_id', $semesterId);
            })
            ->groupBy('status')
            ->get()
            ->mapWithKeys(function($item) {
                return [$item->status => $item->count];
            });

        // Thống kê theo khoa
        $byFaculty = CourseClass::select(
                'faculties.name',
                DB::raw('COUNT(classes.id) as class_count'),
                DB::raw('SUM(classes.current_students) as student_count')
            )
            ->join('courses', 'classes.course_id', '=', 'courses.id')
            ->join('departments', 'courses.department_id', '=', 'departments.id')
            ->join('faculties', 'departments.faculty_id', '=', 'faculties.id')
            ->when($semesterId, function($q) use ($semesterId) {
                return $q->where('classes.semester_id', $semesterId);
            })
            ->groupBy('faculties.name')
            ->get();

        // Thống kê thanh toán
        $paymentStats = TeacherPayment::select(
                DB::raw('SUM(total_amount) as total_paid'),
                DB::raw('AVG(total_amount) as avg_payment')
            )
            ->when($semesterId, function($q) use ($semesterId) {
                return $q->where('semester_id', $semesterId);
            })
            ->first();

        // Dữ liệu filter
        $semesters = Semester::orderBy('start_date', 'desc')->get();

        return view('class-management.class-reports.index', array_merge($stats, [
            'byStatus' => $byStatus,
            'byFaculty' => $byFaculty,
            'paymentStats' => $paymentStats,
            'semesters' => $semesters,
            'currentFilters' => [
                'semester' => $semesterId,
                'status' => $status
            ]
        ]));
    }

    // 2.6.2 - Chi tiết thống kê lớp
    public function show(CourseClass $class)
    {
        $class->load([
            'course.department.faculty',
            'teacher.degree',
            'schedules' => function($q) {
                $q->orderBy('date')->orderBy('start_time');
            },
            'statistics',
            'payments' => function($q) {
                $q->orderBy('payment_date', 'desc');
            }
        ]);

        // Tính % các loại buổi học
        $sessionTypes = $class->schedules->groupBy('session_type')
            ->map(function($sessions) {
                return [
                    'count' => $sessions->count(),
                    'percentage' => round(($sessions->count() / $sessions->sum('count')) * 100, 1)
                ];
            });

        return view('class-management.class-reports.show', [
            'class' => $class,
            'sessionTypes' => $sessionTypes
        ]);
    }
}
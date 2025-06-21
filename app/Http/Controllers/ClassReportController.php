<?php

namespace App\Http\Controllers;

use App\Models\Clazz;
use App\Models\Semester;
use App\Models\ClassStatistics;
use App\Models\TeacherPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassReportController extends Controller
{
    /**
     * Hiển thị thống kê tổng quan các lớp học
     */
    public function index(Request $request)
    {
        // Lọc theo học kỳ và trạng thái
        $semesterId = $request->input('semester');
        $status = $request->input('status', 'all');

        $query = Clazz::with(['course', 'semester.academicYear', 'teachers', 'statistics'])
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
            'avgAttendance' => ClassStatistics::when($semesterId, function($q) use ($semesterId) {
                    return $q->whereHas('class', function($q) use ($semesterId) {
                        $q->where('semester_id', $semesterId);
                    });
                })
                ->avg('average_attendance'),
            'completedSessions' => ClassStatistics::when($semesterId, function($q) use ($semesterId) {
                    return $q->whereHas('class', function($q) use ($semesterId) {
                        $q->where('semester_id', $semesterId);
                    });
                })
                ->sum('total_sessions_taught')
        ];

        // Thống kê theo trạng thái lớp
        $byStatus = Clazz::select('status', DB::raw('COUNT(*) as count'))
            ->when($semesterId, function($q) use ($semesterId) {
                return $q->where('semester_id', $semesterId);
            })
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // Thống kê theo khoa
        $byFaculty = Clazz::select(
                'faculties.name',
                DB::raw('COUNT(classes.id) as class_count'),
                DB::raw('SUM(classes.current_students) as student_count')
            )
            ->join('courses', 'classes.course_id', '=', 'courses.id')
            ->join('faculties', 'courses.faculty_id', '=', 'faculties.id')
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

        $semesters = Semester::with('academicYear')
            ->orderBy('start_date', 'desc')
            ->get();


        return view('class-management.reports.index', array_merge($stats, [
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

    /**
     * Hiển thị chi tiết thống kê lớp học
     */
    public function show(Clazz $class)
    {
        $class->load([
            'course.faculty',
            'teachers.degree',
            'schedules' => function($q) {
                $q->orderBy('date')->orderBy('start_time');
            },
            'statistics',
            'payments' => function($q) {
                $q->orderBy('payment_date', 'desc');
            }
        ]);

        // Tính toán các loại buổi học
        $totalSessions = $class->course->total_sessions;
        $taughtSessions = $class->schedules->where('is_taught', true)->count();
        $cancelledSessions = $totalSessions - $taughtSessions;

        return view('class-management.reports.show', [
            'class' => $class,
            'taughtSessions' => $taughtSessions,
            'cancelledSessions' => $cancelledSessions,
            'attendanceRate' => $class->statistics->average_attendance ?? 0
        ]);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Clazz;
use App\Models\Semester;
use App\Models\TeacherPayment;
use App\Services\ClassStatisticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassReportController extends Controller
{
    public function __construct(
        private readonly ClassStatisticsService $classStatisticsService
    ) {}

    /**
     * Hiển thị thống kê tổng quan các lớp học
     */
    public function index(Request $request)
    {
        $semesterId = $request->input('semester');
        $status = $request->input('status', 'all');

        $query = Clazz::with(['course', 'semester.academicYear', 'teachers', 'statistics'])
            ->when($semesterId, function ($q) use ($semesterId) {
                return $q->where('semester_id', $semesterId);
            })
            ->when($status !== 'all', function ($q) use ($status) {
                return $q->where('status', $status);
            });

        $scheduleStats = $this->classStatisticsService->aggregateForClassQuery($query);

        $stats = [
            'totalClasses' => (clone $query)->count(),
            'totalStudents' => (clone $query)->sum('current_students'),
            'avgAttendance' => $scheduleStats['avgAttendance'],
            'completedSessions' => $scheduleStats['completedSessions'],
        ];

        $byStatus = Clazz::select('status', DB::raw('COUNT(*) as count'))
            ->when($semesterId, function ($q) use ($semesterId) {
                return $q->where('semester_id', $semesterId);
            })
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        $byFaculty = Clazz::select(
            'faculties.name',
            DB::raw('COUNT(classes.id) as class_count'),
            DB::raw('SUM(classes.current_students) as student_count')
        )
            ->join('courses', 'classes.course_id', '=', 'courses.id')
            ->join('faculties', 'courses.faculty_id', '=', 'faculties.id')
            ->when($semesterId, function ($q) use ($semesterId) {
                return $q->where('classes.semester_id', $semesterId);
            })
            ->groupBy('faculties.name')
            ->get();

        $paymentStats = TeacherPayment::select(
            DB::raw('SUM(total_amount) as total_paid'),
            DB::raw('AVG(total_amount) as avg_payment')
        )
            ->when($semesterId, function ($q) use ($semesterId) {
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
                'status' => $status,
            ],
        ]));
    }

    /**
     * Hiển thị chi tiết thống kê lớp học
     */
    public function show(Clazz $class)
    {
        $class->load([
            'course.faculty',
            'semester.academicYear',
            'teachers.degree',
            'schedules' => function ($q) {
                $q->orderBy('date')->orderBy('start_time');
            },
            'statistics',
            'payments' => function ($q) {
                $q->orderBy('payment_date', 'desc');
            },
        ]);

        $statistics = $this->classStatisticsService->syncForClass($class);
        $class->setRelation('statistics', $statistics);

        $totalSessions = max(
            (int) ($class->course->total_sessions ?? 0),
            $class->schedules->count()
        );
        $taughtSessions = (int) $statistics->total_sessions_taught;
        $attendanceRate = (float) $statistics->average_attendance;

        $taughtPercent = $totalSessions > 0
            ? round(($taughtSessions / $totalSessions) * 100, 2)
            : 0;

        return view('class-management.reports.show', [
            'class' => $class,
            'totalSessions' => $totalSessions,
            'taughtSessions' => $taughtSessions,
            'cancelledSessions' => max(0, $totalSessions - $taughtSessions),
            'taughtPercent' => $taughtPercent,
            'attendanceRate' => $attendanceRate,
            'teacherStats' => collect(),
        ]);
    }
}

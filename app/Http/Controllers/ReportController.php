<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Faculty;
use App\Models\Semester;
use App\Models\TeacherPayment;
use App\Models\Clazz;
use App\Models\TeachingAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    // UC4.1 - Teacher Payment Report
    public function teacherPayments(Request $request)
    {
        $semesterId = $request->input('semester');
        $teacherId = $request->input('teacher');
        
        $semesters = Semester::with('academicYear')
            ->orderBy('start_date', 'desc')
            ->get();
            
        $teachers = Teacher::with(['faculty', 'degree'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        
        // Set default semester if not selected
        if (!$semesterId && $semesters->isNotEmpty()) {
            $semesterId = $semesters->first()->id;
        }
        
        $teacher = null;
        $stats = (object)[
            'total_amount' => 0,
            'total_sessions' => 0,
            'total_classes' => 0
        ];
        $payments = collect();
        $monthlyData = collect();
        $teachingAssignments = collect();
        
        if ($teacherId && $semesterId) {
            $teacher = Teacher::with(['faculty', 'degree'])->findOrFail($teacherId);
            
            // Get teaching assignments with class and course info
            $teachingAssignments = TeachingAssignment::with([
                'class' => function($query) use ($semesterId) {
                    $query->with(['course', 'semester', 'schedules' => function($q) {
                        $q->where('is_taught', true);
                    }])
                    ->where('semester_id', $semesterId);
                },
                'class.statistics'
            ])
            ->where('teacher_id', $teacherId)
            ->get()
            ->filter(function($assignment) {
                return $assignment->class !== null;
            });
            
            // Calculate statistics from teaching assignments
            $stats->total_classes = $teachingAssignments->count();
            $stats->total_sessions = $teachingAssignments->sum(function($assignment) {
                return $assignment->class->schedules->count();
            });
            
            // Get payment statistics
            $paymentStats = TeacherPayment::where('teacher_id', $teacherId)
                ->where('semester_id', $semesterId)
                ->select(
                    DB::raw('COALESCE(SUM(total_amount), 0) as total_amount'),
                    DB::raw('COALESCE(SUM(total_sessions), 0) as total_sessions'),
                    DB::raw('COUNT(DISTINCT class_id) as total_classes')
                )
                ->first();
                
            if ($paymentStats) {
                $stats->total_amount = $paymentStats->total_amount;
                // Use payment sessions if available, otherwise use schedules count
                $stats->total_sessions = $paymentStats->total_sessions ?: $stats->total_sessions;
            }
            
            // Get detailed payments with related data
            $payments = TeacherPayment::with([
                    'class' => function($query) {
                        $query->with('course');
                    },
                    'paymentBatch'
                ])
                ->where('teacher_id', $teacherId)
                ->where('semester_id', $semesterId)
                ->orderBy('payment_date', 'desc')
                ->get();
            
            // Get monthly payment data for chart
            $monthlyData = TeacherPayment::where('teacher_id', $teacherId)
                ->where('semester_id', $semesterId)
                ->whereNotNull('payment_date')
                ->select(
                    DB::raw('MONTH(payment_date) as month'),
                    DB::raw('SUM(total_amount) as total')
                )
                ->groupBy('month')
                ->orderBy('month')
                ->get();
        }
        
        return view('reports.teacher-payments', compact(
            'semesters', 'teachers', 'semesterId', 'teacherId',
            'teacher', 'stats', 'payments', 'monthlyData', 'teachingAssignments'
        ));
    }

    // Add this method to your ReportController
    public function facultyPayments(Request $request)
    {
        $semesterId = $request->input('semester');
        $facultyId = $request->input('faculty');
        
        $semesters = Semester::with('academicYear')
            ->orderBy('start_date', 'desc')
            ->get();
            
        $faculties = Faculty::orderBy('name')->get();
        
        // Set default semester if not selected
        if (!$semesterId && $semesters->isNotEmpty()) {
            $semesterId = $semesters->first()->id;
        }
        
        $faculty = null;
        $stats = (object)[
            'total_amount' => 0,
            'teacher_count' => 0,
            'average_payment' => 0,
            'class_count' => 0
        ];
        $payments = collect();
        $departmentData = collect();
        $topTeachers = collect();
        $trendData = collect();
        
        if ($facultyId && $semesterId) {
            $faculty = Faculty::findOrFail($facultyId);
            
            // Get payment statistics for the faculty
            $paymentStats = TeacherPayment::whereHas('teacher', function($q) use ($facultyId) {
                    $q->where('faculty_id', $facultyId);
                })
                ->where('semester_id', $semesterId)
                ->select(
                    DB::raw('COALESCE(SUM(total_amount), 0) as total_amount'),
                    DB::raw('COUNT(DISTINCT teacher_id) as teacher_count'),
                    DB::raw('COUNT(DISTINCT class_id) as class_count')
                )
                ->first();
                
            if ($paymentStats) {
                $stats->total_amount = $paymentStats->total_amount;
                $stats->teacher_count = $paymentStats->teacher_count;
                $stats->class_count = $paymentStats->class_count;
                $stats->average_payment = $paymentStats->teacher_count > 0 
                    ? $paymentStats->total_amount / $paymentStats->teacher_count 
                    : 0;
            }
            
            // Get payment distribution by department (course faculty)
            $departmentData = TeacherPayment::whereHas('teacher', function($q) use ($facultyId) {
                $q->where('faculty_id', $facultyId);
            })
            ->where('teacher_payments.semester_id', $semesterId)
            ->join('classes', 'teacher_payments.class_id', '=', 'classes.id')
            ->join('courses', 'classes.course_id', '=', 'courses.id')
            ->select(
                'courses.faculty_id',
                DB::raw('SUM(teacher_payments.total_amount) as total_amount'),
                DB::raw('COUNT(DISTINCT teacher_payments.teacher_id) as teacher_count')
            )
            ->groupBy('courses.faculty_id')
            ->with(['class.course.faculty']) // Sửa từ 'course.faculty' thành 'class.course.faculty'
            ->get();
            
            // Get top 5 teachers by payment amount
            $topTeachers = TeacherPayment::whereHas('teacher', function($q) use ($facultyId) {
                    $q->where('faculty_id', $facultyId);
                })
                ->where('semester_id', $semesterId)
                ->select(
                    'teacher_id',
                    DB::raw('SUM(total_amount) as total_amount'),
                    DB::raw('COUNT(DISTINCT class_id) as class_count'),
                    DB::raw('SUM(total_sessions) as total_sessions')
                )
                ->groupBy('teacher_id')
                ->orderBy('total_amount', 'desc')
                ->limit(5)
                ->with(['teacher.degree'])
                ->get();
            
            // Get payment trend for last 5 semesters
            $recentSemesterIds = Semester::orderBy('start_date', 'desc')
                ->limit(5)
                ->pluck('id')
                ->toArray();

            $trendData = TeacherPayment::whereHas('teacher', function($q) use ($facultyId) {
                    $q->where('faculty_id', $facultyId);
                })
                ->whereIn('semester_id', $recentSemesterIds)
                ->join('semesters', 'teacher_payments.semester_id', '=', 'semesters.id')
                ->select(
                    'semester_id',
                    'semesters.name as semester_name',
                    DB::raw('SUM(total_amount) as total_amount'),
                    DB::raw('COUNT(DISTINCT teacher_id) as teacher_count')
                )
                ->groupBy('semester_id', 'semesters.name')
                ->orderBy('semesters.start_date')
                ->get();
        }
        
        return view('reports.faculty-payments', compact(
            'semesters', 'faculties', 'semesterId', 'facultyId',
            'faculty', 'stats', 'departmentData', 'topTeachers', 'trendData'
        ));
    }

// Helper method to generate colors for departments




    // UC4.3 - Summary Report
    public function summary(Request $request)
    {
        $semesterId = $request->input('semester');
    
        $semesters = Semester::with('academicYear')
            ->orderBy('start_date', 'desc')
            ->get();
        
        // Set default semester if not selected
        if (!$semesterId && $semesters->isNotEmpty()) {
            $semesterId = $semesters->first()->id;
        }
        
        $stats = (object)[
            'total_amount' => 0,
            'teacher_count' => 0,
            'class_count' => 0,
            'faculty_count' => 0
        ];
        
        $facultyComparison = collect();
        $teacherScatterData = collect();
        $facultyRanking = collect();
        
        if ($semesterId) {
            // Get summary statistics
            $summaryStats = TeacherPayment::where('semester_id', $semesterId)
                ->select(
                    DB::raw('COALESCE(SUM(total_amount), 0) as total_amount'),
                    DB::raw('COUNT(DISTINCT teacher_id) as teacher_count'),
                    DB::raw('COUNT(DISTINCT class_id) as class_count')
                )
                ->first();
                
            if ($summaryStats) {
                $stats->total_amount = $summaryStats->total_amount;
                $stats->teacher_count = $summaryStats->teacher_count;
                $stats->class_count = $summaryStats->class_count;
                $stats->faculty_count = Faculty::has('teachers')->count();
            }
            
            // Faculty comparison data
            $facultyComparison = TeacherPayment::where('semester_id', $semesterId)
                ->join('teachers', 'teacher_payments.teacher_id', '=', 'teachers.id')
                ->join('faculties', 'teachers.faculty_id', '=', 'faculties.id')
                ->select(
                    'faculties.id',
                    'faculties.name as faculty_name',
                    'faculties.short_name',
                    DB::raw('SUM(total_amount) as total_amount'),
                    DB::raw('COUNT(DISTINCT teacher_payments.teacher_id) as teacher_count'),
                    DB::raw('COUNT(DISTINCT teacher_payments.class_id) as class_count')
                )
                ->groupBy('faculties.id', 'faculties.name', 'faculties.short_name')
                ->orderBy('total_amount', 'desc')
                ->get();
            
            // Teacher scatter plot data
            $teacherScatterData = TeacherPayment::where('semester_id', $semesterId)
                ->join('teachers', 'teacher_payments.teacher_id', '=', 'teachers.id')
                ->join('faculties', 'teachers.faculty_id', '=', 'faculties.id')
                ->select(
                    'teachers.id',
                    'teachers.name as teacher_name',
                    'faculties.short_name as faculty_name',
                    DB::raw('SUM(total_amount) as total_amount'),
                    DB::raw('COUNT(DISTINCT teacher_payments.class_id) as class_count')
                )
                ->groupBy('teachers.id', 'teachers.name', 'faculties.short_name')
                ->get();
            
            // Faculty ranking
            $facultyRanking = TeacherPayment::where('semester_id', $semesterId)
                ->join('teachers', 'teacher_payments.teacher_id', '=', 'teachers.id')
                ->join('faculties', 'teachers.faculty_id', '=', 'faculties.id')
                ->select(
                    'faculties.id',
                    'faculties.name as faculty_name',
                    'faculties.short_name',
                    DB::raw('SUM(total_amount) as total_amount'),
                    DB::raw('COUNT(DISTINCT teacher_payments.teacher_id) as teacher_count'),
                    DB::raw('COUNT(DISTINCT teacher_payments.class_id) as class_count'),
                    DB::raw('ROUND(SUM(total_amount) / COUNT(DISTINCT teacher_payments.teacher_id), 2) as average_per_teacher')
                )
                ->groupBy('faculties.id', 'faculties.name', 'faculties.short_name')
                ->orderBy('total_amount', 'desc')
                ->get();
        }
        
        return view('reports.summary', compact(
            'semesters', 'semesterId',
            'stats', 'facultyComparison', 
            'teacherScatterData', 'facultyRanking'
        ));
        
    }
}
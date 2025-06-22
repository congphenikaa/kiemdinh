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
    
    // Get all semesters for dropdown
    $semesters = Semester::with('academicYear')
        ->orderBy('start_date', 'desc')
        ->get();
        
    // Get all faculties for dropdown
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
    $trendData = collect();
    $topTeachers = collect();
    
    if ($facultyId && $semesterId) {
        $faculty = Faculty::findOrFail($facultyId);
        
        // Get all payments for faculty in semester
        $payments = TeacherPayment::with([
                'teacher.degree',
                'class.course',
                'paymentBatch'
            ])
            ->whereHas('teacher', function($query) use ($facultyId) {
                $query->where('faculty_id', $facultyId);
            })
            ->where('semester_id', $semesterId)
            ->get();
            
        // Calculate statistics
        if ($payments->isNotEmpty()) {
            $stats->total_amount = $payments->sum('total_amount');
            $stats->teacher_count = $payments->groupBy('teacher_id')->count();
            $stats->average_payment = $stats->teacher_count > 0 
                ? $stats->total_amount / $stats->teacher_count 
                : 0;
            $stats->class_count = $payments->groupBy('class_id')->count();
            
            // Get data for department pie chart (group by course faculty)
            $departmentData = $payments->groupBy('class.course.faculty_id')
                ->map(function($items, $facultyId) {
                    $faculty = Faculty::find($facultyId);
                    return [
                        'faculty' => $faculty ? $faculty->short_name : 'Khác',
                        'total' => $items->sum('total_amount'),
                        'color' => $this->getDepartmentColor($facultyId)
                    ];
                })
                ->sortByDesc('total')
                ->values();
                
            // Get top 5 teachers by total payment
            $topTeachers = $payments->groupBy('teacher_id')
                ->map(function($items, $teacherId) {
                    $teacher = $items->first()->teacher;
                    return [
                        'teacher' => $teacher,
                        'total_amount' => $items->sum('total_amount'),
                        'class_count' => $items->groupBy('class_id')->count()
                    ];
                })
                ->sortByDesc('total_amount')
                ->take(5);
                
            // Get trend data for last 5 semesters
            $trendData = TeacherPayment::whereHas('teacher', function($query) use ($facultyId) {
                    $query->where('faculty_id', $facultyId);
                })
                ->whereHas('semester', function($query) use ($semesterId) {
                    $currentSemester = Semester::find($semesterId);
                    $query->where('start_date', '<=', $currentSemester->start_date)
                        ->orderBy('start_date', 'desc');
                })
                ->with('semester')
                ->select(
                    'semester_id',
                    DB::raw('SUM(total_amount) as total_amount'),
                    DB::raw('COUNT(DISTINCT teacher_id) as teacher_count')
                )
                ->groupBy('semester_id')
                ->orderBy('semester_id', 'desc')
                ->limit(5)
                ->get()
                ->map(function($item) {
                    return [
                        'semester' => $item->semester->name,
                        'total_amount' => $item->total_amount,
                        'average_per_teacher' => $item->total_amount / max(1, $item->teacher_count)
                    ];
                })
                ->reverse()
                ->values();
        }
    }
    
    return view('reports.faculty-payments', compact(
        'semesters', 'faculties', 'semesterId', 'facultyId',
        'faculty', 'stats', 'payments', 'departmentData', 
        'trendData', 'topTeachers'
    ));
}

// Helper method to generate colors for departments
protected function getDepartmentColor($facultyId)
{
    $colors = [
        '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6',
        '#EC4899', '#14B8A6', '#F97316', '#64748B', '#06B6D4'
    ];
    
    return $colors[$facultyId % count($colors)];
}



    // UC4.3 - Summary Report
    public function summary(Request $request)
    {
        
    }
}
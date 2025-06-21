<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Faculty;
use App\Models\Semester;
use App\Models\TeacherPayment;
use App\Models\Clazz;
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
        $stats = null;
        $payments = collect();
        $monthlyData = collect();
        
        if ($teacherId && $semesterId) {
            $teacher = Teacher::with(['faculty', 'degree'])->findOrFail($teacherId);
            
            // Get payment statistics
            $stats = TeacherPayment::where('teacher_id', $teacherId)
                ->where('semester_id', $semesterId)
                ->select(
                    DB::raw('COALESCE(SUM(total_amount), 0) as total_amount'),
                    DB::raw('COALESCE(SUM(total_sessions), 0) as total_sessions'),
                    DB::raw('COUNT(DISTINCT class_id) as total_classes')
                )
                ->first();
            
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
            'teacher', 'stats', 'payments', 'monthlyData'
        ));
    }

    // UC4.2 - Faculty Payment Report
    public function facultyPayments(Request $request)
    {
        $semesterId = $request->input('semester');
        $facultyId = $request->input('faculty');
        
        $semesters = Semester::orderBy('start_date', 'desc')->get();
        $faculties = Faculty::orderBy('name')->get();
        
        if (!$semesterId && $semesters->isNotEmpty()) {
            $semesterId = $semesters->first()->id;
        }
        
        $faculty = null;
        $stats = null;
        $paymentData = collect();
        $monthlyTrends = collect();
        $topTeachers = collect();
        $hasData = false;
        
        if ($facultyId && $semesterId) {
            $faculty = Faculty::findOrFail($facultyId);
            
            // Faculty payment statistics
            $stats = DB::table('teacher_payments')
                ->join('teachers', 'teacher_payments.teacher_id', '=', 'teachers.id')
                ->where('teachers.faculty_id', $facultyId)
                ->where('teacher_payments.semester_id', $semesterId)
                ->select(
                    DB::raw('COALESCE(SUM(teacher_payments.total_amount), 0) as total_amount'),
                    DB::raw('COUNT(DISTINCT teacher_payments.teacher_id) as teacher_count'),
                    DB::raw('COALESCE(AVG(teacher_payments.total_amount), 0) as average_payment')
                )
                ->first();
                
            $hasData = $stats->total_amount > 0;
            
            if ($hasData) {
                // Payment distribution by department (if applicable)
                $paymentData = DB::table('teacher_payments')
                    ->join('teachers', 'teacher_payments.teacher_id', '=', 'teachers.id')
                    ->where('teachers.faculty_id', $facultyId)
                    ->where('teacher_payments.semester_id', $semesterId)
                    ->select(
                        'teachers.department',
                        DB::raw('SUM(teacher_payments.total_amount) as total_amount')
                    )
                    ->groupBy('teachers.department')
                    ->get();
                
                // Monthly trends for the last 3 semesters
                $monthlyTrends = DB::table('teacher_payments')
                    ->join('teachers', 'teacher_payments.teacher_id', '=', 'teachers.id')
                    ->join('semesters', 'teacher_payments.semester_id', '=', 'semesters.id')
                    ->where('teachers.faculty_id', $facultyId)
                    ->whereIn('semesters.id', function($query) use ($semesterId) {
                        $query->select('id')
                            ->from('semesters')
                            ->orderBy('start_date', 'desc')
                            ->limit(3);
                    })
                    ->select(
                        'semesters.name as semester_name',
                        DB::raw('SUM(teacher_payments.total_amount) as total_amount')
                    )
                    ->groupBy('semesters.id', 'semesters.name')
                    ->orderBy('semesters.start_date')
                    ->get();
                
                // Top 5 teachers
                $topTeachers = DB::table('teacher_payments')
                    ->join('teachers', 'teacher_payments.teacher_id', '=', 'teachers.id')
                    ->where('teachers.faculty_id', $facultyId)
                    ->where('teacher_payments.semester_id', $semesterId)
                    ->select(
                        'teachers.id',
                        'teachers.name',
                        'teachers.code',
                        DB::raw('SUM(teacher_payments.total_amount) as total_amount')
                    )
                    ->groupBy('teachers.id', 'teachers.name', 'teachers.code')
                    ->orderByDesc('total_amount')
                    ->limit(5)
                    ->get();
            }
        }
        
        return view('reports.faculty-payments', compact(
            'semesters', 'faculties', 'semesterId', 'facultyId',
            'faculty', 'stats', 'paymentData', 'monthlyTrends', 
            'topTeachers', 'hasData'
        ));
    }

    // UC4.3 - Summary Report
    public function summary(Request $request)
    {
        $semesterId = $request->input('semester');
        
        $semesters = Semester::orderBy('start_date', 'desc')->get();
        
        if (!$semesterId && $semesters->isNotEmpty()) {
            $semesterId = $semesters->first()->id;
        }
        
        // General statistics
        $stats = [
            'total_teachers' => Teacher::where('is_active', true)->count(),
            'total_classes' => $semesterId ? Clazz::where('semester_id', $semesterId)->count() : 0,
            'total_payments' => $semesterId ? TeacherPayment::where('semester_id', $semesterId)->sum('total_amount') : 0,
        ];
        
        // Faculty-wise payment distribution
        $facultyDistribution = Faculty::with(['teachers.payments' => function($query) use ($semesterId) {
                if ($semesterId) {
                    $query->where('semester_id', $semesterId);
                }
            }])
            ->get()
            ->map(function($faculty) {
                $total = $faculty->teachers->flatMap->payments->sum('total_amount');
                return [
                    'faculty' => $faculty,
                    'total_amount' => $total,
                    'teacher_count' => $faculty->teachers->count(),
                    'payment_per_teacher' => $faculty->teachers->count() > 0 ? $total / $faculty->teachers->count() : 0
                ];
            })
            ->sortByDesc('total_amount');
        
        // Class vs Payment scatter data
        $teacherScatterData = Teacher::with(['payments' => function($query) use ($semesterId) {
                if ($semesterId) {
                    $query->where('semester_id', $semesterId);
                }
            }])
            ->has('payments')
            ->get()
            ->map(function($teacher) {
                return [
                    'teacher' => $teacher->name,
                    'class_count' => $teacher->payments->unique('class_id')->count(),
                    'total_payment' => $teacher->payments->sum('total_amount')
                ];
            });
        
        return view('reports.summary', compact(
            'semesters', 'semesterId', 'stats', 
            'facultyDistribution', 'teacherScatterData'
        ));
    }
}
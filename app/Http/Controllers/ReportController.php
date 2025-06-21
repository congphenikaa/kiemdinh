<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Faculty;
use App\Models\Semester;
use App\Models\TeacherPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    // 4.1. Báo cáo tiền dạy giảng viên
    public function teacherPayments(Request $request)
    {
        $semesterId = $request->input('semester');
        $teacherId = $request->input('teacher');
        
        $query = TeacherPayment::with(['teacher', 'semester', 'classe.course'])
            ->when($semesterId, function($q) use ($semesterId) {
                return $q->where('semester_id', $semesterId);
            })
            ->when($teacherId, function($q) use ($teacherId) {
                return $q->where('teacher_id', $teacherId);
            });

        // Thống kê tổng hợp
        $stats = $query->select(
                DB::raw('SUM(total_amount) as total_paid'),
                DB::raw('AVG(total_amount) as avg_payment'),
                DB::raw('COUNT(*) as payment_count')
            )->first();

        // Danh sách thanh toán
        $payments = $query->orderBy('payment_date', 'desc')
            ->paginate(20);

        // Filter data
        $semesters = Semester::orderBy('start_date', 'desc')->get();
        $teachers = Teacher::where('is_active', true)->orderBy('name')->get();

        return view('reports.teacher-payments', compact(
            'payments', 'stats', 'semesters', 'teachers',
            'semesterId', 'teacherId'
        ));
    }

    // 4.2. Báo cáo tiền dạy theo khoa
    public function facultyPayments(Request $request)
    {
        $semesterId = $request->input('semester');
        
        $report = Faculty::with(['departments.teachers.payments' => function($q) use ($semesterId) {
                if ($semesterId) $q->where('semester_id', $semesterId);
            }])
            ->orderBy('name')
            ->get()
            ->map(function($faculty) {
                $total = 0;
                $teacherCount = 0;
                
                foreach ($faculty->departments as $department) {
                    foreach ($department->teachers as $teacher) {
                        $total += $teacher->payments->sum('total_amount');
                        $teacherCount += ($teacher->payments->count() > 0) ? 1 : 0;
                    }
                }
                
                return [
                    'faculty' => $faculty,
                    'total_payments' => $total,
                    'teacher_count' => $teacherCount,
                    'avg_payment' => ($teacherCount > 0) ? $total / $teacherCount : 0
                ];
            });

        $semesters = Semester::orderBy('start_date', 'desc')->get();

        return view('reports.faculty-payments', compact(
            'report', 'semesters', 'semesterId'
        ));
    }

    // 4.3. Báo cáo tổng hợp
    public function summary(Request $request)
    {
        $semesterId = $request->input('semester');
        
        $data = [
            // Thống kê giảng viên
            'teacherStats' => Teacher::selectRaw('
                    COUNT(*) as total,
                    SUM(CASE WHEN is_active THEN 1 ELSE 0 END) as active_count,
                    AVG(TIMESTAMPDIFF(YEAR, dob, NOW())) as avg_age
                ')->first(),
                
            // Thống kê thanh toán
            'paymentStats' => TeacherPayment::when($semesterId, function($q) use ($semesterId) {
                    return $q->where('semester_id', $semesterId);
                })
                ->selectRaw('
                    SUM(total_amount) as total_paid,
                    AVG(total_amount) as avg_payment,
                    COUNT(*) as payment_count
                ')->first(),
                
            // Thống kê lớp học
            'classStats' => Classe::when($semesterId, function($q) use ($semesterId) {
                    return $q->where('semester_id', $semesterId);
                })
                ->selectRaw('
                    COUNT(*) as total,
                    SUM(current_students) as total_students,
                    AVG(current_students) as avg_students
                ')->first(),
                
            // Top giảng viên
            'topTeachers' => TeacherPayment::with('teacher')
                ->when($semesterId, function($q) use ($semesterId) {
                    return $q->where('semester_id', $semesterId);
                })
                ->select('teacher_id', DB::raw('SUM(total_amount) as total'))
                ->groupBy('teacher_id')
                ->orderBy('total', 'desc')
                ->limit(5)
                ->get()
        ];

        $semesters = Semester::orderBy('start_date', 'desc')->get();

        return view('reports.summary', array_merge($data, [
            'semesters' => $semesters,
            'semesterId' => $semesterId
        ]));
    }
}
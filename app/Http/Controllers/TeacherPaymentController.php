<?php

namespace App\Http\Controllers;

use App\Models\TeacherPayment;
use App\Models\Teacher;
use App\Models\Semester;
use App\Models\PaymentConfig;
use App\Models\ClassSizeCoefficient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherPaymentController extends Controller
{
    public function index()
    {
        $payments = TeacherPayment::with(['teacher', 'classe', 'semester'])
            ->filter(request(['semester', 'teacher', 'status']))
            ->orderBy('payment_date', 'desc')
            ->paginate(20);

        $semesters = Semester::orderBy('start_date', 'desc')->get();
        $teachers = Teacher::where('is_active', true)->get();

        return view('payment.teacher-payments.index', compact('payments', 'semesters', 'teachers'));
    }

    public function calculate(Semester $semester)
    {
        // Lấy cấu hình lương
        $config = PaymentConfig::firstOrFail();
        
        // Lấy tất cả lớp trong học kỳ
        $classes = Classe::with(['schedules', 'teacher.degree'])
            ->where('semester_id', $semester->id)
            ->where('status', 'completed')
            ->get();

        DB::transaction(function() use ($classes, $config, $semester) {
            foreach ($classes as $class) {
                // Tính số buổi dạy thực tế
                $theorySessions = $class->schedules
                    ->where('session_type', 'theory')
                    ->where('is_taught', true)
                    ->count();

                $practiceSessions = $class->schedules
                    ->where('session_type', 'practice')
                    ->where('is_taught', true)
                    ->count();

                // Tính hệ số sĩ số
                $sizeCoefficient = ClassSizeCoefficient::getCoefficient(
                    $class->current_students,
                    $class->max_students
                );

                // Tính tổng lương
                $baseAmount = ($theorySessions * $config->base_salary_per_session)
                           + ($practiceSessions * $config->base_salary_per_session * $config->practice_session_rate);

                $totalAmount = $baseAmount 
                            * $class->teacher->degree->salary_coefficient
                            * $sizeCoefficient;

                // Tạo bản ghi thanh toán
                TeacherPayment::updateOrCreate(
                    [
                        'teacher_id' => $class->teacher_id,
                        'class_id' => $class->id,
                        'semester_id' => $semester->id
                    ],
                    [
                        'theory_sessions' => $theorySessions,
                        'practice_sessions' => $practiceSessions,
                        'degree_coefficient' => $class->teacher->degree->salary_coefficient,
                        'size_coefficient' => $sizeCoefficient,
                        'base_rate' => $config->base_salary_per_session,
                        'total_amount' => $totalAmount,
                        'status' => 'pending'
                    ]
                );
            }
        });

        return redirect()->route('teacher-payments.index', ['semester' => $semester->id])
            ->with('success', 'Tính lương thành công cho học kỳ ' . $semester->name);
    }

    public function update(Request $request, TeacherPayment $payment)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,paid,cancelled'],
            'payment_date' => ['required_if:status,paid', 'nullable', 'date']
        ]);

        $payment->update($validated);

        return redirect()->route('teacher-payments.index')
            ->with('success', 'Cập nhật trạng thái thanh toán thành công!');
    }
}
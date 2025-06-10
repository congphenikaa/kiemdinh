<?php

namespace App\Http\Controllers;

use App\Models\TeacherPayment;
use App\Models\Teacher;
use App\Models\Semester;
use App\Models\PaymentConfig;
use App\Models\ClassSizeCoefficient;
use App\Models\Clazz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherPaymentController extends Controller
{
    public function index()
    {
        $payments = TeacherPayment::with(['teacher', 'class', 'semester'])
            ->filter(request(['semester', 'teacher', 'status']))
            ->orderBy('payment_date', 'desc')
            ->paginate(20);

        $semesters = Semester::orderBy('start_date', 'desc')->get();
        $teachers = Teacher::where('is_active', true)->get();

        return view('payment.teacher-payments.index', compact('payments', 'semesters', 'teachers'));
    }

    public function calculate(Semester $semester)
    {
        $config = PaymentConfig::first();
        if (!$config) {
            return redirect()->back()->with('error', 'Cấu hình lương chưa được thiết lập!');
        }

        $classes = Clazz::with(['schedules', 'teacher.degree'])
            ->where('semester_id', $semester->id)
            ->where('status', 'completed')
            ->get();

        if ($classes->isEmpty()) {
            return redirect()->back()->with('error', 'Không có lớp nào đủ điều kiện để tính lương!');
        }

        DB::beginTransaction();
        try {
            foreach ($classes as $class) {
                if (!$class->teacher || !$class->teacher->degree) {
                    // Bỏ qua nếu giáo viên hoặc bằng cấp không tồn tại
                    continue;
                }

                $theorySessions = $class->schedules
                    ->where('session_type', 'theory')
                    ->where('is_taught', true)
                    ->count();

                $practiceSessions = $class->schedules
                    ->where('session_type', 'practice')
                    ->where('is_taught', true)
                    ->count();

                // Kiểm tra số tiết hợp lệ
                if ($theorySessions + $practiceSessions === 0) {
                    continue;
                }

                $sizeCoefficient = ClassSizeCoefficient::getCoefficient(
                    $class->current_students,
                    $class->max_students
                );

                $baseAmount = ($theorySessions * $config->base_salary_per_session) +
                              ($practiceSessions * $config->base_salary_per_session * $config->practice_session_rate);

                $totalAmount = $baseAmount *
                               $class->teacher->degree->salary_coefficient *
                               $sizeCoefficient;

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

            DB::commit();
            return redirect()->route('teacher-payments.index', ['semester' => $semester->id])
                ->with('success', 'Tính lương thành công cho học kỳ ' . $semester->name);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Lỗi khi tính lương: ' . $e->getMessage());
        }
    }

    public function update(Request $request, TeacherPayment $payment)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,paid,cancelled'],
            'payment_date' => ['required_if:status,paid', 'nullable', 'date']
        ]);

        // Kiểm tra nếu muốn chuyển sang "paid" thì bắt buộc có ngày thanh toán
        if ($validated['status'] === 'paid' && empty($validated['payment_date'])) {
            return redirect()->back()->with('error', 'Vui lòng nhập ngày thanh toán khi cập nhật trạng thái đã thanh toán.');
        }

        $payment->update($validated);

        return redirect()->route('teacher-payments.index')
            ->with('success', 'Cập nhật trạng thái thanh toán thành công!');
    }
}

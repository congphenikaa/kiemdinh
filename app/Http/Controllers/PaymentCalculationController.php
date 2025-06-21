<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use App\Models\TeachingAssignment;
use App\Models\PaymentConfig;
use App\Models\ClassSizeCoefficient;
use Illuminate\Http\Request;

class PaymentCalculationController extends Controller
{
    public function index()
    {
        $semesters = Semester::with(['academicYear'])
            ->orderBy('start_date', 'desc')
            ->paginate(10);
            
        return view('payment.calculations.index', compact('semesters'));
    }

    public function calculate(Semester $semester)
    {
        // Validate semester exists
        if (!$semester) {
            return redirect()->back()
                ->with('error', 'Kỳ học không tồn tại!');
        }

        // Get all teaching assignments with required relationships
        $assignments = TeachingAssignment::with([
            'teacher.degree', 
            'teacher.faculty',
            'class' => function($query) {
                $query->with([
                    'course', 
                    'semester.academicYear', 
                    'schedules' // Lấy tất cả các buổi học đã tạo, không chỉ buổi đã dạy
                ]);
            }
        ])
        ->whereHas('class', function($query) use ($semester) {
            $query->where('semester_id', $semester->id)
                ->where('status', 'open');
        })
        ->whereHas('teacher', function($query) {
            $query->where('is_active', true);
        })
        ->get();

        if ($assignments->isEmpty()) {
            return redirect()->back()
                ->with('error', 'Không có phân công giảng dạy nào trong kỳ học này với lớp có trạng thái "open"!');
        }

        // Get payment config
        $paymentConfig = PaymentConfig::where('academic_year_id', $semester->academic_year_id)->first();
        if (!$paymentConfig) {
            return redirect()->back()
                ->with('error', 'Chưa có cấu hình thanh toán cho năm học này!');
        }

        // Get size coefficients
        $sizeCoefficients = ClassSizeCoefficient::where('academic_year_id', $semester->academic_year_id)
            ->orderBy('min_students')
            ->get();

        if ($sizeCoefficients->isEmpty()) {
            return redirect()->back()
                ->with('error', 'Chưa có cấu hình hệ số sĩ số lớp cho năm học này!');
        }

        // Calculate payments
        $paymentData = [];
        foreach ($assignments as $assignment) {
            // Skip if essential data is missing
            if (!$assignment->teacher || !$assignment->class) {
                continue;
            }

            // Lấy tổng số buổi học đã tạo (không quan tâm đã dạy hay chưa)
            $totalSessions = $assignment->class->schedules->count();

            if ($totalSessions <= 0) {
                continue;
            }

            // Get size coefficient
            $currentStudents = $assignment->class->current_students ?? 0;
            $sizeCoefficient = $this->getSizeCoefficient($sizeCoefficients, $currentStudents);
            
            // Get degree coefficient with fallback
            $degreeCoefficient = optional($assignment->teacher->degree)->salary_coefficient ?? 1.0;
            
            // Get course coefficient (hệ số học phần)
            $courseCredit = optional($assignment->class->course)->credit_hours ?? 1;
            $courseCoefficient = $this->calculateCourseCoefficient($courseCredit);

            // Tính toán theo công thức mới:
            // Số tiết thực tế * (hệ_số_học_phần + hệ_số_lớp) * hệ_số_giáo_viên * tiền_dạy_một_tiết
            $baseRate = $paymentConfig->base_salary_per_session;
            $totalAmount = $totalSessions * ($courseCoefficient + $sizeCoefficient) * $degreeCoefficient * $baseRate;

            $paymentData[] = [
                'teacher_id' => $assignment->teacher->id,
                'teacher_name' => $assignment->teacher->name,
                'teacher_code' => $assignment->teacher->code,
                'class_id' => $assignment->class->id,
                'class_code' => $assignment->class->class_code,
                'course_name' => optional($assignment->class->course)->name ?? 'N/A',
                'total_sessions' => $totalSessions,
                'degree_coefficient' => $degreeCoefficient,
                'size_coefficient' => $sizeCoefficient,
                'course_coefficient' => $courseCoefficient,
                'base_rate' => $baseRate,
                'total_amount' => $totalAmount,
                'current_students' => $currentStudents,
                'course_credit' => $courseCredit,
                'combined_coefficient' => ($courseCoefficient + $sizeCoefficient) // Thêm hệ số tổng để kiểm tra
            ];
        }

        if (empty($paymentData)) {
            return redirect()->back()
                ->with('error', 'Không có dữ liệu thanh toán nào được tính toán cho các lớp có trạng thái "open"!');
        }

        // Group by teacher
        $groupedPayments = collect($paymentData)->groupBy('teacher_id')->map(function($items) {
            return [
                'teacher' => [
                    'id' => $items->first()['teacher_id'],
                    'name' => $items->first()['teacher_name'],
                    'code' => $items->first()['teacher_code']
                ],
                'classes' => $items,
                'total_sessions' => $items->sum('total_sessions'),
                'total_amount' => $items->sum('total_amount'),
            ];
        });

        // Store calculation results in session
        session([
            'payment_calculation_' . $semester->id => [
                'data' => $paymentData,
                'grouped' => $groupedPayments,
                'total_amount' => collect($paymentData)->sum('total_amount'),
                'config' => $paymentConfig,
                'semester' => $semester
            ]
        ]);

        return view('payment.calculations.result', compact(
            'semester',
            'groupedPayments',
            'paymentConfig',
            'sizeCoefficients',
            'paymentData'
        ));
    }

    protected function getSizeCoefficient($coefficients, $studentCount)
    {
        $default = 1.0;
        foreach ($coefficients as $coef) {
            if ($studentCount >= $coef->min_students && $studentCount <= $coef->max_students) {
                return $coef->coefficient;
            }
        }
        return $default;
    }

    protected function calculateCourseCoefficient($creditHours)
    {
        // Logic tính hệ số học phần theo số tín chỉ
        return match((int)$creditHours) {
            1 => 1.0,
            2 => 1.2,
            3 => 1.5,
            4 => 2.0,
            default => 1.0
        };
    }
}
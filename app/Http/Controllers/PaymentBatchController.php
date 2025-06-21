<?php

namespace App\Http\Controllers;

use App\Models\PaymentBatch;
use App\Models\Semester;
use App\Models\TeacherPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentBatchController extends Controller
{
    public function index()
    {
        $batches = PaymentBatch::with(['semester.academicYear'])
            ->orderBy('processed_date', 'desc')
            ->paginate(10);

        return view('payment.batches.index', compact('batches'));
    }

    public function create(Semester $semester)
    {
        if (!$semester->exists) {
            return redirect()->route('payment-calculations.index')
                ->with('error', 'Vui lòng chọn kỳ học trước khi tạo đợt thanh toán');
        }
        
        $calculation = session('payment_calculation_' . $semester->id);
        
        if (!$calculation) {
            return redirect()->route('payment-calculations.calculate', $semester->id)
                ->with('error', 'Vui lòng tính toán thanh toán trước khi tạo đợt thanh toán');
        }

        // Calculate total classes
        $totalClasses = collect($calculation['grouped'])->sum(function($item) {
            return count($item['classes']);
        });

        return view('payment.batches.create', [
            'semester' => $semester,
            'groupedPayments' => $calculation['grouped'],
            'totalAmount' => $calculation['total_amount'],
            'totalClasses' => $totalClasses,
            'paymentConfig' => $calculation['config']
        ]);
    }

    public function store(Request $request, Semester $semester)
    {
        $validated = $request->validate([
            'batch_name' => 'required|string|max:255',
            'notes' => 'nullable|string|max:500',
            'payment_date' => 'required|date'
        ]);

        $calculation = session('payment_calculation_' . $semester->id);
        
        if (!$calculation) {
            return redirect()->route('payment-calculations.calculate', $semester->id)
                ->with('error', 'Dữ liệu tính toán không còn hợp lệ. Vui lòng tính toán lại');
        }

        try {
            DB::beginTransaction();

            $batch = PaymentBatch::create([
                'name' => $validated['batch_name'],
                'semester_id' => $semester->id,
                'processed_date' => $validated['payment_date'],
                'total_amount' => $calculation['total_amount'],
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null
            ]);

            foreach ($calculation['data'] as $payment) {
                TeacherPayment::create([
                    'teacher_id' => $payment['teacher_id'],
                    'class_id' => $payment['class_id'],
                    'payment_batch_id' => $batch->id,
                    'semester_id' => $semester->id,
                    'total_sessions' => $payment['total_sessions'],
                    'degree_coefficient' => $payment['degree_coefficient'],
                    'size_coefficient' => $payment['size_coefficient'],
                    'course_coefficient' => $payment['course_coefficient'],
                    'base_rate' => $payment['base_rate'],
                    'total_amount' => $payment['total_amount'],
                    'status' => 'pending',
                    'payment_date' => $validated['payment_date']
                ]);
            }

            DB::commit();

            session()->forget('payment_calculation_' . $semester->id);

            return redirect()->route('payment-batches.show', $batch->id)
                ->with('success', 'Tạo đợt thanh toán thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Lỗi khi tạo đợt thanh toán: ' . $e->getMessage());
        }
    }

    public function show(PaymentBatch $paymentBatch)
    {
        $paymentBatch->load([
            'semester.academicYear',
            'payments' => function($query) {
                $query->with([
                    'teacher.degree',
                    'class.course',
                    'class.semester'
                ])->orderBy('teacher_id');
            }
        ]);

        $summary = [
            'total_payments' => $paymentBatch->payments->count(),
            'total_amount' => $paymentBatch->payments->sum('total_amount'),
            'paid_count' => $paymentBatch->payments->where('status', 'paid')->count(),
            'paid_amount' => $paymentBatch->payments->where('status', 'paid')->sum('total_amount'),
            'pending_count' => $paymentBatch->payments->where('status', 'pending')->count(),
            'pending_amount' => $paymentBatch->payments->where('status', 'pending')->sum('total_amount'),
            'cancelled_count' => $paymentBatch->payments->where('status', 'cancelled')->count(),
            'cancelled_amount' => $paymentBatch->payments->where('status', 'cancelled')->sum('total_amount')
        ];

        return view('payment.batches.show', compact('paymentBatch', 'summary'));
    }

    public function edit(PaymentBatch $paymentBatch)
    {
        $paymentBatch->load(['semester', 'payments.teacher']);
        return view('payment.batches.edit', compact('paymentBatch'));
    }

    public function update(Request $request, PaymentBatch $paymentBatch)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:pending,completed,cancelled',
            'processed_date' => 'required|date',
            'notes' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $paymentBatch->update($validated);

            if ($validated['status'] === 'completed') {
                $paymentBatch->payments()->update([
                    'status' => 'paid',
                    'payment_date' => $validated['processed_date']
                ]);
            } elseif ($validated['status'] === 'cancelled') {
                $paymentBatch->payments()->update([
                    'status' => 'cancelled'
                ]);
            }

            DB::commit();

            return redirect()->route('payment-batches.show', $paymentBatch->id)
                ->with('success', 'Cập nhật đợt thanh toán thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Lỗi khi cập nhật đợt thanh toán: ' . $e->getMessage());
        }
    }

    public function destroy(PaymentBatch $paymentBatch)
    {
        try {
            DB::beginTransaction();
            
            $paymentBatch->payments()->delete();
            $paymentBatch->delete();
            
            DB::commit();
            
            return redirect()->route('payment-batches.index')
                ->with('success', 'Xóa đợt thanh toán thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi khi xóa đợt thanh toán: ' . $e->getMessage());
        }
    }
}
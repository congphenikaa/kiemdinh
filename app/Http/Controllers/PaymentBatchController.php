<?php

namespace App\Http\Controllers;

use App\Models\PaymentBatch;
use App\Models\Semester;
use App\Models\TeacherPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

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
        
        if (!$this->isValidCalculation($calculation)) {
            return redirect()->route('payment-calculations.calculate', $semester->id)
                ->with('error', 'Vui lòng tính toán thanh toán trước khi tạo đợt thanh toán');
        }

        return view('payment.batches.create', [
            'semester' => $semester,
            'groupedPayments' => $calculation['grouped'],
            'totalAmount' => $calculation['total_amount'],
            'totalClasses' => count($calculation['data']),
            'paymentConfig' => $calculation['config']
        ]);
    }

    public function store(Request $request, Semester $semester)
    {
        $validated = $this->validateRequest($request);
        $calculation = session('payment_calculation_' . $semester->id);

        if (!$this->isValidCalculation($calculation)) {
            return redirect()->route('payment-calculations.calculate', $semester->id)
                ->with('error', 'Dữ liệu tính toán không hợp lệ. Vui lòng tính toán lại');
        }

        try {
            DB::beginTransaction();

            // Tạo payment batch
            $batch = $this->createPaymentBatch($semester, $validated, $calculation);
            
            // Chuẩn bị dữ liệu payments (đã loại bỏ course_coefficient)
            $paymentsData = $this->preparePaymentData($calculation['data'], $batch->id, $semester->id, $validated['payment_date']);
            
            // Tạo các teacher payment
            TeacherPayment::insert($paymentsData);

            DB::commit();

            session()->forget('payment_calculation_' . $semester->id);

            return redirect()->route('payment-batches.show', $batch)
                ->with('success', 'Tạo đợt thanh toán thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment Batch Creation Error: ' . $e->getMessage());
            
            return back()->withInput()
                ->with('error', 'Lỗi khi tạo đợt thanh toán: ' . $this->getFriendlyErrorMessage($e));
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

        $summary = $this->getPaymentSummary($paymentBatch);

        return view('payment.batches.show', compact('paymentBatch', 'summary'));
    }



    public function edit(PaymentBatch $paymentBatch)
    {
        $paymentBatch->load(['semester.academicYear', 'payments.teacher']);
        
        return view('payment.batches.edit', [
            'batch' => $paymentBatch,
            'summary' => $this->getPaymentSummary($paymentBatch)
        ]);
    }

    public function update(Request $request, PaymentBatch $paymentBatch)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => ['required', Rule::in(['pending', 'completed', 'cancelled'])],
            'processed_date' => 'required|date',
            'notes' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $paymentBatch->update($validated);

            // Update payment statuses if batch status changed
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

            return redirect()->route('payment-batches.show', $paymentBatch)
                ->with('success', 'Cập nhật đợt thanh toán thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment Batch Update Error: ' . $e->getMessage());
            
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
            Log::error('Payment Batch Deletion Error: ' . $e->getMessage());
            
            return back()->with('error', 'Lỗi khi xóa đợt thanh toán: ' . $e->getMessage());
        }
    }

    // ========== CÁC PHƯƠNG THỨC HỖ TRỢ ========== //
    
    protected function validateRequest(Request $request)
    {
        return $request->validate([
            'batch_name' => 'required|string|max:255',
            'notes' => 'nullable|string|max:500',
            'payment_date' => 'required|date|after_or_equal:today'
        ], [
            'payment_date.after_or_equal' => 'Ngày thanh toán phải từ hôm nay trở đi'
        ]);
    }

    protected function isValidCalculation($calculation)
    {
        if (!$calculation || !isset($calculation['data']) || !is_array($calculation['data'])) {
            return false;
        }

        foreach ($calculation['data'] as $item) {
            $requiredFields = [
                'teacher_id', 'class_id', 'total_sessions',
                'degree_coefficient', 'size_coefficient',
                'base_rate', 'total_amount'
            ];

            foreach ($requiredFields as $field) {
                if (!isset($item[$field])) {
                    return false;
                }
            }
        }

        return true;
    }

    protected function createPaymentBatch($semester, $validated, $calculation)
    {
        return PaymentBatch::create([
            'name' => $validated['batch_name'],
            'semester_id' => $semester->id,
            'processed_date' => $validated['payment_date'],
            'total_amount' => (float)$calculation['total_amount'],
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null
        ]);
    }

    protected function preparePaymentData($calculationData, $batchId, $semesterId, $paymentDate)
    {
        return array_map(function($item) use ($batchId, $semesterId, $paymentDate) {
            return [
                'teacher_id' => (int)$item['teacher_id'],
                'class_id' => (int)$item['class_id'],
                'payment_batch_id' => (int)$batchId,
                'semester_id' => (int)$semesterId,
                'total_sessions' => (int)$item['total_sessions'],
                'degree_coefficient' => (float)$item['degree_coefficient'],
                'size_coefficient' => (float)$item['size_coefficient'],
                'base_rate' => (float)$item['base_rate'],
                'total_amount' => (float)$item['total_amount'],
                'status' => 'pending',
                'payment_date' => $paymentDate,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }, $calculationData);
    }

    protected function getPaymentSummary($paymentBatch)
    {
        return [
            'total_payments' => $paymentBatch->payments->count(),
            'total_amount' => $paymentBatch->payments->sum('total_amount'),
            'paid_count' => $paymentBatch->payments->where('status', 'paid')->count(),
            'paid_amount' => $paymentBatch->payments->where('status', 'paid')->sum('total_amount'),
            'pending_count' => $paymentBatch->payments->where('status', 'pending')->count(),
            'pending_amount' => $paymentBatch->payments->where('status', 'pending')->sum('total_amount'),
            'cancelled_count' => $paymentBatch->payments->where('status', 'cancelled')->count(),
            'cancelled_amount' => $paymentBatch->payments->where('status', 'cancelled')->sum('total_amount')
        ];
    }

    protected function getFriendlyErrorMessage(\Exception $e)
    {
        if (str_contains($e->getMessage(), 'BigDecimal::toScale()')) {
            return 'Lỗi định dạng dữ liệu số. Vui lòng kiểm tra lại các hệ số tính toán.';
        }

        return $e->getMessage();
    }

    
}
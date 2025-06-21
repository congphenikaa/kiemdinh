<?php

namespace App\Http\Controllers;

use App\Models\PaymentBatch;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentBatchController extends Controller
{
    public function index()
    {
        $batches = PaymentBatch::with(['semester', 'payments.teacher'])
            ->orderBy('processed_date', 'desc')
            ->paginate(15);

        return view('payment.batches.index', compact('batches'));
    }

    public function create()
    {
        $semesters = Semester::has('teacherPayments')->orderBy('start_date', 'desc')->get();
        return view('payment.batches.create', compact('semesters'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'semester_id' => ['required', 'exists:semesters,id'],
            'payment_ids' => ['required', 'array'],
            'payment_ids.*' => ['exists:teacher_payments,id']
        ]);

        DB::transaction(function() use ($validated) {
            // Tạo đợt thanh toán
            $batch = PaymentBatch::create([
                'name' => $validated['name'],
                'semester_id' => $validated['semester_id'],
                'processed_date' => now(),
                'status' => 'processing'
            ]);

            // Cập nhật các khoản thanh toán
            TeacherPayment::whereIn('id', $validated['payment_ids'])
                ->update([
                    'payment_batch_id' => $batch->id,
                    'status' => 'paid',
                    'payment_date' => now()
                ]);

            // Tính tổng số tiền
            $totalAmount = TeacherPayment::where('payment_batch_id', $batch->id)
                ->sum('total_amount');

            $batch->update([
                'total_amount' => $totalAmount,
                'status' => 'completed'
            ]);
        });

        return redirect()->route('payment-batches.index')
            ->with('success', 'Tạo đợt thanh toán thành công!');
    }

    public function show(PaymentBatch $batch)
    {
        $batch->load(['payments.teacher', 'payments.classe.course']);
        return view('payment.batches.show', compact('batch'));
    }
}
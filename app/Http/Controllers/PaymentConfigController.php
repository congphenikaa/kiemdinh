<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\PaymentConfig;
use Illuminate\Http\Request;

class PaymentConfigController extends Controller
{
    public function index()
    {
        $academicYears = AcademicYear::with('paymentConfigs')->orderBy('start_date', 'desc')->get();
        return view('payment.configs.index', compact('academicYears'));
    }

    public function create()
    {
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        return view('payment.configs.create', compact('academicYears'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'base_salary_per_session' => ['required', 'numeric', 'min:0']
        ]);

        // Check if config already exists for this academic year
        $existingConfig = PaymentConfig::where('academic_year_id', $validated['academic_year_id'])->first();
        
        if ($existingConfig) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Cấu hình thanh toán cho năm học này đã tồn tại!');
        }

        PaymentConfig::create($validated);

        return redirect()->route('payment-configs.index')
            ->with('success', 'Tạo cấu hình thanh toán thành công!');
    }

    public function edit(PaymentConfig $paymentConfig)
    {
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        return view('payment.configs.edit', compact('paymentConfig', 'academicYears'));
    }

    public function update(Request $request, PaymentConfig $paymentConfig)
    {
        $validated = $request->validate([
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'base_salary_per_session' => ['required', 'numeric', 'min:0']
        ]);

        // Check if another config already exists for this academic year
        $existingConfig = PaymentConfig::where('academic_year_id', $validated['academic_year_id'])
            ->where('id', '!=', $paymentConfig->id)
            ->first();
        
        if ($existingConfig) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Cấu hình thanh toán cho năm học này đã tồn tại!');
        }

        $paymentConfig->update($validated);

        return redirect()->route('payment-configs.index')
            ->with('success', 'Cập nhật cấu hình thanh toán thành công!');
    }

    public function destroy(PaymentConfig $paymentConfig)
    {
        // Check if this config is being used in any payments
        if ($paymentConfig->academicYear->paymentBatches()->exists()) {
            return redirect()->route('payment-configs.index')
                ->with('error', 'Không thể xóa cấu hình này vì nó đã được sử dụng trong các đợt thanh toán!');
        }

        $paymentConfig->delete();

        return redirect()->route('payment-configs.index')
            ->with('success', 'Xóa cấu hình thanh toán thành công!');
    }
}
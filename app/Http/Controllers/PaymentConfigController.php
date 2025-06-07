<?php

namespace App\Http\Controllers;

use App\Models\PaymentConfig;
use Illuminate\Http\Request;

class PaymentConfigController extends Controller
{
    public function index()
    {
        $config = PaymentConfig::firstOrNew();
        return view('payment.configs.index', compact('config'));
    }

    public function create()
    {
        return view('payment.configs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'base_salary_per_session' => ['required', 'numeric', 'min:0'],
            'practice_session_rate' => ['required', 'numeric', 'min:0', 'max:1']
        ]);

        PaymentConfig::updateOrCreate(
            ['id' => 1],
            $validated
        );

        return redirect()->route('payment-configs.index')
            ->with('success', 'Cập nhật cấu hình lương thành công!');
    }
}
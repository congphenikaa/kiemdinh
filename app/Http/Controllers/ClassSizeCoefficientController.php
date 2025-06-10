<?php

namespace App\Http\Controllers;

use App\Models\ClassSizeCoefficient;
use Illuminate\Http\Request;

class ClassSizeCoefficientController extends Controller
{
    // 1. Hiển thị danh sách
    public function index()
    {
        $coefficients = ClassSizeCoefficient::all();
        return view('payment.coefficients.index', compact('coefficients'));
    }

    // 2. Hiển thị form tạo mới
    public function create()
    {
        return view('payment.coefficients.create');
    }

    // 3. Lưu bản ghi mới
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'min_students' => 'required|integer|min:1',
            'max_students' => 'required|integer|gte:min_students',
            'coefficient' => 'required|numeric|min:0',
        ]);

        ClassSizeCoefficient::create($validatedData);

        return redirect()->route('class-size-coefficients.index')
                         ->with('success', 'Đã thêm hệ số lớp học thành công.');
    }

    // 4. Hiển thị form sửa
    public function edit($id)
    {
        $coefficient = ClassSizeCoefficient::findOrFail($id);
        return view('payment.coefficients.edit', compact('coefficient'));
    }

    // 5. Cập nhật bản ghi
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'min_students' => 'required|integer|min:1',
            'max_students' => 'required|integer|gte:min_students',
            'coefficient' => 'required|numeric|min:0',
        ]);

        $coefficient = ClassSizeCoefficient::findOrFail($id);
        $coefficient->update($validatedData);

        return redirect()->route('class-size-coefficients.index')
                         ->with('success', 'Đã cập nhật hệ số lớp học thành công.');
    }

    // 6. Xóa bản ghi
    public function destroy($id)
    {
        $coefficient = ClassSizeCoefficient::find($id);

        if (!$coefficient) {
            return redirect()->route('payment.coefficients.index')
                             ->with('error', 'Không tìm thấy bản ghi.');
        }

        try {
            $coefficient->delete();
            return redirect()->route('class-size-coefficients.index')
                             ->with('success', 'Đã xóa hệ số lớp học thành công.');
        } catch (\Exception $e) {
            return redirect()->route('class-size-coefficients.index')
                             ->with('error', 'Lỗi khi xóa: Bản ghi đang được sử dụng.');
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\ClassSizeCoefficient;
use Illuminate\Http\Request;

class ClassSizeCoefficientController extends Controller
{
    public function index()
    {
        $coefficients = ClassSizeCoefficient::orderBy('min_students')->get();
        return view('payment.coefficients.index', compact('coefficients'));
    }

    public function create()
    {
        return view('payment.coefficients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'min_students' => ['required', 'integer', 'min:0'],
            'max_students' => ['required', 'integer', 'gt:min_students'],
            'coefficient' => ['required', 'numeric', 'min:0']
        ]);

        // Kiểm tra trùng phạm vi
        $conflict = ClassSizeCoefficient::where(function($query) use ($request) {
            $query->whereBetween('min_students', [$request->min_students, $request->max_students])
                  ->orWhereBetween('max_students', [$request->min_students, $request->max_students])
                  ->orWhere(function($q) use ($request) {
                      $q->where('min_students', '<=', $request->min_students)
                        ->where('max_students', '>=', $request->max_students);
                  });
        })->exists();

        if ($conflict) {
            return back()->withInput()
                ->with('error', 'Phạm vi sĩ số đã tồn tại hoặc trùng lặp!');
        }

        ClassSizeCoefficient::create($validated);

        return redirect()->route('class-size-coefficients.index')
            ->with('success', 'Thêm hệ số sĩ số thành công!');
    }

    public function destroy(ClassSizeCoefficient $coefficient)
    {
        $coefficient->delete();
        return redirect()->route('class-size-coefficients.index')
            ->with('success', 'Xóa hệ số sĩ số thành công!');
    }
}
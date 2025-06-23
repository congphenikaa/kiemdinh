<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\ClassSizeCoefficient;
use Illuminate\Http\Request;

class ClassSizeCoefficientController extends Controller
{
    public function index()
    {
        $academicYears = AcademicYear::with('classSizeCoefficients')
            ->orderBy('start_date', 'desc')
            ->get();
            
        return view('payment.coefficients.index', compact('academicYears'));
    }

    public function create()
    {
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        return view('payment.coefficients.create', compact('academicYears'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'min_students' => 'required|integer|min:1',
            'max_students' => 'required|integer|gt:min_students',
            'coefficient' => 'required|numeric|between:-1,1',
        ]);

        // Check for overlapping ranges
        $overlapExists = ClassSizeCoefficient::where('academic_year_id', $validatedData['academic_year_id'])
            ->where(function($query) use ($validatedData) {
                $query->whereBetween('min_students', [$validatedData['min_students'], $validatedData['max_students']])
                      ->orWhereBetween('max_students', [$validatedData['min_students'], $validatedData['max_students']]);
            })
            ->exists();

        if ($overlapExists) {
            return back()->withInput()->with('error', 'Khoảng số sinh viên này đã tồn tại hoặc chồng lấn với khoảng khác trong năm học này');
        }

        ClassSizeCoefficient::create($validatedData);

        return redirect()->route('class-size-coefficients.index')
                         ->with('success', 'Đã thêm hệ số lớp học thành công.');
    }

    public function edit(ClassSizeCoefficient $classSizeCoefficient)
    {
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        return view('payment.coefficients.edit', compact('classSizeCoefficient', 'academicYears'));
    }

    public function update(Request $request, ClassSizeCoefficient $classSizeCoefficient)
    {
        $validatedData = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'min_students' => 'required|integer|min:1',
            'max_students' => 'required|integer|gt:min_students',
            'coefficient' => 'required|numeric|between:-1,1',
        ]);

        // Check for overlapping ranges excluding current record
        $overlapExists = ClassSizeCoefficient::where('academic_year_id', $validatedData['academic_year_id'])
            ->where('id', '!=', $classSizeCoefficient->id)
            ->where(function($query) use ($validatedData) {
                $query->whereBetween('min_students', [$validatedData['min_students'], $validatedData['max_students']])
                      ->orWhereBetween('max_students', [$validatedData['min_students'], $validatedData['max_students']]);
            })
            ->exists();

        if ($overlapExists) {
            return back()->withInput()->with('error', 'Khoảng số sinh viên này đã tồn tại hoặc chồng lấn với khoảng khác trong năm học này');
        }

        $classSizeCoefficient->update($validatedData);

        return redirect()->route('class-size-coefficients.index')
                         ->with('success', 'Đã cập nhật hệ số lớp học thành công.');
    }

    public function destroy(ClassSizeCoefficient $classSizeCoefficient)
    {
        try {
            $classSizeCoefficient->delete();
            return redirect()->route('class-size-coefficients.index')
                             ->with('success', 'Đã xóa hệ số lớp học thành công.');
        } catch (\Exception $e) {
            return redirect()->route('class-size-coefficients.index')
                             ->with('error', 'Lỗi khi xóa: Bản ghi đang được sử dụng.');
        }
    }
}
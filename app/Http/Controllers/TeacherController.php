<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Faculty;
use App\Models\Degree;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with(['faculty', 'degree'])->get();
        return view('teachers.index', compact('teachers'));
    }

    public function create()
    {
        $faculties = Faculty::all();
        $degrees = Degree::all();
        return view('teachers.create', compact('faculties', 'degrees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'nullable|string|max:50|unique:teachers',
            'name' => 'required|string|max:255|regex:/^[\pL\s\-]+$/u', // Chỉ cho phép chữ cái, khoảng trắng và dấu gạch ngang
            'dob' => [
                'required',
                'date',
                'before_or_equal:' . Carbon::now()->subYears(18)->format('Y-m-d'), // Ít nhất 18 tuổi
                'after_or_equal:' . Carbon::now()->subYears(150)->format('Y-m-d') // Không quá 150 tuổi
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^[0-9\-\+\(\)\s]+$/' // Chỉ cho phép số và các ký tự điện thoại
            ],
            'email' => 'nullable|email|max:255|unique:teachers,email',
            'faculty_id' => 'required|exists:faculties,id',
            'degree_id' => 'required|exists:degrees,id'
        ], [
            'dob.before_or_equal' => 'Giáo viên phải từ 18 tuổi trở lên',
            'dob.after_or_equal' => 'Ngày sinh không hợp lệ (tối đa 150 tuổi)',
            'name.regex' => 'Tên chỉ được chứa chữ cái và khoảng trắng',
            'phone.regex' => 'Số điện thoại không hợp lệ',
            'email.unique' => 'Email đã được sử dụng bởi giáo viên khác'
        ]);

        // Tự động tạo mã nếu không nhập
        if (empty($validated['code'])) {
            $validated['code'] = 'GV' . str_pad(Teacher::count() + 1, 3, '0', STR_PAD_LEFT);
        }

        Teacher::create($validated);

        return redirect()->route('teachers.index')
            ->with('success', 'Giáo viên đã được thêm thành công.');
    }

    public function show(Teacher $teacher)
    {
        return view('teachers.show', compact('teacher'));
    }

    public function edit(Teacher $teacher)
    {
        $faculties = Faculty::all();
        $degrees = Degree::all();
        return view('teachers.edit', compact('teacher', 'faculties', 'degrees'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'code' => 'nullable|string|max:50|unique:teachers,code,' . $teacher->id,
            'name' => 'required|string|max:255|regex:/^[\pL\s\-]+$/u',
            'dob' => [
                'required',
                'date',
                'before_or_equal:' . Carbon::now()->subYears(18)->format('Y-m-d'),
                'after_or_equal:' . Carbon::now()->subYears(150)->format('Y-m-d')
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^[0-9\-\+\(\)\s]+$/'
            ],
            'email' => 'nullable|email|max:255|unique:teachers,email,' . $teacher->id,
            'faculty_id' => 'required|exists:faculties,id',
            'degree_id' => 'required|exists:degrees,id'
        ], [
            'dob.before_or_equal' => 'Giáo viên phải từ 18 tuổi trở lên',
            'dob.after_or_equal' => 'Ngày sinh không hợp lệ (tối đa 150 tuổi)',
            'name.regex' => 'Tên chỉ được chứa chữ cái và khoảng trắng',
            'phone.regex' => 'Số điện thoại không hợp lệ',
            'email.unique' => 'Email đã được sử dụng bởi giáo viên khác'
        ]);

        $teacher->update($validated);

        return redirect()->route('teachers.index')
            ->with('success', 'Giáo viên đã được cập nhật thành công.');
    }

    public function destroy(Teacher $teacher)
    {
        $teacher->delete();

        return redirect()->route('teachers.index')
            ->with('success', 'Giáo viên đã được xóa thành công.');
    }

    public function statistics(Request $request)
    {
        $query = Teacher::with(['faculty', 'degree']);

        if ($request->has('faculty_id') && $request->faculty_id !== 'all') {
            $query->where('faculty_id', $request->faculty_id);
        }

        if ($request->has('year') && $request->year !== 'all') {
            $query->whereYear('created_at', $request->year);
        }

        if ($request->has('degree_id') && $request->degree_id !== 'all') {
            $query->where('degree_id', $request->degree_id);
        }

        $teachers = $query->get();
        $faculties = Faculty::all();
        $degrees = Degree::all();

        // Tính toán thống kê theo khoa
        $facultyStats = $teachers->groupBy('faculty.name')
            ->map(function ($group) {
                return $group->count();
            })->toArray();

        // Tính toán thống kê theo bằng cấp
        $degreeStats = $teachers->groupBy('degree.name')
            ->map(function ($group) {
                return $group->count();
            })->toArray();

        // Chuẩn bị dữ liệu cho biểu đồ
        $facultyData = [];
        foreach ($faculties as $faculty) {
            $facultyData[$faculty->name] = $teachers->where('faculty_id', $faculty->id)->count();
        }

        return view('teachers.statistics', compact('teachers', 'faculties', 'degrees', 'facultyStats', 'degreeStats', 'facultyData'));
    }

    public function export()
    {
        $teachers = Teacher::with(['faculty', 'degree'])->get();
        // Implement Excel export logic here
        return response()->json(['message' => 'Export functionality to be implemented']);
    }
} 
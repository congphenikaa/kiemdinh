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

    public function destroy($id)
    {
        try {
            $item = Teacher::findOrFail($id); // Thay Teacher bằng model tương ứng
            $item->delete();
            
            return redirect()->back()->with('success', 'Xóa Giáo Viên thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Xóa Giáo Viên thất bại: ' . $e->getMessage());
        }
    }

    

   
} 
<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Faculty;
use App\Models\Degree;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with(['faculty', 'degree'])
                        ->orderBy('name')
                        ->paginate(10);
        
        return view('teacher-management.teachers.index', compact('teachers'));
    }

    public function create()
    {
        $faculties = Faculty::orderBy('name')->get();
        $degrees = Degree::orderBy('name')->get();
        
        return view('teacher-management.teachers.create', compact('faculties', 'degrees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:teachers',
            'name' => 'required|string|max:255',
            'dob' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:teachers',
            'address' => 'required|string|max:255',
            'faculty_id' => 'required|exists:faculties,id',
            'degree_id' => 'required|exists:degrees,id',
            'start_date' => 'required|date',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();
            
            $validated['is_active'] = $request->has('is_active');
            $validated['dob'] = Carbon::parse($validated['dob'])->format('Y-m-d');
            $validated['start_date'] = Carbon::parse($validated['start_date'])->format('Y-m-d');

            Teacher::create($validated);
            
            DB::commit();
            
            return redirect()->route('teachers.index')
                             ->with('success', 'Giảng viên đã được tạo thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Có lỗi xảy ra khi tạo giảng viên: ' . $e->getMessage());
        }
    }

    public function edit(Teacher $teacher)
    {
        $faculties = Faculty::orderBy('name')->get();
        $degrees = Degree::orderBy('name')->get();
        
        return view('teacher-management.teachers.edit', compact('teacher', 'faculties', 'degrees'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('teachers')->ignore($teacher->id),
            ],
            'name' => 'required|string|max:255',
            'dob' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'phone' => 'required|string|max:20',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('teachers')->ignore($teacher->id),
            ],
            'address' => 'required|string|max:255',
            'faculty_id' => 'required|exists:faculties,id',
            'degree_id' => 'required|exists:degrees,id',
            'start_date' => 'required|date',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();
            
            $validated['is_active'] = $request->has('is_active');
            $validated['dob'] = Carbon::parse($validated['dob'])->format('Y-m-d');
            $validated['start_date'] = Carbon::parse($validated['start_date'])->format('Y-m-d');

            $teacher->update($validated);
            
            DB::commit();
            
            return redirect()->route('teachers.index')
                             ->with('success', 'Thông tin giảng viên đã được cập nhật.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Có lỗi xảy ra khi cập nhật giảng viên: ' . $e->getMessage());
        }
    }

    public function destroy(Teacher $teacher)
    {
        try {
            DB::beginTransaction();
            
            if ($teacher->teachingAssignments()->exists()) {
                return redirect()->route('teachers.index')
                                 ->with('error', 'Không thể xóa giảng viên vì đã có phân công giảng dạy.');
            }

            $teacher->delete();
            
            DB::commit();
            
            return redirect()->route('teachers.index')
                             ->with('success', 'Giảng viên đã được xóa thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra khi xóa giảng viên: ' . $e->getMessage());
        }
    }
}
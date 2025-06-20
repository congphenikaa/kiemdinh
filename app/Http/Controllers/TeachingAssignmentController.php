<?php

namespace App\Http\Controllers;

use App\Models\Clazz;
use App\Models\Teacher;
use App\Models\Schedule;
use App\Models\TeachingAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeachingAssignmentController extends Controller
{
    /**
     * Hiển thị danh sách phân công giảng dạy
     */
    public function index(Request $request)
    {
        $query = TeachingAssignment::with(['class.course', 'teacher.faculty'])
            ->orderBy('created_at', 'desc');

        // Lọc theo lớp học
        if ($request->has('class_id') && $request->class_id) {
            $query->where('class_id', $request->class_id);
        }

        // Lọc theo giáo viên
        if ($request->has('teacher_id') && $request->teacher_id) {
            $query->where('teacher_id', $request->teacher_id);
        }

        $assignments = $query->paginate(20);
        $classes = Clazz::where('status', 'open')->orderBy('class_code')->get();
        $teachers = Teacher::where('is_active', true)->orderBy('name')->get();

        return view('class-management.assignments.index', compact('assignments', 'classes', 'teachers'));
    }

    /**
     * Hiển thị form tạo phân công mới
     */
    public function create()
    {
        $classes = Clazz::with(['course', 'semester.academicYear'])
            ->where('status', 'open')
            ->orderBy('class_code')
            ->get();

        $teachers = Teacher::with(['faculty', 'degree'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('class-management.assignments.create', compact('classes', 'teachers'));
    }

    /**
     * Lưu phân công mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'teacher_id' => 'required|exists:teachers,id',
        ]);

        try {
            DB::beginTransaction();

            // Kiểm tra trùng phân công
            $existingAssignment = TeachingAssignment::where('class_id', $validated['class_id'])
                ->where('teacher_id', $validated['teacher_id'])
                ->exists();

            if ($existingAssignment) {
                return back()->withInput()
                    ->with('error', 'Giáo viên đã được phân công cho lớp học này.');
            }

            // Kiểm tra giáo viên có cùng khoa với môn học không
            $class = Clazz::with('course.faculty')->find($validated['class_id']);
            $teacher = Teacher::find($validated['teacher_id']);

            if ($class->course->faculty_id !== $teacher->faculty_id) {
                return back()->withInput()
                    ->with('warning', 'Giáo viên không thuộc khoa phụ trách môn học. Bạn có chắc muốn tiếp tục?')
                    ->with('force_confirm', true);
            }

            TeachingAssignment::create($validated);

            DB::commit();

            return redirect()->route('teaching-assignments.index')
                ->with('success', 'Phân công giảng dạy thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Lỗi khi tạo phân công: ' . $e->getMessage());
        }
    }

    /**
     * Xóa phân công giảng dạy
     */
    public function destroy(TeachingAssignment $teachingAssignment)
    {
        try {
            DB::beginTransaction();

            // Kiểm tra nếu giáo viên đã có buổi dạy trong lớp
            $taughtSessions = Schedule::where('class_id', $teachingAssignment->class_id)
                ->where('is_taught', true)
                ->exists();

            if ($taughtSessions) {
                return back()
                    ->with('error', 'Không thể xóa phân công vì giáo viên đã có buổi dạy trong lớp này.');
            }

            $teachingAssignment->delete();

            DB::commit();

            return redirect()->route('teaching-assignments.index')
                ->with('success', 'Xóa phân công giảng dạy thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi khi xóa phân công: ' . $e->getMessage());
        }
    }

    /**
     * API lấy danh sách giáo viên theo khoa của môn học
     */
    public function getTeachersByClass($classId)
    {
        $class = Clazz::with('course.faculty')->findOrFail($classId);
        $teachers = Teacher::where('faculty_id', $class->course->faculty_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return response()->json($teachers);
    }
}
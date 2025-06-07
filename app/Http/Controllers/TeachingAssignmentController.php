<?php

namespace App\Http\Controllers;

use App\Models\Clazz;
use App\Models\Teacher;
use App\Models\TeachingAssignment;
use Illuminate\Http\Request;

class TeachingAssignmentController extends Controller
{
    public function index()
    {
        $classes = Clazz::with(['course', 'semester', 'mainTeacher', 'teachingAssignments.teacher'])
            ->where('status', 'open')
            ->orderBy('start_date', 'desc')
            ->paginate(10);
            
        return view('class-management.assignments.index', compact('classes'));
    }

    public function create(Clazz $class)
{
    $teachers = Teacher::all();
    $currentAssignments = $class->teachingAssignments()->with('teacher')->get();
    
    return view('class-management.assignments.create', compact('class', 'teachers', 'currentAssignments'));
}

public function store(Request $request)
{
    $request->validate([
        'class_id' => 'required|exists:classes,id',
        'teacher_id' => 'required|exists:teachers,id',
        'main_teacher' => 'nullable|boolean',
        'assigned_sessions' => 'required|integer|min:1'
    ]);

    try {
        DB::beginTransaction();

        // Kiểm tra nếu là main teacher thì hủy main teacher cũ
        if ($request->main_teacher) {
            TeachingAssignment::where('class_id', $request->class_id)
                ->update(['main_teacher' => false]);
        }

        TeachingAssignment::create([
            'class_id' => $request->class_id,
            'teacher_id' => $request->teacher_id,
            'main_teacher' => $request->main_teacher ?? false,
            'assigned_sessions' => $request->assigned_sessions
        ]);

        DB::commit();
        
        return redirect()->route('teaching-assignments.index')
            ->with('success', 'Phân công giảng dạy đã được thêm thành công');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Lỗi khi thêm phân công: ' . $e->getMessage());
    }
}

public function edit(Clazz $class)
{
    $teachers = Teacher::all();
    $assignments = $class->teachingAssignments()->with('teacher')->get();
    
    return view('teaching-assignments.edit', compact('class', 'teachers', 'assignments'));
}

public function update(Request $request, Clazz $class)
{
    $request->validate([
        'assignments' => 'required|array',
        'assignments.*.teacher_id' => 'required|exists:teachers,id',
        'assignments.*.main_teacher' => 'nullable|boolean',
        'assignments.*.assigned_sessions' => 'required|integer|min:1'
    ]);

    try {
        DB::beginTransaction();

        // Xóa tất cả phân công cũ
        $class->teachingAssignments()->delete();

        // Thêm phân công mới
        foreach ($request->assignments as $assignment) {
            // Nếu là main teacher thì hủy main teacher cũ
            if ($assignment['main_teacher'] ?? false) {
                TeachingAssignment::where('class_id', $class->id)
                    ->update(['main_teacher' => false]);
            }

            TeachingAssignment::create([
                'class_id' => $class->id,
                'teacher_id' => $assignment['teacher_id'],
                'main_teacher' => $assignment['main_teacher'] ?? false,
                'assigned_sessions' => $assignment['assigned_sessions']
            ]);
        }

        DB::commit();
        
        return redirect()->route('teaching-assignments.index')
            ->with('success', 'Cập nhật phân công thành công');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Lỗi khi cập nhật: ' . $e->getMessage());
    }
}
}
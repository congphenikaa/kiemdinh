<?php

namespace App\Http\Controllers;

use App\Models\Clazz;
use App\Models\Teacher;
use App\Models\TeachingAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeachingAssignmentController extends Controller
{
    public function index()
    {
        $classes = Clazz::with([
                'course', 
                'semester', 
                'mainTeacher', 
                'teachingAssignments.teacher',
                'schedules' // Thêm schedules để tính toán số buổi
            ])
            ->where('status', 'open')
            ->orderBy('start_date', 'desc')
            ->paginate(10);
            
        return view('class-management.assignments.index', compact('classes'));
    }

    public function create(Clazz $class)
    {
        $class->load([
            'course', 
            'semester', 
            'schedules', 
            'teachingAssignments.teacher',
            'schedules' // Đảm bảo load schedules
        ]);

        $teachers = Teacher::orderBy('name')->get();
        $currentAssignments = $class->teachingAssignments;

        // Tính tổng số buổi đã phân công
        $totalAssignedSessions = $class->teachingAssignments->sum('assigned_sessions');
        $remainingSessions = $class->schedules->count() - $totalAssignedSessions;

        return view('class-management.assignments.create', compact(
            'class', 
            'teachers', 
            'totalAssignedSessions',
            'remainingSessions'
        ));
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

            $class = Clazz::with(['schedules', 'teachingAssignments'])->find($request->class_id);
            $totalSessions = $class->schedules->count();
            $assignedSessions = $class->teachingAssignments->sum('assigned_sessions');
            $remainingSessions = $totalSessions - $assignedSessions;

            // Kiểm tra số buổi phân công không vượt quá số buổi còn lại
            if ($request->assigned_sessions > $remainingSessions) {
                return back()->with('error', 'Số buổi phân công vượt quá số buổi còn lại ('.$remainingSessions.' buổi)');
            }

            // Kiểm tra nếu là main teacher thì hủy main teacher cũ
            if ($request->main_teacher) {
                TeachingAssignment::where('class_id', $request->class_id)
                    ->update(['main_teacher' => false]);
            }

            // Kiểm tra giáo viên đã được phân công chưa
            $existingAssignment = TeachingAssignment::where('class_id', $request->class_id)
                ->where('teacher_id', $request->teacher_id)
                ->first();

            if ($existingAssignment) {
                return back()->with('error', 'Giảng viên này đã được phân công cho lớp');
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
        $class->load([
            'course', 
            'semester', 
            'schedules', 
            'teachingAssignments.teacher'
        ]);

        $teachers = Teacher::orderBy('name')->get();
        $totalSessions = $class->schedules->count();
        $totalAssignedSessions = $class->teachingAssignments->sum('assigned_sessions');
        $remainingSessions = $totalSessions - $totalAssignedSessions;

        // Lấy danh sách teacher chưa được phân công
        $assignedTeacherIds = $class->teachingAssignments->pluck('teacher_id')->toArray();
        $availableTeachers = Teacher::whereNotIn('id', $assignedTeacherIds)
            ->orderBy('name')
            ->get();

        return view('class-management.assignments.edit', compact(
            'class',
            'teachers',
            'availableTeachers',
            'totalSessions',
            'remainingSessions'
        ));
    }

    public function update(Request $request, Clazz $class)
    {
        $request->validate([
            'assignments' => 'sometimes|array',
            'assignments.*.id' => 'sometimes|exists:teaching_assignments,id',
            'assignments.*.teacher_id' => 'required_with:assignments|exists:teachers,id',
            'assignments.*.assigned_sessions' => 'required_with:assignments|integer|min:1',
            'assignments.*.main_teacher' => 'nullable|boolean',
            'deleted_assignments' => 'sometimes|array',
            'deleted_assignments.*' => 'sometimes|exists:teaching_assignments,id'
        ]);

        try {
            DB::beginTransaction();

            $totalSessions = $class->schedules->count();
            
            // Xử lý xóa trước
            if ($request->has('deleted_assignments')) {
                TeachingAssignment::whereIn('id', $request->deleted_assignments)->delete();
            }

            // Tính tổng số buổi đã phân công
            $totalAssigned = $request->has('assignments') 
                ? collect($request->assignments)->sum('assigned_sessions')
                : 0;
                
            if ($totalAssigned > $totalSessions) {
                return back()->with('error', 'Tổng số buổi phân công vượt quá số buổi học của lớp');
            }

            // Kiểm tra có đúng 1 giảng viên chính
            if ($request->has('assignments')) {
                $mainTeachersCount = collect($request->assignments)
                    ->filter(fn($a) => $a['main_teacher'] ?? false)
                    ->count();
                    
                if ($mainTeachersCount !== 1) {
                    return back()->with('error', 'Phải có chính xác 1 giảng viên chính');
                }
            }

            // Cập nhật hoặc tạo phân công mới
            if ($request->has('assignments')) {
                foreach ($request->assignments as $assignmentData) {
                    if (isset($assignmentData['id'])) {
                        $assignment = TeachingAssignment::find($assignmentData['id']);
                        $assignment->update([
                            'assigned_sessions' => $assignmentData['assigned_sessions'],
                            'main_teacher' => $assignmentData['main_teacher'] ?? false
                        ]);
                    } else {
                        TeachingAssignment::create([
                            'class_id' => $class->id,
                            'teacher_id' => $assignmentData['teacher_id'],
                            'assigned_sessions' => $assignmentData['assigned_sessions'],
                            'main_teacher' => $assignmentData['main_teacher'] ?? false
                        ]);
                    }
                }
            }

            DB::commit();
            
            return redirect()->route('teaching-assignments.index')
                ->with('success', 'Cập nhật phân công thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi khi cập nhật: ' . $e->getMessage());
        }
    }

    public function destroy(Clazz $class)
    {
        try {
            $class->teachingAssignments()->delete();

            return back()->with('success', 'Đã xoá toàn bộ phân công giảng dạy của lớp.');
        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi khi xoá phân công: ' . $e->getMessage());
        }
    }

}
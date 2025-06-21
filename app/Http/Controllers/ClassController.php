<?php

namespace App\Http\Controllers;

use App\Models\Clazz;
use App\Models\Course;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClassController extends Controller
{
    public function index()
    {
        $classes = Clazz::with(['course', 'semester'])->orderBy('start_date', 'desc')->paginate(10);
        return view('class-management.classes.index', compact('classes'));
    }

    public function create()
    {
        $courses = Course::all();
        $semesters = Semester::all();
        return view('class-management.classes.create', compact('courses', 'semesters'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_code' => 'required|string|max:50|unique:classes,class_code',
            'course_id' => 'required|exists:courses,id',
            'semester_id' => 'required|exists:semesters,id',
            'room' => 'nullable|string|max:100',
            'max_students' => 'required|integer|min:1',
            'current_students' => 'nullable|integer|min:0',
            'schedule_type' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:open,closed,finished'
        ]);

        try {
            DB::beginTransaction();

            $validated['start_date'] = Carbon::parse($validated['start_date'])->format('Y-m-d');
            $validated['end_date'] = Carbon::parse($validated['end_date'])->format('Y-m-d');

            Clazz::create($validated);

            DB::commit();

            return redirect()->route('classes.index')
                ->with('success', 'Class created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error creating class: ' . $e->getMessage());
        }
    }

    public function edit(Clazz $class)
    {
        $courses = Course::all();
        $semesters = Semester::all();
        return view('class-management.classes.edit', compact('class', 'courses', 'semesters'));
    }

    public function update(Request $request, Clazz $class)
    {
        $validated = $request->validate([
            'class_code' => 'required|string|max:50|unique:classes,class_code,' . $class->id,
            'course_id' => 'required|exists:courses,id',
            'semester_id' => 'required|exists:semesters,id',
            'room' => 'nullable|string|max:100',
            'max_students' => 'required|integer|min:1',
            'current_students' => 'nullable|integer|min:0',
            'schedule_type' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:open,closed,finished'
        ]);

        try {
            DB::beginTransaction();

            $validated['start_date'] = Carbon::parse($validated['start_date'])->format('Y-m-d');
            $validated['end_date'] = Carbon::parse($validated['end_date'])->format('Y-m-d');

            $class->update($validated);

            DB::commit();

            return redirect()->route('classes.index')
                ->with('success', 'Class updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error updating class: ' . $e->getMessage());
        }
    }

    public function destroy(Clazz $class)
    {
        try {
            DB::beginTransaction();

            // Optional: Prevent deletion if related schedules or teaching assignments exist
            if ($class->schedules()->exists() || $class->teachingAssignments()->exists()) {
                return redirect()->route('classes.index')
                    ->with('error', 'Cannot delete class with associated schedules or teaching assignments.');
            }

            $class->delete();

            DB::commit();

            return redirect()->route('classes.index')
                ->with('success', 'Class deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting class: ' . $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TeachingAssignment;
use App\Models\Teacher;
use App\Models\Clazz;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TeachingAssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $assignments = TeachingAssignment::with(['teacher', 'class'])
            ->latest()
            ->paginate(10);

        return view('class-management.teaching-assignments.index', compact('assignments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teachers = Teacher::orderBy('name')->get();
        $classes = Clazz::orderBy('name')->get();

        return view('class-management.teaching-assignments.create', compact('teachers', 'classes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_id' => [
                'required',
                'exists:classes,id',
                Rule::unique('teaching_assignments')->where(function ($query) use ($request) {
                    return $query->where('teacher_id', $request->teacher_id);
                })
            ],
            'teacher_id' => 'required|exists:teachers,id',
            'main_teacher' => 'sometimes|boolean',
            'assigned_sessions' => 'required|integer|min:1'
        ], [
            'class_id.unique' => 'This teacher is already assigned to this class.'
        ]);

        TeachingAssignment::create($validated);

        return redirect()->route('class-management.assignments.index')
            ->with('success', 'Teaching assignment created successfully.');
    }

    /**
     * Display the specified resource.
     */

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TeachingAssignment $assignment)
    {
        $teachers = Teacher::orderBy('name')->get();
        $classes = Clazz::orderBy('name')->get();

        return view('class-management.teaching-assignments.edit', compact('assignment', 'teachers', 'classes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TeachingAssignment $assignment)
    {
        $validated = $request->validate([
            'class_id' => [
                'required',
                'exists:classes,id',
                Rule::unique('teaching_assignments')
                    ->where(function ($query) use ($request) {
                        return $query->where('teacher_id', $request->teacher_id);
                    })
                    ->ignore($assignment->id)
            ],
            'teacher_id' => 'required|exists:teachers,id',
            'main_teacher' => 'sometimes|boolean',
            'assigned_sessions' => 'required|integer|min:1'
        ], [
            'class_id.unique' => 'This teacher is already assigned to this class.'
        ]);

        $assignment->update($validated);

        return redirect()->route('class-management.assignments.index')
            ->with('success', 'Teaching assignment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TeachingAssignment $assignment)
    {
        $assignment->delete();

        return redirect()->route('class-management.assignments.index')
            ->with('success', 'Teaching assignment deleted successfully.');
    }
}
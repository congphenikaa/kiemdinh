<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    /**
     * Display a listing of the courses.
     */
    public function index()
    {
        $courses = Course::with('faculty')->latest()->paginate(10);
        return view('class-management.courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new course.
     */
    public function create()
    {
        $faculties = Faculty::all();
        return view('class-management.courses.create', compact('faculties'));
    }

    /**
     * Store a newly created course in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_code' => 'required|string|max:20|unique:courses',
            'name' => 'required|string|max:100',
            'credit_hours' => 'required|integer|min:1|max:10',
            'total_sessions' => 'required|integer|min:1|max:50',
            'description' => 'nullable|string|max:500',
            'faculty_id' => 'required|exists:faculties,id'
        ]);

        Course::create($validated);

        return redirect()->route('courses.index')
            ->with('success', 'Course created successfully.');
    }


    /**
     * Show the form for editing the specified course.
     */
    public function edit(Course $course)
    {
        $faculties = Faculty::all();
        return view('class-management.courses.edit', compact('course', 'faculties'));
    }

    /**
     * Update the specified course in storage.
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'course_code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('courses')->ignore($course->id)
            ],
            'name' => 'required|string|max:100',
            'credit_hours' => 'required|integer|min:1|max:10',
            'total_sessions' => 'required|integer|min:1|max:50',
            'description' => 'nullable|string|max:500',
            'faculty_id' => 'required|exists:faculties,id'
        ]);

        $course->update($validated);

        return redirect()->route('courses.index')
            ->with('success', 'Course updated successfully.');
    }

    /**
     * Remove the specified course from storage.
     */
    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('courses.index')
            ->with('success', 'Course deleted successfully.');
    }
}
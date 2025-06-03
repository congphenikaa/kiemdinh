<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FacultyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $faculties = Faculty::withCount(['teachers', 'courses'])
                          ->orderBy('name')
                          ->paginate(10);
        
        return view('teacher-management.faculties.index', compact('faculties'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('teacher-management.faculties.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:faculties',
            'short_name' => 'required|string|max:50|unique:faculties',
            'description' => 'nullable|string',
        ]);

        Faculty::create($validated);

        return redirect()->route('faculties.index')
                         ->with('success', 'Khoa đã được tạo thành công.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Faculty $faculty)
    {
        return view('teacher-management.faculties.edit', compact('faculty'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Faculty $faculty)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('faculties')->ignore($faculty->id),
            ],
            'short_name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('faculties')->ignore($faculty->id),
            ],
            'description' => 'nullable|string',
        ]);

        $faculty->update($validated);

        return redirect()->route('faculties.index')
                         ->with('success', 'Khoa đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Faculty $faculty)
    {
        // Kiểm tra ràng buộc trước khi xóa
        if ($faculty->teachers()->exists() || $faculty->courses()->exists()) {
            return redirect()->route('faculties.index')
                             ->with('error', 'Không thể xóa khoa vì có giáo viên hoặc học phần đang sử dụng.');
        }

        $faculty->delete();

        return redirect()->route('faculties.index')
                         ->with('success', 'Khoa đã được xóa thành công.');
    }
}
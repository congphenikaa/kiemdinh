<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use App\Models\Teacher;
use Illuminate\Http\Request;

class FacultyController extends Controller
{
    public function index()
    {
        $faculties = Faculty::all();
        return view('faculties.index', compact('faculties'));
    }

    public function create()
    {
        return view('faculties.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:faculties,name',
            'short_name' => 'required|string|max:50|unique:faculties,short_name',
            'description' => 'nullable|string'
        ], [
            'name.unique' => 'Tên khoa đã tồn tại',
            'short_name.unique' => 'Tên viết tắt đã tồn tại'
        ]);

        Faculty::create($validated);

        return redirect()->route('faculties.index')
            ->with('success', 'Khoa đã được thêm thành công.');
    }

    public function edit(Faculty $faculty)
    {
        return view('faculties.edit', compact('faculty'));
    }

    public function update(Request $request, Faculty $faculty)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:faculties,name,'.$faculty->id,
            'short_name' => 'required|string|max:50|unique:faculties,short_name,'.$faculty->id,
            'description' => 'nullable|string'
        ], [
            'name.unique' => 'Tên khoa đã tồn tại',
            'short_name.unique' => 'Tên viết tắt đã tồn tại'
        ]);

        $faculty->update($validated);

        return redirect()->route('faculties.index')
            ->with('success', 'Khoa đã được cập nhật thành công.');
    }

    public function destroy(Faculty $faculty)
    {
        // Kiểm tra xem có giảng viên nào thuộc khoa này không
        if (Teacher::where('faculty_id', $faculty->id)->exists()) {
            return redirect()->route('faculties.index')
                ->with('error', 'Không thể xóa khoa vì có giảng viên đang thuộc khoa này.');
        }

        $faculty->delete();

        return redirect()->route('faculties.index')
            ->with('success', 'Khoa đã được xóa thành công.');
    }
}

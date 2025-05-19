<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
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
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:50',
            'description' => 'nullable|string'
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
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:50',
            'description' => 'nullable|string'
        ]);

        $faculty->update($validated);

        return redirect()->route('faculties.index')
            ->with('success', 'Khoa đã được cập nhật thành công.');
    }

    public function destroy(Faculty $faculty)
    {
        $faculty->delete();

        return redirect()->route('faculties.index')
            ->with('success', 'Khoa đã được xóa thành công.');
    }
} 
<?php

namespace App\Http\Controllers;

use App\Models\Degree;
use Illuminate\Http\Request;

class DegreeController extends Controller
{
    public function index()
    {
        $degrees = Degree::all();
        return view('degrees.index', compact('degrees'));
    }

    public function create()
    {
        return view('degrees.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:50'
        ]);

        Degree::create($validated);

        return redirect()->route('degrees.index')
            ->with('success', 'Bằng cấp đã được thêm thành công.');
    }

    public function edit(Degree $degree)
    {
        return view('degrees.edit', compact('degree'));
    }

    public function update(Request $request, Degree $degree)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:50'
        ]);

        $degree->update($validated);

        return redirect()->route('degrees.index')
            ->with('success', 'Bằng cấp đã được cập nhật thành công.');
    }

    public function destroy(Degree $degree)
    {
        $degree->delete();

        return redirect()->route('degrees.index')
            ->with('success', 'Bằng cấp đã được xóa thành công.');
    }
} 
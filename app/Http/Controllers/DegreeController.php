<?php

namespace App\Http\Controllers;

use App\Models\Degree;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DegreeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $degrees = Degree::orderBy('name')->paginate(10);
        return view('teacher-management.degrees.index', compact('degrees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('teacher-management.degrees.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:degrees',
            'short_name' => 'required|string|max:50|unique:degrees',
            'salary_coefficient' => 'required|numeric|min:0',
        ]);

        Degree::create($validated);

        return redirect()->route('degrees.index')
                         ->with('success', 'Degree created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Degree $degree)
    {
        return view('teacher-management.degrees.edit', compact('degree'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Degree $degree)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('degrees')->ignore($degree->id),
            ],
            'short_name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('degrees')->ignore($degree->id),
            ],
            'salary_coefficient' => 'required|numeric|min:0',
        ]);

        $degree->update($validated);

        return redirect()->route('degrees.index')
                         ->with('success', 'Degree updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Degree $degree)
    {
        // Check if degree is being used by any teacher
        if ($degree->teachers()->exists()) {
            return redirect()->route('degrees.index')
                             ->with('error', 'Cannot delete degree because it is assigned to one or more teachers.');
        }

        $degree->delete();

        return redirect()->route('degrees.index')
                         ->with('success', 'Degree deleted successfully.');
    }
}
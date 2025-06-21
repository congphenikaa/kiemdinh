<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AcademicYearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->paginate(10);
        return view('class-management.academic-years.index', compact('academicYears'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('class-management.academic-years.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'sometimes|boolean'
        ]);

        try {
            DB::beginTransaction();

            // Format dates using Carbon
            $validated['start_date'] = Carbon::parse($validated['start_date'])->format('Y-m-d');
            $validated['end_date'] = Carbon::parse($validated['end_date'])->format('Y-m-d');
            
            // Properly handle checkbox value
            $validated['is_active'] = $request->boolean('is_active');

            // If setting this as active, deactivate all others
            if ($validated['is_active']) {
                AcademicYear::where('is_active', true)->update(['is_active' => false]);
            }

            AcademicYear::create($validated);

            DB::commit();

            return redirect()->route('academic-years.index')
                ->with('success', 'Academic year created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error creating academic year: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AcademicYear $academicYear)
    {
        return view('class-management.academic-years.edit', compact('academicYear'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AcademicYear $academicYear)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'sometimes|boolean'
        ]);

        try {
            DB::beginTransaction();

            // Format dates using Carbon
            $validated['start_date'] = Carbon::parse($validated['start_date'])->format('Y-m-d');
            $validated['end_date'] = Carbon::parse($validated['end_date'])->format('Y-m-d');
            
            // Properly handle checkbox value
            $validated['is_active'] = $request->boolean('is_active');

            // If setting this as active, deactivate all others
            if ($validated['is_active']) {
                AcademicYear::where('id', '!=', $academicYear->id)
                    ->where('is_active', true)
                    ->update(['is_active' => false]);
            }

            $academicYear->update($validated);

            DB::commit();

            return redirect()->route('academic-years.index')
                ->with('success', 'Academic year updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error updating academic year: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcademicYear $academicYear)
    {
        try {
            DB::beginTransaction();

            // Prevent deletion if there are associated semesters
            if ($academicYear->semesters()->exists()) {
                return redirect()->route('academic-years.index')
                    ->with('error', 'Cannot delete academic year with associated semesters.');
            }

            $academicYear->delete();

            DB::commit();

            return redirect()->route('academic-years.index')
                ->with('success', 'Academic year deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting academic year: ' . $e->getMessage());
        }
    }
}
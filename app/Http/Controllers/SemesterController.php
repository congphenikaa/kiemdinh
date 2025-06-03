<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SemesterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $semesters = Semester::with('academicYear')
            ->orderBy('start_date', 'desc')
            ->paginate(10);
            
        return view('class-management.semesters.index', compact('semesters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        return view('class-management.semesters.create', compact('academicYears'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'academic_year_id' => 'required|exists:academic_years,id',
            'start_date' => 'required|date',
            'end_date' => [
                'required',
                'date',
                'after:start_date',
                function ($attribute, $value, $fail) use ($request) {
                    $startDate = Carbon::parse($request->start_date);
                    $endDate = Carbon::parse($value);
                    
                    // Check semester duration (max 6 months)
                    if ($startDate->diffInMonths($endDate) > 6) {
                        $fail('The semester duration cannot exceed 6 months.');
                    }
                    
                    // Check if end date is within academic year
                    $academicYear = AcademicYear::find($request->academic_year_id);
                    if ($academicYear && $endDate->gt(Carbon::parse($academicYear->end_date))) {
                        $fail('The semester end date cannot exceed the academic year end date.');
                    }
                }
            ],
            'type' => [
                'required',
                Rule::in([1, 2]),
                function ($attribute, $value, $fail) use ($request) {
                    // Check for duplicate semester type in academic year
                    if (Semester::where('academic_year_id', $request->academic_year_id)
                        ->where('type', $value)
                        ->exists()) {
                        $fail('This academic year already has a semester of this type.');
                    }
                }
            ],
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
                Semester::where('is_active', true)->update(['is_active' => false]);
            }

            Semester::create($validated);

            DB::commit();

            return redirect()->route('semesters.index')
                ->with('success', 'Semester created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error creating semester: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Semester $semester)
    {
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        return view('class-management.semesters.edit', compact('semester', 'academicYears'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Semester $semester)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'academic_year_id' => 'required|exists:academic_years,id',
            'start_date' => 'required|date',
            'end_date' => [
                'required',
                'date',
                'after:start_date',
                function ($attribute, $value, $fail) use ($request, $semester) {
                    $startDate = Carbon::parse($request->start_date);
                    $endDate = Carbon::parse($value);
                    
                    // Check semester duration (max 6 months)
                    if ($startDate->diffInMonths($endDate) > 6) {
                        $fail('The semester duration cannot exceed 6 months.');
                    }
                    
                    // Check if end date is within academic year
                    $academicYear = AcademicYear::find($request->academic_year_id);
                    if ($academicYear && $endDate->gt(Carbon::parse($academicYear->end_date))) {
                        $fail('The semester end date cannot exceed the academic year end date.');
                    }
                }
            ],
            'type' => [
                'required',
                Rule::in([1, 2]),
                function ($attribute, $value, $fail) use ($request, $semester) {
                    // Check for duplicate semester type in academic year (excluding current)
                    if (Semester::where('academic_year_id', $request->academic_year_id)
                        ->where('type', $value)
                        ->where('id', '!=', $semester->id)
                        ->exists()) {
                        $fail('This academic year already has a semester of this type.');
                    }
                }
            ],
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
                Semester::where('id', '!=', $semester->id)
                    ->where('is_active', true)
                    ->update(['is_active' => false]);
            }

            $semester->update($validated);

            DB::commit();

            return redirect()->route('semesters.index')
                ->with('success', 'Semester updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error updating semester: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Semester $semester)
    {
        try {
            DB::beginTransaction();

            // Prevent deletion if there are associated records
            if ($semester->classes()->exists()) {
                return redirect()->route('semesters.index')
                    ->with('error', 'Cannot delete semester with associated classes.');
            }

            if ($semester->teacherPayments()->exists()) {
                return redirect()->route('semesters.index')
                    ->with('error', 'Cannot delete semester with associated teacher payments.');
            }

            if ($semester->paymentBatches()->exists()) {
                return redirect()->route('semesters.index')
                    ->with('error', 'Cannot delete semester with associated payment batches.');
            }

            $semester->delete();

            DB::commit();

            return redirect()->route('semesters.index')
                ->with('success', 'Semester deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting semester: ' . $e->getMessage());
        }
    }

    /**
     * Toggle active status of semester
     */
    public function toggleActive(Semester $semester)
    {
        try {
            DB::beginTransaction();

            if ($semester->is_active) {
                $semester->update(['is_active' => false]);
                $message = 'Semester deactivated successfully.';
            } else {
                // Deactivate all other semesters first
                Semester::where('id', '!=', $semester->id)
                    ->where('is_active', true)
                    ->update(['is_active' => false]);
                    
                $semester->update(['is_active' => true]);
                $message = 'Semester activated successfully (other semesters were deactivated).';
            }

            DB::commit();

            return redirect()->back()
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error toggling semester status: ' . $e->getMessage());
        }
    }
}
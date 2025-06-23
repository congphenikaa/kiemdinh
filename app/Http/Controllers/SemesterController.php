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
    public function index()
    {
        $semesters = Semester::with('academicYear')
            ->orderBy('start_date', 'desc')
            ->paginate(10);
            
        return view('class-management.semesters.index', compact('semesters'));
    }

    public function create()
    {
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        return view('class-management.semesters.create', compact('academicYears'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:semesters,name,NULL,id,academic_year_id,'.$request->academic_year_id,
            'academic_year_id' => 'required|exists:academic_years,id',
            'start_date' => 'required|date',
            'end_date' => [
                'required',
                'date',
                'after:start_date',
                function ($attribute, $value, $fail) use ($request) {
                    $startDate = Carbon::parse($request->start_date);
                    $endDate = Carbon::parse($value);
                    
                    $academicYear = AcademicYear::find($request->academic_year_id);
                    if ($academicYear) {
                        if ($startDate->lt(Carbon::parse($academicYear->start_date))) {
                            $fail('The semester start date cannot be before the academic year start date.');
                        }
                        if ($endDate->gt(Carbon::parse($academicYear->end_date))) {
                            $fail('The semester end date cannot exceed the academic year end date.');
                        }
                    }
                    $overlapping = Semester::where('academic_year_id', $request->academic_year_id)
                        ->where(function($query) use ($startDate, $endDate) {
                            $query->whereBetween('start_date', [$startDate, $endDate])
                                ->orWhereBetween('end_date', [$startDate, $endDate])
                                ->orWhere(function($q) use ($startDate, $endDate) {
                                    $q->where('start_date', '<', $startDate)
                                        ->where('end_date', '>', $endDate);
                                });
                        });
                    
                    if (isset($semester)) {
                        $overlapping->where('id', '!=', $semester->id);
                    }
                    
                    if ($overlapping->exists()) {
                        $fail('The semester dates overlap with another semester in the same academic year.');
                    }
                }
            ],
            'type' => ['required', Rule::in(['1', '2'])],
            'is_active' => 'sometimes|boolean'
        ]);


        DB::transaction(function () use ($validated, $request) {
            $validated['start_date'] = Carbon::parse($validated['start_date'])->format('Y-m-d');
            $validated['end_date'] = Carbon::parse($validated['end_date'])->format('Y-m-d');
            $validated['is_active'] = $request->boolean('is_active');

            if ($validated['is_active']) {
                Semester::where('is_active', true)->update(['is_active' => false]);
            }

            Semester::create($validated);
        });

        return redirect()->route('semesters.index')
            ->with('success', 'Semester created successfully.');
    }

    public function edit(Semester $semester)
    {
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        return view('class-management.semesters.edit', compact('semester', 'academicYears'));
    }

    public function update(Request $request, Semester $semester)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('semesters')->ignore($semester->id)->where('academic_year_id', $request->academic_year_id)
            ],
            'academic_year_id' => 'required|exists:academic_years,id',
            'start_date' => 'required|date',
            'end_date' => [
                'required',
                'date',
                'after:start_date',
                function ($attribute, $value, $fail) use ($request, $semester) {
                    $startDate = Carbon::parse($request->start_date);
                    $endDate = Carbon::parse($value);
                    
                    $academicYear = AcademicYear::find($request->academic_year_id);
                    if ($academicYear) {
                        if ($startDate->lt(Carbon::parse($academicYear->start_date))) {
                            $fail('The semester start date cannot be before the academic year start date.');
                        }
                        if ($endDate->gt(Carbon::parse($academicYear->end_date))) {
                            $fail('The semester end date cannot exceed the academic year end date.');
                        }
                    }
                    $overlapping = Semester::where('academic_year_id', $request->academic_year_id)
                        ->where(function($query) use ($startDate, $endDate) {
                            $query->whereBetween('start_date', [$startDate, $endDate])
                                ->orWhereBetween('end_date', [$startDate, $endDate])
                                ->orWhere(function($q) use ($startDate, $endDate) {
                                    $q->where('start_date', '<', $startDate)
                                        ->where('end_date', '>', $endDate);
                                });
                        });
                    
                    if (isset($semester)) {
                        $overlapping->where('id', '!=', $semester->id);
                    }
                    
                    if ($overlapping->exists()) {
                        $fail('The semester dates overlap with another semester in the same academic year.');
                    }
                }
            ],
            'type' => ['required', Rule::in(['1', '2'])],
            'is_active' => 'sometimes|boolean'
        ]);

        DB::transaction(function () use ($validated, $request, $semester) {
            $validated['start_date'] = Carbon::parse($validated['start_date'])->format('Y-m-d');
            $validated['end_date'] = Carbon::parse($validated['end_date'])->format('Y-m-d');
            $validated['is_active'] = $request->boolean('is_active');

            if ($validated['is_active']) {
                Semester::where('id', '!=', $semester->id)
                    ->where('is_active', true)
                    ->update(['is_active' => false]);
            }

            $semester->update($validated);
        });

        return redirect()->route('semesters.index')
            ->with('success', 'Semester updated successfully.');
    }

    public function destroy(Semester $semester)
    {
        if ($semester->classes()->exists() || 
            $semester->teacherPayments()->exists() || 
            $semester->paymentBatches()->exists()) {
            return redirect()->route('semesters.index')
                ->with('error', 'Cannot delete semester with associated records.');
        }

        $semester->delete();

        return redirect()->route('semesters.index')
            ->with('success', 'Semester deleted successfully.');
    }

    public function toggleActive(Semester $semester)
    {
        DB::transaction(function () use ($semester) {
            if ($semester->is_active) {
                $semester->update(['is_active' => false]);
                $message = 'Semester deactivated successfully.';
            } else {
                Semester::where('id', '!=', $semester->id)
                    ->where('is_active', true)
                    ->update(['is_active' => false]);
                    
                $semester->update(['is_active' => true]);
                $message = 'Semester activated successfully (other semesters were deactivated).';
            }
        });

        return redirect()->back()->with('success', $message);
    }
}
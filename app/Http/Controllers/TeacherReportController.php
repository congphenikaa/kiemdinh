<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Faculty;
use App\Models\Degree;
use Illuminate\Http\Request;

class TeacherReportController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $filters = [
            'faculty' => $request->input('faculty'),
            'degree' => $request->input('degree'),
            'status' => $request->input('status', 'active') // Default to active
        ];

        // Base query for all filtered stats
        $filteredQuery = Teacher::query();

        // Apply filters to main query
        if ($filters['faculty']) {
            $filteredQuery->where('faculty_id', $filters['faculty']);
        }

        if ($filters['degree']) {
            $filteredQuery->where('degree_id', $filters['degree']);
        }

        if ($filters['status'] === 'active') {
            $filteredQuery->where('is_active', true);
        } elseif ($filters['status'] === 'inactive') {
            $filteredQuery->where('is_active', false);
        }

        // Get filtered counts
        $filteredStats = [
            'filtered_total' => $filteredQuery->count(),
            'filtered_active' => (clone $filteredQuery)->where('is_active', true)->count(),
            'filtered_inactive' => (clone $filteredQuery)->where('is_active', false)->count(),
        ];

        // Prepare statistics data (all filtered)
        $stats = [
            'total' => $filteredStats['filtered_total'],
            'active' => $filteredStats['filtered_active'],
            'inactive' => $filteredStats['filtered_inactive'],
            'byFaculty' => Faculty::withCount(['teachers' => function($q) use ($filters) {
                if ($filters['faculty']) {
                    $q->where('faculty_id', $filters['faculty']);
                }
                if ($filters['degree']) {
                    $q->where('degree_id', $filters['degree']);
                }
                if ($filters['status'] === 'active') {
                    $q->where('is_active', true);
                } elseif ($filters['status'] === 'inactive') {
                    $q->where('is_active', false);
                }
            }])->orderBy('name')->get(),
            'byDegree' => Degree::withCount(['teachers' => function($q) use ($filters) {
                if ($filters['faculty']) {
                    $q->where('faculty_id', $filters['faculty']);
                }
                if ($filters['degree']) {
                    $q->where('degree_id', $filters['degree']);
                }
                if ($filters['status'] === 'active') {
                    $q->where('is_active', true);
                } elseif ($filters['status'] === 'inactive') {
                    $q->where('is_active', false);
                }
            }])->orderBy('salary_coefficient', 'desc')->get()
        ];

        // Prepare filter dropdown data
        $filterData = [
            'faculties' => Faculty::orderBy('name')->get(),
            'degrees' => Degree::orderBy('salary_coefficient', 'desc')->get()
        ];

        return view('teacher-management.teacher-reports.index', array_merge(
            $stats,
            $filterData,
            [
                'currentFilters' => $filters,
                'hasNoResults' => $stats['total'] === 0
            ]
        ));
    }
}
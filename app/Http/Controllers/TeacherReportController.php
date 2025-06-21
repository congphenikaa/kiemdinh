<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Faculty;
use App\Models\Degree;
use Illuminate\Http\Request;

class TeacherReportController extends Controller
{
    // 1.5.1 - Trang thống kê chính
    public function index(Request $request)
    {
        // Lấy tham số filter
        $facultyId = $request->input('faculty');
        $degreeId = $request->input('degree');
        $status = $request->input('status', 'active');

        // Query cơ bản
        $query = Teacher::query()
            ->with(['faculty', 'degree', 'department']);

        // Áp dụng bộ lọc
        if ($facultyId) {
            $query->where('faculty_id', $facultyId);
        }

        if ($degreeId) {
            $query->where('degree_id', $degreeId);
        }

        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        // Thống kê tổng hợp
        $stats = [
            'total' => $query->count(),
            'active' => Teacher::where('is_active', true)->count(),
            'inactive' => Teacher::where('is_active', false)->count(),
            'byFaculty' => Faculty::withCount('teachers')->get(),
            'byDegree' => Degree::withCount('teachers')->get()
        ];

        // Dữ liệu cho dropdown filter
        $filterData = [
            'faculties' => Faculty::orderBy('name')->get(),
            'degrees' => Degree::orderBy('salary_coefficient', 'desc')->get()
        ];

        return view('teacher-management.teacher-reports.index', array_merge(
            $stats,
            $filterData,
            [
                'currentFilters' => [
                    'faculty' => $facultyId,
                    'degree' => $degreeId,
                    'status' => $status
                ]
            ]
        ));
    }
}
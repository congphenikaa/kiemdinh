<?php

namespace App\Http\Controllers;

use App\Models\Degree;
use App\Models\Faculty;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        $query = Teacher::with(['faculty', 'degree']);

        // Lọc theo khoa
        if ($request->has('faculty_id') && $request->faculty_id !== 'all') {
            $query->where('faculty_id', $request->faculty_id);
        }

        // Lọc theo năm
        if ($request->has('year') && $request->year !== 'all') {
            $query->whereYear('created_at', $request->year);
        }

        // Lọc theo bằng cấp
        if ($request->has('degree_id') && $request->degree_id !== 'all') {
            $query->where('degree_id', $request->degree_id);
        }

        $teachers = $query->get();
        $faculties = Faculty::all();
        $degrees = Degree::all();

        // Thống kê theo khoa
        $facultyStats = $teachers->groupBy('faculty.name')
            ->map(function ($group) {
                return $group->count();
            })->toArray();

        // Thống kê theo bằng cấp
        $degreeStats = $teachers->groupBy('degree.name')
            ->map(function ($group) {
                return $group->count();
            })->toArray();

        // Thống kê theo độ tuổi
        $ageStats = [
            'under_30' => $teachers->filter(function ($teacher) {
                return $teacher->dob->age < 30;
            })->count(),
            '30_to_40' => $teachers->filter(function ($teacher) {
                return $teacher->dob->age >= 30 && $teacher->dob->age < 40;
            })->count(),
            '40_to_50' => $teachers->filter(function ($teacher) {
                return $teacher->dob->age >= 40 && $teacher->dob->age < 50;
            })->count(),
            'over_50' => $teachers->filter(function ($teacher) {
                return $teacher->dob->age >= 50;
            })->count(),
        ];

        // Thống kê theo giới tính (giả sử có trường gender)
        $genderStats = [
            'male' => $teachers->where('gender', 'male')->count(),
            'female' => $teachers->where('gender', 'female')->count(),
        ];

        // Thống kê theo năm thêm vào
        $yearlyStats = $teachers->groupBy(function ($teacher) {
            return $teacher->created_at->format('Y');
        })->map(function ($group) {
            return $group->count();
        })->toArray();

        // Chuẩn bị dữ liệu cho biểu đồ
        $facultyData = [];
        foreach ($faculties as $faculty) {
            $facultyData[$faculty->name] = $teachers->where('faculty_id', $faculty->id)->count();
        }

        // Lấy danh sách năm để lọc
        $years = range(Carbon::now()->year - 5, Carbon::now()->year);
        
        return view('statistics.index', compact(
            'degrees', 
            'faculties', 
            'teachers',
            'facultyStats',
            'degreeStats',
            'facultyData',
            'ageStats',
            'genderStats',
            'yearlyStats',
            'years'
        ));
    }
} 
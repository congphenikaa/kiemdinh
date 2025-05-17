<?php

namespace App\Http\Controllers;

use App\Models\Degree;
use App\Models\Faculty;
use App\Models\Teacher;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $degrees = Degree::all();
        $faculties = Faculty::all();
        $teachers = Teacher::all();
        
        // Lấy 5 giáo viên được thêm gần đây nhất
        $recentTeachers = Teacher::with(['faculty', 'degree'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        return view('dashboard', compact('degrees', 'faculties', 'teachers', 'recentTeachers'));
    }
}

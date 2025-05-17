<?php

namespace App\Http\Controllers;

use App\Models\Degree;
use App\Models\Teacher;
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
            'name' => 'required|string|max:255|unique:degrees,name',
            'short_name' => 'required|string|max:50|unique:degrees,short_name'
        ], [
            'name.unique' => 'Tên bằng cấp đã tồn tại',
            'short_name.unique' => 'Tên viết tắt đã tồn tại'
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
            'name' => 'required|string|max:255|unique:degrees,name,'.$degree->id,
            'short_name' => 'required|string|max:50|unique:degrees,short_name,'.$degree->id
        ], [
            'name.unique' => 'Tên bằng cấp đã tồn tại',
            'short_name.unique' => 'Tên viết tắt đã tồn tại'
        ]);

        $degree->update($validated);

        return redirect()->route('degrees.index')
            ->with('success', 'Bằng cấp đã được cập nhật thành công.');
    }

    public function destroy(Degree $degree)
    {
        // Kiểm tra xem có giảng viên nào đang sử dụng bằng cấp này không
        if (Teacher::where('degree_id', $degree->id)->exists()) {
            return redirect()->route('degrees.index')
                ->with('error', 'Không thể xóa bằng cấp vì có giảng viên đang sử dụng.');
        }

        $degree->delete();

        return redirect()->route('degrees.index')
            ->with('success', 'Bằng cấp đã được xóa thành công.');
    }
}
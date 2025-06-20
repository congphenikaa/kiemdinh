<?php

namespace App\Http\Controllers;

use App\Models\Clazz;
use App\Models\Course;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ScheduleController extends Controller
{
    /**
     * Hiển thị danh sách lịch học
     */
    public function index(Request $request)
    {
        $query = Schedule::with(['class.course', 'class.semester.academicYear'])
            ->orderBy('date', 'desc')
            ->orderBy('start_time');

        // Lọc theo lớp học
        if ($request->has('class_id') && $request->class_id) {
            $query->where('class_id', $request->class_id);
        }

        // Lọc theo ngày
        if ($request->has('date') && $request->date) {
            $query->whereDate('date', $request->date);
        }

        // Lọc theo trạng thái
        if ($request->has('is_taught') && in_array($request->is_taught, ['0', '1'])) {
            $query->where('is_taught', $request->is_taught);
        }

        $schedules = $query->paginate(20);
        $classes = Clazz::with('course')->orderBy('class_code')->get();

        return view('class-management.schedules.index', compact('schedules', 'classes'));
    }

    /**
     * Hiển thị form tạo lịch học mới
     */
    public function create()
    {
        $classes = Clazz::with(['course', 'semester.academicYear'])
            ->where('status', 'open') // Chỉ hiển thị lớp có trạng thái mở
            ->orderBy('class_code')
            ->get();

        return view('class-management.schedules.create', compact('classes'));
    }

    /**
     * Lưu lịch học mới (tạo nhiều buổi cùng lúc)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'days_of_week' => 'required|array|min:1',
            'days_of_week.*' => 'integer|between:0,6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        try {
            DB::beginTransaction();

            $class = Clazz::with('course')->findOrFail($validated['class_id']);
            $totalSessions = $class->course->total_sessions;
            
            // Kiểm tra số buổi hiện có của lớp
            $existingSessions = Schedule::where('class_id', $validated['class_id'])->count();
            $remainingSessions = $totalSessions - $existingSessions;
            
            if ($remainingSessions <= 0) {
                return back()->withInput()
                    ->with('error', 'Lớp học này đã có đủ số buổi theo quy định của môn học.');
            }

            $startDate = Carbon::parse($validated['start_date']);
            $endDate = Carbon::parse($validated['end_date']);
            $currentDate = $startDate->copy();
            $sessionNumber = $existingSessions + 1; // Bắt đầu từ buổi tiếp theo
            $createdSessions = 0;

            // Tạo lịch học cho đến khi đủ số buổi hoặc hết khoảng thời gian
            while ($sessionNumber <= $totalSessions && $currentDate <= $endDate) {
                if (in_array($currentDate->dayOfWeek, $validated['days_of_week'])) {
                    $conflict = Schedule::where('class_id', $validated['class_id'])
                        ->where('date', $currentDate->format('Y-m-d'))
                        ->exists();

                    if (!$conflict) {
                        Schedule::create([
                            'class_id' => $validated['class_id'],
                            'day_of_week' => $currentDate->dayOfWeek,
                            'start_time' => $validated['start_time'],
                            'end_time' => $validated['end_time'],
                            'date' => $currentDate->format('Y-m-d'),
                            'session_number' => $sessionNumber,
                            'is_taught' => false
                        ]);

                        $sessionNumber++;
                        $createdSessions++;
                    }
                }

                $currentDate->addDay();
            }

            DB::commit();

            if ($createdSessions < $remainingSessions) {
                return redirect()->route('schedules.index')
                    ->with('warning', "Đã tạo $createdSessions/$remainingSessions buổi học còn lại. Không đủ ngày học trong khoảng thời gian hoặc có lịch trùng.");
            }

            return redirect()->route('schedules.index')
                ->with('success', "Đã tạo thành công $createdSessions buổi học.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Lỗi khi tạo lịch học: ' . $e->getMessage());
        }
    }

    /**
     * Hiển thị form chỉnh sửa lịch học
     */
    public function edit(Schedule $schedule)
    {
        $classes = Clazz::with(['course', 'semester.academicYear'])
            ->where('status', 'open')
            ->orderBy('class_code')
            ->get();

        return view('class-management.schedules.edit', compact('schedule', 'classes'));
    }

    /**
     * Cập nhật lịch học
     */
    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'day_of_week' => 'required|integer|between:0,6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'date' => 'required|date',
            'session_number' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('schedules')->ignore($schedule->id)->where(function ($query) use ($request) {
                    return $query->where('class_id', $request->class_id)
                                ->where('date', $request->date);
                })
            ],
            'is_taught' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            $schedule->update($validated);

            DB::commit();

            return redirect()->route('schedules.index')
                ->with('success', 'Cập nhật lịch học thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Lỗi khi cập nhật lịch học: ' . $e->getMessage());
        }
    }

    /**
     * Xóa lịch học
     */
    public function destroy(Schedule $schedule)
    {
        try {
            DB::beginTransaction();

            $schedule->delete();

            DB::commit();

            return redirect()->route('schedules.index')
                ->with('success', 'Xóa lịch học thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi khi xóa lịch học: ' . $e->getMessage());
        }
    }

    /**
     * API lấy thông tin lớp học để hiển thị số buổi cần tạo
     */
    public function getClassInfo($classId)
    {
        $class = Clazz::with('course')->findOrFail($classId);
        
        return response()->json([
            'total_sessions' => $class->course->total_sessions,
            'class_code' => $class->class_code,
            'course_name' => $class->course->name
        ]);
    }

    /**
     * Đánh dấu lịch học đã dạy/chưa dạy
     */
    public function toggleTaughtStatus(Schedule $schedule)
    {
        $schedule->update(['is_taught' => !$schedule->is_taught]);
        
        return response()->json([
            'success' => true,
            'is_taught' => $schedule->is_taught
        ]);
    }
}
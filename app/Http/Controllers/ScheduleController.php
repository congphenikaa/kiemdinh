<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Clazz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with('class')->orderBy('date', 'desc')->paginate(10);
        return view('class-management.schedules.index', compact('schedules'));
    }

    public function create()
    {
        $classes = Clazz::with('course')->get();
        return view('class-management.schedules.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'start_date' => 'required|date|after_or_equal:today',
            'total_sessions' => 'required|integer|min:1|max:100',
            'day_of_week' => 'required|array|min:1',
            'day_of_week.*' => 'integer|between:2,7',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'session_type' => 'required|in:theory,practice,exam,review',
        ]);

        try {
            DB::beginTransaction();

            $class = Clazz::findOrFail($validated['class_id']);
            $startDate = Carbon::parse($validated['start_date']);
            $sessionsCreated = 0;
            $currentDate = $startDate->copy();
            $scheduleData = [];

            while ($sessionsCreated < $validated['total_sessions']) {
                // Kiểm tra nếu ngày hiện tại là ngày học được chọn
                if (in_array($currentDate->dayOfWeekIso, $validated['day_of_week'])) {
                    $scheduleData[] = [
                        'class_id' => $validated['class_id'],
                        'day_of_week' => $currentDate->dayOfWeekIso,
                        'date' => $currentDate->format('Y-m-d'),
                        'start_time' => $validated['start_time'],
                        'end_time' => $validated['end_time'],
                        'session_type' => $validated['session_type'],
                        'session_number' => $sessionsCreated + 1,
                        'is_cancelled' => false,
                        'is_taught' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    $sessionsCreated++;
                }

                // Chuyển sang ngày tiếp theo
                $currentDate->addDay();

                // Bảo vệ trường hợp vòng lặp vô hạn
                if ($currentDate->diffInDays($startDate) > 365) {
                    throw new \Exception('Không thể tạo đủ số buổi học trong vòng 1 năm');
                }
            }

            // Chèn hàng loạt vào database
            Schedule::insert($scheduleData);

            DB::commit();

            return redirect()->route('schedules.index')
                ->with('success', 'Đã tạo thành công ' . $sessionsCreated . ' buổi học cho lớp ' . $class->class_code);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Lỗi khi tạo lịch học: ' . $e->getMessage());
        }
    }

   public function edit(Schedule $schedule)
    {
        $classes = Clazz::with('course')->get();
        $daysOfWeek = [
            2 => 'Thứ 2',
            3 => 'Thứ 3',
            4 => 'Thứ 4',
            5 => 'Thứ 5',
            6 => 'Thứ 6',
            7 => 'Thứ 7'
        ];
        
        return view('class-management.schedules.edit', compact('schedule', 'classes', 'daysOfWeek'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'day_of_week' => 'required|integer|between:2,7',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'date' => 'required|date',
            'session_type' => 'required|in:theory,practice,exam,review',
            'is_cancelled' => 'sometimes|boolean',
            'cancellation_reason' => 'nullable|required_if:is_cancelled,true|string|max:255',
            'is_taught' => 'sometimes|boolean'
        ]);

        try {
            DB::beginTransaction();

            // Nếu buổi học bị hủy nhưng không có lý do
            if ($validated['is_cancelled'] && empty($validated['cancellation_reason'])) {
                throw new \Exception('Vui lòng nhập lý do hủy buổi học');
            }

            // Nếu buổi học đã được dạy thì không thể hủy
            if ($schedule->is_taught && $validated['is_cancelled']) {
                throw new \Exception('Không thể hủy buổi học đã được dạy');
            }

            // Cập nhật thông tin
            $schedule->update($validated);

            DB::commit();

            return redirect()->route('schedules.index')
                ->with('success', 'Cập nhật thông tin buổi học thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Lỗi khi cập nhật: ' . $e->getMessage());
        }
    }

    public function destroy(Schedule $schedule)
    {
        try {
            DB::beginTransaction();

            $schedule->delete();

            DB::commit();

            return redirect()->route('schedules.index')
                ->with('success', 'Schedule deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting schedule: ' . $e->getMessage());
        }
    }
}
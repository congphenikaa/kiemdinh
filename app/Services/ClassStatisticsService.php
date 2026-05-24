<?php

namespace App\Services;

use App\Models\Clazz;
use App\Models\ClassStatistics;

class ClassStatisticsService
{
    public function syncForClass(Clazz|int $class): ClassStatistics
    {
        if (is_int($class)) {
            $class = Clazz::with(['schedules', 'course'])->findOrFail($class);
        } else {
            $class->loadMissing(['schedules', 'course']);
        }

        $taught = $class->schedules->filter(fn ($schedule) => $this->isTaught($schedule->is_taught))->count();
        $scheduled = $class->schedules->count();

        $averageAttendance = $this->calculateAttendanceRate($taught, $scheduled, $class->course?->total_sessions);

        return ClassStatistics::updateOrCreate(
            ['class_id' => $class->id],
            [
                'total_sessions_taught' => $taught,
                'average_attendance' => $averageAttendance,
            ]
        );
    }

    /**
     * @return array{completedSessions: int, avgAttendance: float}
     */
    public function aggregateForClassQuery($classQuery): array
    {
        $classes = (clone $classQuery)
            ->with('course:id,total_sessions')
            ->withCount([
                'schedules as schedules_count',
                'schedules as taught_schedules_count' => fn ($q) => $q->where('is_taught', true),
            ])
            ->get(['id', 'course_id']);

        $completedSessions = (int) $classes->sum('taught_schedules_count');

        $rates = $classes->map(function (Clazz $class) {
            return $this->calculateAttendanceRate(
                (int) $class->taught_schedules_count,
                (int) $class->schedules_count,
                $class->course?->total_sessions
            );
        });

        return [
            'completedSessions' => $completedSessions,
            'avgAttendance' => round((float) ($rates->avg() ?? 0), 2),
        ];
    }

    private function calculateAttendanceRate(int $taught, int $scheduledInDb, ?int $courseTotalSessions): float
    {
        if ($scheduledInDb > 0) {
            return round(($taught / $scheduledInDb) * 100, 2);
        }

        if ($courseTotalSessions && $courseTotalSessions > 0) {
            return round(($taught / $courseTotalSessions) * 100, 2);
        }

        return 0;
    }

    private function isTaught(mixed $value): bool
    {
        return in_array($value, [true, 1, '1'], true);
    }
}

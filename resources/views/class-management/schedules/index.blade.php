@extends('templates.index', [
    'entityName' => 'Lịch học',
    'routePrefix' => 'schedules'
])

@section('table_headers')
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STT</th>
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lớp học</th>
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày học</th>
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thứ</th>
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thời gian</th>
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Buổi học</th>
    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
    <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
@endsection

@section('table_rows')
    @forelse($schedules as $index => $schedule)
    <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-gray-100 transition-colors">
        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
            {{ ($schedules->currentPage() - 1) * $schedules->perPage() + $index + 1 }}
        </td>
        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                    <i class="fas fa-chalkboard-teacher text-blue-600"></i>
                </div>
                <div>
                    {{ $schedule->class->class_code }}
                    <div class="text-gray-500 text-xs mt-1">
                        {{ $schedule->class->course->name ?? '' }}
                    </div>
                </div>
            </div>
        </td>
        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
            {{ \Carbon\Carbon::parse($schedule->date)->format('d/m/Y') }}
        </td>
        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
            {{ 'Thứ ' . ($schedule->day_of_week + 1) }}
        </td>
        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
            {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - 
            {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
        </td>
        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
            Buổi {{ $schedule->session_number }} ({{ $schedule->session_type }})
        </td>
        <td class="px-4 py-4 whitespace-nowrap text-sm">
            @if($schedule->is_cancelled)
                <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">
                    <i class="fas fa-times-circle mr-1"></i> Đã hủy
                </span>
                @if($schedule->cancellation_reason)
                    <div class="text-gray-500 text-xs mt-1" title="{{ $schedule->cancellation_reason }}">
                        {{ Str::limit($schedule->cancellation_reason, 30) }}
                    </div>
                @endif
            @elseif($schedule->is_taught)
                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                    <i class="fas fa-check-circle mr-1"></i> Đã dạy
                </span>
            @else
                @if(\Carbon\Carbon::parse($schedule->date)->isPast())
                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">
                        <i class="fas fa-exclamation-circle mr-1"></i> Chưa dạy
                    </span>
                @else
                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                        <i class="fas fa-clock mr-1"></i> Sắp diễn ra
                    </span>
                @endif
            @endif
        </td>
        <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
            <a href="{{ route('schedules.edit', $schedule) }}" 
               class="inline-flex items-center px-3 py-1 border border-blue-300 rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors"
               title="Chỉnh sửa">
                <i class="fas fa-edit mr-1"></i> Sửa
            </a>
            <button class="btn-delete inline-flex items-center px-3 py-1 border border-red-300 rounded-md text-red-700 bg-red-50 hover:bg-red-100 transition-colors" 
                    data-id="{{ $schedule->id }}"
                    title="Xóa">
                <i class="fas fa-trash-alt mr-1"></i> Xóa
            </button>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="8" class="px-6 py-12 text-center">
            <div class="flex flex-col items-center justify-center">
                <i class="fas fa-calendar-day text-4xl text-gray-300 mb-4"></i>
                <h4 class="text-lg font-medium text-gray-500">Không có lịch học nào</h4>
                <p class="text-gray-400 mt-1">Nhấn "Thêm mới" để tạo lịch học đầu tiên</p>
                <a href="{{ route('schedules.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i> Thêm lịch học
                </a>
            </div>
        </td>
    </tr>
    @endforelse
@endsection
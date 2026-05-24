@php
    $userInitials = collect(explode(' ', Auth::user()->name ?? 'A'))
        ->filter()
        ->take(2)
        ->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))
        ->implode('');
@endphp

<aside
    id="sidebar"
    class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col border-r border-slate-800 bg-slate-900 text-slate-200 transition-transform duration-200 ease-in-out lg:static lg:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
>
    <div class="flex h-16 shrink-0 items-center gap-3 border-b border-slate-800 px-5">
        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary-600 text-sm font-bold text-white">
            QL
        </div>
        <div class="min-w-0">
            <p class="truncate text-sm font-semibold text-white">QL Giảng dạy</p>
            <p class="truncate text-xs text-slate-400">Hệ thống kiểm định</p>
        </div>
    </div>

    <nav class="flex-1 space-y-1 overflow-y-auto p-3">
        <a href="{{ route('dashboard') }}"
           class="sidebar-link {{ request()->routeIs('dashboard') ? 'sidebar-link-active' : '' }}">
            <i class="fas fa-chart-pie w-5 text-center text-slate-400"></i>
            <span>Tổng quan</span>
        </a>

        {{-- Giáo viên --}}
        <div class="menu-parent {{ request()->routeIs('teachers*', 'degrees*', 'faculties*', 'teacher-reports*') ? 'active' : '' }}">
            <button type="button" class="parent-item sidebar-link w-full {{ request()->routeIs('teachers*', 'degrees*', 'faculties*', 'teacher-reports*') ? 'sidebar-link-active' : '' }}">
                <i class="fas fa-chalkboard-teacher w-5 text-center text-slate-400"></i>
                <span class="flex-1 text-left">Giáo viên</span>
                <i class="dropdown-icon fas fa-chevron-down text-xs text-slate-500 transition-transform"></i>
            </button>
            <ul class="child-menu mt-1 space-y-0.5 overflow-hidden" style="max-height: 0">
                <li>
                    <a href="{{ route('degrees.index') }}" class="sidebar-sublink {{ request()->routeIs('degrees.*') ? 'sidebar-sublink-active' : '' }}">
                        Bằng cấp
                    </a>
                </li>
                <li>
                    <a href="{{ route('faculties.index') }}" class="sidebar-sublink {{ request()->routeIs('faculties.*') ? 'sidebar-sublink-active' : '' }}">
                        Khoa
                    </a>
                </li>
                <li>
                    <a href="{{ route('teachers.index') }}" class="sidebar-sublink {{ request()->routeIs('teachers.*') ? 'sidebar-sublink-active' : '' }}">
                        Giảng viên
                    </a>
                </li>
                <li>
                    <a href="{{ route('teacher-reports.index') }}" class="sidebar-sublink {{ request()->routeIs('teacher-reports.*') ? 'sidebar-sublink-active' : '' }}">
                        Thống kê
                    </a>
                </li>
            </ul>
        </div>

        {{-- Lớp học --}}
        <div class="menu-parent {{ request()->routeIs('classes*', 'courses*', 'semesters*', 'schedules*', 'academic-years*', 'class-reports*', 'teaching-assignments*') ? 'active' : '' }}">
            <button type="button" class="parent-item sidebar-link w-full {{ request()->routeIs('classes*', 'courses*', 'semesters*', 'schedules*', 'academic-years*', 'class-reports*', 'teaching-assignments*') ? 'sidebar-link-active' : '' }}">
                <i class="fas fa-school w-5 text-center text-slate-400"></i>
                <span class="flex-1 text-left">Lớp học</span>
                <i class="dropdown-icon fas fa-chevron-down text-xs text-slate-500 transition-transform"></i>
            </button>
            <ul class="child-menu mt-1 space-y-0.5 overflow-hidden" style="max-height: 0">
                <li><a href="{{ route('courses.index') }}" class="sidebar-sublink {{ request()->routeIs('courses.*') ? 'sidebar-sublink-active' : '' }}">Học phần</a></li>
                <li><a href="{{ route('academic-years.index') }}" class="sidebar-sublink {{ request()->routeIs('academic-years.*') ? 'sidebar-sublink-active' : '' }}">Năm học</a></li>
                <li><a href="{{ route('semesters.index') }}" class="sidebar-sublink {{ request()->routeIs('semesters.*') ? 'sidebar-sublink-active' : '' }}">Kỳ học</a></li>
                <li><a href="{{ route('classes.index') }}" class="sidebar-sublink {{ request()->routeIs('classes.*') ? 'sidebar-sublink-active' : '' }}">Lớp học</a></li>
                <li><a href="{{ route('teaching-assignments.index') }}" class="sidebar-sublink {{ request()->routeIs('teaching-assignments.*') ? 'sidebar-sublink-active' : '' }}">Phân công</a></li>
                <li><a href="{{ route('schedules.index') }}" class="sidebar-sublink {{ request()->routeIs('schedules.*') ? 'sidebar-sublink-active' : '' }}">Thời khóa biểu</a></li>
                <li><a href="{{ route('class-reports.index') }}" class="sidebar-sublink {{ request()->routeIs('class-reports.*') ? 'sidebar-sublink-active' : '' }}">Thống kê lớp</a></li>
            </ul>
        </div>

        {{-- Thanh toán --}}
        <div class="menu-parent {{ request()->routeIs('payment-calculations*', 'payment-configs*', 'payment-batches*', 'class-size-coefficients*') ? 'active' : '' }}">
            <button type="button" class="parent-item sidebar-link w-full {{ request()->routeIs('payment-calculations*', 'payment-configs*', 'payment-batches*', 'class-size-coefficients*') ? 'sidebar-link-active' : '' }}">
                <i class="fas fa-wallet w-5 text-center text-slate-400"></i>
                <span class="flex-1 text-left">Thanh toán</span>
                <i class="dropdown-icon fas fa-chevron-down text-xs text-slate-500 transition-transform"></i>
            </button>
            <ul class="child-menu mt-1 space-y-0.5 overflow-hidden" style="max-height: 0">
                <li><a href="{{ route('payment-configs.index') }}" class="sidebar-sublink {{ request()->routeIs('payment-configs.*') ? 'sidebar-sublink-active' : '' }}">Mức lương</a></li>
                <li><a href="{{ route('class-size-coefficients.index') }}" class="sidebar-sublink {{ request()->routeIs('class-size-coefficients.*') ? 'sidebar-sublink-active' : '' }}">Hệ số sĩ số</a></li>
                <li><a href="{{ route('payment-calculations.index') }}" class="sidebar-sublink {{ request()->routeIs('payment-calculations.*') ? 'sidebar-sublink-active' : '' }}">Tính toán</a></li>
                <li><a href="{{ route('payment-batches.index') }}" class="sidebar-sublink {{ request()->routeIs('payment-batches.*') ? 'sidebar-sublink-active' : '' }}">Đợt thanh toán</a></li>
            </ul>
        </div>

        {{-- Báo cáo --}}
        <div class="menu-parent {{ request()->routeIs('reports*') ? 'active' : '' }}">
            <button type="button" class="parent-item sidebar-link w-full {{ request()->routeIs('reports*') ? 'sidebar-link-active' : '' }}">
                <i class="fas fa-chart-line w-5 text-center text-slate-400"></i>
                <span class="flex-1 text-left">Báo cáo</span>
                <i class="dropdown-icon fas fa-chevron-down text-xs text-slate-500 transition-transform"></i>
            </button>
            <ul class="child-menu mt-1 space-y-0.5 overflow-hidden" style="max-height: 0">
                <li><a href="{{ route('reports.teacher-payments') }}" class="sidebar-sublink {{ request()->routeIs('reports.teacher-payments') ? 'sidebar-sublink-active' : '' }}">Tiền dạy GV</a></li>
                <li><a href="{{ route('reports.faculty-payments') }}" class="sidebar-sublink {{ request()->routeIs('reports.faculty-payments') ? 'sidebar-sublink-active' : '' }}">Tiền dạy khoa</a></li>
                <li><a href="{{ route('reports.summary') }}" class="sidebar-sublink {{ request()->routeIs('reports.summary') ? 'sidebar-sublink-active' : '' }}">Tổng hợp</a></li>
            </ul>
        </div>
    </nav>

    <div class="border-t border-slate-800 p-4">
        <div class="flex items-center gap-3">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary-600 text-xs font-semibold text-white">
                {{ $userInitials ?: 'A' }}
            </div>
            <div class="min-w-0 flex-1">
                <p class="truncate text-sm font-medium text-white">{{ Auth::user()->name ?? 'Admin' }}</p>
                <p class="truncate text-xs text-slate-400">{{ Auth::user()->email ?? '' }}</p>
            </div>
        </div>
    </div>
</aside>

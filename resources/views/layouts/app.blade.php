<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống QLDT</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .child-menu {
            transition: max-height 0.3s ease-in-out;
        }
        .sidebar-mobile-hidden {
            transform: translateX(-100%);
        }
        @media (min-width: 1024px) {
            .sidebar-mobile-hidden {
                transform: translateX(0);
            }
        }
        .menu-parent.active > .parent-item {
            background-color: #1E40AF;
        }
        .child-menu li:hover {
            background-color: #4B5563 !important;
        }

    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div id="sidebar" class="w-64 bg-gray-800 text-gray-100 h-screen fixed shadow-lg lg:static lg:translate-x-0 transform transition-transform duration-200 ease-in-out sidebar-mobile-hidden">
            <div class="p-5 border-b border-gray-700 text-center">
                <h2 class="text-white text-xl font-semibold">Hệ thống quản lý</h2>
            </div>

            <ul class="space-y-1 p-2">
                <!-- Dashboard -->
                <li class="{{ request()->routeIs('dashboard') ? 'bg-blue-600' : '' }}">
                    <a href="{{ route('dashboard') }}" class="flex items-center p-3 hover:bg-gray-700 rounded transition-colors">
                        <i class="fas fa-tachometer-alt w-5 mr-3 text-center"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- 1. Quản lý giáo viên -->
                <li class="menu-parent {{ request()->routeIs('teachers*', 'degrees*', 'faculties*', 'teacher-reports*') ? 'active open' : '' }}">
                    <div class="parent-item flex items-center justify-between p-3 hover:bg-gray-700 rounded cursor-pointer transition-colors">
                        <div class="flex items-center">
                            <i class="fas fa-chalkboard-teacher w-5 mr-3 text-center"></i>
                            <span>1. Quản lý giáo viên</span>
                        </div>
                        <i class="dropdown-icon fas fa-chevron-down text-xs transition-transform"></i>
                    </div>
                    <ul class="child-menu bg-gray-700 overflow-hidden max-h-0 rounded">
                        <!-- Danh mục bằng cấp -->
                        <li class="{{ request()->routeIs('degrees.*') ? 'bg-blue-600' : '' }}">
                            <a href="{{ route('degrees.index') }}" class="flex items-center p-3 pl-11 hover:bg-gray-600 rounded transition-colors">
                                <i class="fas fa-graduation-cap w-5 mr-3 text-center"></i>
                                <span>1.1. Danh mục bằng cấp</span>
                            </a>
                        </li>
                        
                        <!-- Quản lý khoa -->
                        <li class="{{ request()->routeIs('faculties.*') ? 'bg-blue-600' : '' }}">
                            <a href="{{ route('faculties.index') }}" class="flex items-center p-3 pl-11 hover:bg-gray-600 rounded transition-colors">
                                <i class="fas fa-university w-5 mr-3 text-center"></i>
                                <span>1.2. Quản lý khoa</span>
                            </a>
                        </li>
                        
                        <!-- Quản lý giáo viên -->
                        <li class="{{ request()->routeIs('teachers.*') ? 'bg-blue-600' : '' }}">
                            <a href="{{ route('teachers.index') }}" class="flex items-center p-3 pl-11 hover:bg-gray-600 rounded transition-colors">
                                <i class="fas fa-user-tie w-5 mr-3 text-center"></i>
                                <span>1.3. Quản lý giáo viên</span>
                            </a>
                        </li>
                        
                        <!-- Thống kê giáo viên -->
                        <li class="{{ request()->routeIs('teacher-reports.*') ? 'bg-blue-600' : '' }}">
                            <a href="{{ route('teacher-reports.index') }}" class="flex items-center p-3 pl-11 hover:bg-gray-600 rounded transition-colors">
                                <i class="fas fa-chart-bar w-5 mr-3 text-center"></i>
                                <span>1.4. Thống kê giáo viên</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- 2. Quản lý lớp học -->
                <li class="menu-parent {{ request()->routeIs('classes*', 'courses*', 'semesters*', 'schedules*', 'academic-years*', 'class-reports*', 'teaching-assignments*') ? 'active open' : '' }}">
                    <div class="parent-item flex items-center justify-between p-3 hover:bg-gray-700 rounded cursor-pointer transition-colors">
                        <div class="flex items-center">
                            <i class="fas fa-chalkboard w-5 mr-3 text-center"></i>
                            <span>2. Quản lý lớp học</span>
                        </div>
                        <i class="dropdown-icon fas fa-chevron-down text-xs transition-transform"></i>
                    </div>
                    <ul class="child-menu bg-gray-700 overflow-hidden max-h-0 rounded">
                        <!-- Quản lý học phần -->
                        <li class="{{ request()->routeIs('courses.*') ? 'bg-blue-600' : '' }}">
                            <a href="{{ route('courses.index') }}" class="flex items-center p-3 pl-11 hover:bg-gray-600 rounded transition-colors">
                                <i class="fas fa-book w-5 mr-3 text-center"></i>
                                <span>2.1. Quản lý học phần</span>
                            </a>
                        </li>
                        
                        <!-- Quản lý năm học -->
                        <li class="{{ request()->routeIs('academic-years.*') ? 'bg-blue-600' : '' }}">
                            <a href="{{ route('academic-years.index') }}" class="flex items-center p-3 pl-11 hover:bg-gray-600 rounded transition-colors">
                                <i class="fas fa-calendar-alt w-5 mr-3 text-center"></i>
                                <span>2.2. Quản lý năm học</span>
                            </a>
                        </li>
                        
                        <!-- Quản lý kỳ học -->
                        <li class="{{ request()->routeIs('semesters.*') ? 'bg-blue-600' : '' }}">
                            <a href="{{ route('semesters.index') }}" class="flex items-center p-3 pl-11 hover:bg-gray-600 rounded transition-colors">
                                <i class="fas fa-calendar-week w-5 mr-3 text-center"></i>
                                <span>2.3. Quản lý kỳ học</span>
                            </a>
                        </li>
                        
                        <!-- Quản lý lớp học -->
                        <li class="{{ request()->routeIs('classes.*') ? 'bg-blue-600' : '' }}">
                            <a href="{{ route('classes.index') }}" class="flex items-center p-3 pl-11 hover:bg-gray-600 rounded transition-colors">
                                <i class="fas fa-users w-5 mr-3 text-center"></i>
                                <span>2.4. Quản lý lớp học</span>
                            </a>
                        </li>

                        <!-- Phân công giảng dạy -->
                        <li class="{{ request()->routeIs('teaching-assignments.*') ? 'bg-blue-600' : '' }}">
                            <a href="{{ route('teaching-assignments.index') }}" class="flex items-center p-3 pl-11 hover:bg-gray-600 rounded transition-colors">
                                <i class="fas fa-tasks w-5 mr-3 text-center"></i>
                                <span>2.5. Phân công giảng dạy</span>
                            </a>
                        </li>
                        
                        <!-- Quản lý TKB -->
                        <li class="{{ request()->routeIs('schedules.*') ? 'bg-blue-600' : '' }}">
                            <a href="{{ route('schedules.index') }}" class="flex items-center p-3 pl-11 hover:bg-gray-600 rounded transition-colors">
                                <i class="fas fa-calendar-day w-5 mr-3 text-center"></i>
                                <span>2.6. Quản lý thời khóa biểu</span>
                            </a>
                        </li>
                        
                        <!-- Thống kê lớp học -->
                        <li class="{{ request()->routeIs('class-reports.*') ? 'bg-blue-600' : '' }}">
                            <a href="{{ route('class-reports.index') }}" class="flex items-center p-3 pl-11 hover:bg-gray-600 rounded transition-colors">
                                <i class="fas fa-chart-pie w-5 mr-3 text-center"></i>
                                <span>2.7. Thống kê lớp học</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- 3. Tính tiền dạy -->
                <li class="menu-parent {{ request()->routeIs('payment-calculations*', 'payment-configs*', 'payment-batches*', 'class-size-coefficients*') ? 'active open' : '' }}">
                    <div class="parent-item flex items-center justify-between p-3 hover:bg-gray-700 rounded cursor-pointer transition-colors">
                        <div class="flex items-center">
                            <i class="fas fa-money-bill-wave w-5 mr-3 text-center"></i>
                            <span>3. Tính tiền dạy</span>
                        </div>
                        <i class="dropdown-icon fas fa-chevron-down text-xs transition-transform"></i>
                    </div>
                    <ul class="child-menu bg-gray-700 overflow-hidden max-h-0 rounded">
                        <!-- Thiết lập mức lương -->
                        <li class="{{ request()->routeIs('payment-configs.*') ? 'bg-blue-600' : '' }}">
                            <a href="{{ route('payment-configs.index') }}" class="flex items-center p-3 pl-11 hover:bg-gray-600 rounded transition-colors">
                                <i class="fas fa-cog w-5 mr-3 text-center"></i>
                                <span>3.1. Thiết lập mức lương</span>
                            </a>
                        </li>
                        
                        <!-- Thiết lập hệ số lớp -->
                        <li class="{{ request()->routeIs('class-size-coefficients.*') ? 'bg-blue-600' : '' }}">
                            <a href="{{ route('class-size-coefficients.index') }}" class="flex items-center p-3 pl-11 hover:bg-gray-600 rounded transition-colors">
                                <i class="fas fa-sliders-h w-5 mr-3 text-center"></i>
                                <span>3.2. Hệ số sĩ số lớp</span>
                            </a>
                        </li>
                        
                        <!-- Tính toán thanh toán -->
                        <li class="{{ request()->routeIs('payment-calculations.*') ? 'bg-blue-600' : '' }}">
                            <a href="{{ route('payment-calculations.index') }}" class="flex items-center p-3 pl-11 hover:bg-gray-600 rounded transition-colors">
                                <i class="fas fa-calculator w-5 mr-3 text-center"></i>
                                <span>3.3. Tính toán thanh toán</span>
                            </a>
                        </li>

                        <!-- Quản lý đợt thanh toán -->
                        <li class="{{ request()->routeIs('payment-batches.*') ? 'bg-blue-600' : '' }}">
                            <a href="{{ route('payment-batches.index') }}" class="flex items-center p-3 pl-11 hover:bg-gray-600 rounded transition-colors">
                                <i class="fas fa-file-invoice-dollar w-5 mr-3 text-center"></i>
                                <span>3.4. Đợt thanh toán</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- 4. Báo cáo -->
                <li class="menu-parent {{ request()->routeIs('reports*') ? 'active open' : '' }}">
                    <div class="parent-item flex items-center justify-between p-3 hover:bg-gray-700 rounded cursor-pointer transition-colors">
                        <div class="flex items-center">
                            <i class="fas fa-file-alt w-5 mr-3 text-center"></i>
                            <span>4. Báo cáo</span>
                        </div>
                        <i class="dropdown-icon fas fa-chevron-down text-xs transition-transform"></i>
                    </div>
                    <ul class="child-menu bg-gray-700 overflow-hidden max-h-0 rounded">
                        <!-- Báo cáo tiền dạy giảng viên -->
                        <li class="{{ request()->routeIs('reports.teacher-payments') ? 'bg-blue-600' : '' }}">
                            <a href="{{ route('reports.teacher-payments') }}" class="flex items-center p-3 pl-11 hover:bg-gray-600 rounded transition-colors">
                                <i class="fas fa-user-graduate w-5 mr-3 text-center"></i>
                                <span>4.1. Tiền dạy giảng viên</span>
                            </a>
                        </li>
                        
                        <!-- Báo cáo tiền dạy khoa -->
                        <li class="{{ request()->routeIs('reports.faculty-payments') ? 'bg-blue-600' : '' }}">
                            <a href="{{ route('reports.faculty-payments') }}" class="flex items-center p-3 pl-11 hover:bg-gray-600 rounded transition-colors">
                                <i class="fas fa-university w-5 mr-3 text-center"></i>
                                <span>4.2. Tiền dạy theo khoa</span>
                            </a>
                        </li>
                        
                        <!-- Báo cáo tổng hợp -->
                        <li class="{{ request()->routeIs('reports.summary') ? 'bg-blue-600' : '' }}">
                            <a href="{{ route('reports.summary') }}" class="flex items-center p-3 pl-11 hover:bg-gray-600 rounded transition-colors">
                                <i class="fas fa-chart-line w-5 mr-3 text-center"></i>
                                <span>4.3. Báo cáo tổng hợp</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden lg:ml-0">
            <!-- Header -->
            <header class="bg-white shadow-sm z-10">
                <div class="flex items-center justify-between p-4">
                    <div class="flex items-center">
                        <!-- Mobile menu button -->
                        <button id="mobile-menu-button" class="lg:hidden mr-4 text-gray-600 hover:text-gray-900">
                            <i class="fas fa-bars"></i>
                        </button>
                        <div>
                            <h2 class="text-xl font-semibold text-gray-800">@yield('title', 'Dashboard')</h2>
                            <div class="text-sm text-gray-500">
                                @yield('breadcrumb', 'Trang chủ')
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <img src="{{ asset('image/user.jpg') }}" alt="User" class="w-8 h-8 rounded-full object-cover">
                            <span class="ml-2 text-gray-700">Admin</span>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center text-gray-600 hover:text-gray-900">
                                <i class="fas fa-sign-out-alt mr-1"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <main class="flex-1 overflow-y-auto p-4 bg-gray-50">
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
                        <p>{{ session('error') }}</p>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Modal for confirmation -->
    <div id="confirm-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full">
            <h2 id="modal-title" class="text-lg font-semibold mb-4">Xác nhận</h2>
            <p id="modal-message" class="text-gray-600 mb-6">Bạn có chắc chắn muốn xóa?</p>
            <div class="flex justify-end space-x-3">
                <button onclick="document.getElementById('confirm-modal').classList.add('hidden')" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Hủy</button>
                <button id="modal-confirm" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Xóa</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const parentItems = document.querySelectorAll('.parent-item');
            
            parentItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const parentMenu = this.closest('.menu-parent');
                    
                    // Đóng tất cả các menu khác
                    document.querySelectorAll('.menu-parent').forEach(menu => {
                        if (menu !== parentMenu) {
                            menu.classList.remove('open');
                            menu.querySelector('.child-menu').style.maxHeight = '0';
                            menu.querySelector('.dropdown-icon').classList.remove('transform', 'rotate-180');
                        }
                    });
                    
                    // Toggle menu hiện tại
                    parentMenu.classList.toggle('open');
                    const childMenu = parentMenu.querySelector('.child-menu');
                    const dropdownIcon = parentMenu.querySelector('.dropdown-icon');
                    
                    if (parentMenu.classList.contains('open')) {
                        childMenu.style.maxHeight = childMenu.scrollHeight + 'px';
                        dropdownIcon.classList.add('transform', 'rotate-180');
                    } else {
                        childMenu.style.maxHeight = '0';
                        dropdownIcon.classList.remove('transform', 'rotate-180');
                    }
                });
            });
            
            // Mở menu active khi trang tải
            document.querySelectorAll('.menu-parent.active').forEach(menu => {
                menu.classList.add('open');
                const childMenu = menu.querySelector('.child-menu');
                childMenu.style.maxHeight = childMenu.scrollHeight + 'px';
                menu.querySelector('.dropdown-icon').classList.add('transform', 'rotate-180');
            });

            // Modal handling
            const modal = document.getElementById('confirm-modal');
            const closeModal = document.querySelector('.close-modal');
            const cancelBtn = document.getElementById('modal-cancel');
            
            if (closeModal) {
                closeModal.addEventListener('click', () => {
                    modal.classList.add('hidden');
                });
            }
            
            if (cancelBtn) {
                cancelBtn.addEventListener('click', () => {
                    modal.classList.add('hidden');
                });
            }
            
            // Click outside modal to close
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                }
            });

            // Mobile menu toggle
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const sidebar = document.getElementById('sidebar');
            
            if (mobileMenuButton && sidebar) {
                mobileMenuButton.addEventListener('click', () => {
                    sidebar.classList.toggle('sidebar-mobile-hidden');
                });
            }
            
            setupSearch();
        });

        // Function to show modal (can be called from other scripts)
        function showModal(title, message, confirmCallback) {
            document.getElementById('modal-title').textContent = title;
            document.getElementById('modal-message').textContent = message;
            const confirmBtn = document.getElementById('modal-confirm');
            
            // Remove previous event listeners
            const newConfirmBtn = confirmBtn.cloneNode(true);
            confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);
            
            newConfirmBtn.addEventListener('click', () => {
                confirmCallback();
                document.getElementById('confirm-modal').classList.add('hidden');
            });
            
            document.getElementById('confirm-modal').classList.remove('hidden');
        }

        function setupSearch() {
            const searchInputs = document.querySelectorAll('.search-input');
            
            searchInputs.forEach(input => {
                let rowsCache = null;
                let timeout;
                
                input.addEventListener('focus', () => {
                    if (!rowsCache) {
                        const table = input.closest('.content-section')?.querySelector('table') || 
                                    document.querySelector('table');
                        rowsCache = table ? Array.from(table.querySelectorAll('tbody tr')) : [];
                    }
                });
                
                input.addEventListener('input', function() {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        const searchTerm = this.value.toLowerCase();
                        
                        if (rowsCache) {
                            rowsCache.forEach(row => {
                                const text = row.textContent.toLowerCase();
                                row.style.display = text.includes(searchTerm) ? '' : 'none';
                            });
                        }
                    }, 200);
                });
            });
        }
    </script>

    @stack('scripts')
</body>
</html>




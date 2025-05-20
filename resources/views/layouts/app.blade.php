<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý giáo viên</title>
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
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 text-gray-100 h-screen fixed shadow-lg">
            <div class="p-5 border-b border-gray-700 text-center">
                <h2 class="text-white text-xl font-semibold">Hệ thống quản lý</h2>
            </div>

            <ul class="space-y-1 p-2">
                <!-- Quản lý giáo viên -->
                <li class="menu-parent {{ request()->is('dashboard', 'degrees*', 'faculties*', 'teachers*', 'teacher-statistics*') ? 'active open' : '' }}">
                    <div class="parent-item flex items-center justify-between p-3 hover:bg-gray-700 rounded cursor-pointer transition-colors">
                        <div class="flex items-center">
                            <i class="fas fa-chalkboard-teacher w-5 mr-3 text-center"></i>
                            <span>Quản lý giáo viên</span>
                        </div>
                        <i class="dropdown-icon fas fa-chevron-down text-xs transition-transform"></i>
                    </div>
                    <ul class="child-menu bg-gray-700 overflow-hidden max-h-0 rounded">
                        <li class="{{ request()->routeIs('dashboard') ? 'bg-blue-600' : '' }}">
                            <a href="{{ route('dashboard') }}" class="flex items-center p-3 pl-11 hover:bg-gray-600 rounded transition-colors">
                                <i class="fas fa-tachometer-alt w-5 mr-3 text-center"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('degrees.*') ? 'bg-blue-600' : '' }}">
                            <a href="{{ route('degrees.index') }}" class="flex items-center p-3 pl-11 hover:bg-gray-600 rounded transition-colors">
                                <i class="fas fa-graduation-cap w-5 mr-3 text-center"></i>
                                <span>Quản lý bằng cấp</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('faculties.*') ? 'bg-blue-600' : '' }}">
                            <a href="{{ route('faculties.index') }}" class="flex items-center p-3 pl-11 hover:bg-gray-600 rounded transition-colors">
                                <i class="fas fa-university w-5 mr-3 text-center"></i>
                                <span>Quản lý khoa</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('teachers.*') ? 'bg-blue-600' : '' }}">
                            <a href="{{ route('teachers.index') }}" class="flex items-center p-3 pl-11 hover:bg-gray-600 rounded transition-colors">
                                <i class="fas fa-chalkboard-teacher w-5 mr-3 text-center"></i>
                                <span>Quản lý giáo viên</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('statistics.*') ? 'bg-blue-600' : '' }}">
                            <a href="{{ route('statistics.index') }}" class="flex items-center p-3 pl-11 hover:bg-gray-600 rounded transition-colors">
                                <i class="fas fa-chart-bar w-5 mr-3 text-center"></i>
                                <span>Thống kê giáo viên</span>
                            </a>
                        </li>
                    </ul>
                </li>

                
            </ul>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden ml-64">
            <!-- Header -->
            <header class="bg-white shadow-sm z-10">
                <div class="flex items-center justify-between p-4">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">@yield('title', 'Dashboard')</h2>
                        <div class="text-sm text-gray-500">
                            @yield('breadcrumb', 'Trang chủ')
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
    <div class="fixed inset-0 bg-black bg-opacity-50 hidden" id="confirm-modal">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
                <div class="flex justify-between items-center border-b p-4">
                    <h4 class="text-lg font-semibold" id="modal-title">Xác nhận</h4>
                    <button class="text-gray-500 hover:text-gray-700 close-modal">&times;</button>
                </div>
                <div class="p-4">
                    <p id="modal-message">Bạn có chắc chắn muốn xóa?</p>
                </div>
                <div class="flex justify-end space-x-2 border-t p-4">
                    <button class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded" id="modal-cancel">Hủy</button>
                    <button class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded" id="modal-confirm">Xác nhận</button>
                </div>
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
                        }
                    });
                    
                    // Toggle menu hiện tại
                    parentMenu.classList.toggle('open');
                    const childMenu = parentMenu.querySelector('.child-menu');
                    if (parentMenu.classList.contains('open')) {
                        childMenu.style.maxHeight = childMenu.scrollHeight + 'px';
                    } else {
                        childMenu.style.maxHeight = '0';
                    }
                });
            });
            
            // Mở menu active khi trang tải
            document.querySelectorAll('.menu-parent.active').forEach(menu => {
                menu.classList.add('open');
                const childMenu = menu.querySelector('.child-menu');
                childMenu.style.maxHeight = childMenu.scrollHeight + 'px';
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
                let timeout;
                input.addEventListener('input', function() {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        const searchTerm = this.value.toLowerCase();
                        const table = this.closest('.content-section')?.querySelector('table') || 
                                    document.querySelector('table');
                        
                        if (table) {
                            const rows = table.querySelectorAll('tbody tr');
                            
                            rows.forEach(row => {
                                const text = row.textContent.toLowerCase();
                                row.style.display = text.includes(searchTerm) ? '' : 'none';
                            });
                        }
                    }, 300);
                });
            });
        }
    </script>

    @stack('scripts')
</body>
</html>
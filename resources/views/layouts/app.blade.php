<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Quản lý giáo viên</title>
        <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
        <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <body>
        <div class="container">
            <!-- Sidebar -->
            <div class="sidebar">
                <div class="logo">
                    <h2>Quản lý giáo viên</h2>
                </div>
                <ul class="menu">
                    <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <a href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('degrees.*') ? 'active' : '' }}">
                        <a href="{{ route('degrees.index') }}">
                            <i class="fas fa-graduation-cap"></i>
                            <span>Bằng cấp</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('faculties.*') ? 'active' : '' }}">
                        <a href="{{ route('faculties.index') }}">
                            <i class="fas fa-university"></i>
                            <span>Khoa</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('teachers.*') ? 'active' : '' }}">
                        <a href="{{ route('teachers.index') }}">
                            <i class="fas fa-chalkboard-teacher"></i>
                            <span>Giáo viên</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('statistics.*') ? 'active' : '' }}">
                        <a href="{{ route('statistics.index') }}">
                            <i class="fas fa-chart-bar"></i>
                            <span>Thống kê</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="main-content">
                <!-- Header -->
                <header class="header">
                    <div class="header-title">
                        <h2>@yield('title', 'Dashboard')</h2>
                        <div class="breadcrumb">
                            @yield('breadcrumb', 'Trang chủ')
                        </div>
                    </div>
                    <div class="user-info">
                        <div class="user-avatar">
                            <img src="{{ asset('image/user.jpg') }}" alt="User" class="avatar-img">
                        </div>
                        <span class="user-name">Admin</span>
                        <form method="POST" action="{{ route('logout') }}" class="logout-form">
                            @csrf
                            <button type="submit" class="btn btn-logout">
                                <i class="fas fa-sign-out-alt"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </header>

                <!-- Content Area -->
                <div class="content">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </div>

        <!-- Modal for confirmation -->
        <div class="modal" id="confirm-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="modal-title">Xác nhận</h4>
                    <span class="close-modal">&times;</span>
                </div>
                <div class="modal-body">
                    <p id="modal-message">Bạn có chắc chắn muốn xóa?</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" id="modal-cancel">Hủy</button>
                    <button class="btn btn-danger" id="modal-confirm">Xác nhận</button>
                </div>
            </div>
        </div>

        <script src="{{ asset('js/script.js') }}"></script>
        @stack('scripts')
    </body>
</html>

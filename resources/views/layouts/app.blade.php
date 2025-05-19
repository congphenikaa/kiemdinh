<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Quản lý giáo viên</title>
        <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
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

        <style>
            /* User Info Styles */
            .user-info {
                display: flex;
                align-items: center;
                gap: 1rem;
                padding: 0.5rem 1rem;
                background: #fff;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            }

            .user-avatar {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                overflow: hidden;
                border: 2px solid #e2e8f0;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                transition: all 0.3s ease;
            }

            .user-avatar:hover {
                transform: scale(1.05);
                border-color: #4299e1;
            }

            .avatar-img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .user-name {
                font-weight: 500;
                color: #2d3748;
                font-size: 0.95rem;
            }

            .logout-form {
                margin: 0;
            }

            .btn-logout {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.5rem 1rem;
                background: #f53003;
                color: #fff;
                border: none;
                border-radius: 6px;
                font-weight: 500;
                font-size: 0.9rem;
                cursor: pointer;
                transition: all 0.2s ease;
            }

            .btn-logout:hover {
                background: #d42a02;
                transform: translateY(-1px);
            }

            .btn-logout i {
                font-size: 0.9rem;
            }

            @media (max-width: 768px) {
                .user-info {
                    padding: 0.5rem;
                }
                
                .user-name {
                    display: none;
                }
                
                .btn-logout span {
                    display: none;
                }
                
                .btn-logout {
                    padding: 0.5rem;
                }
                
                .btn-logout i {
                    margin: 0;
                }
            }
        </style>
    </body>
</html>

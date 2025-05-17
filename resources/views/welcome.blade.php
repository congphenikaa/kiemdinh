<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hệ thống tính tiền giảng dạy</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-tr from-blue-100 via-white to-blue-200 font-sans text-gray-700">

    <header class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 py-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-blue-600">Hệ thống tính tiền giảng dạy</h1>

            @auth
                <div class="flex gap-4 items-center">
                    <a href="{{ route('dashboard') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                        Dashboard
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="logout-form">
                            @csrf
                            <button type="submit" class="btn btn-logout">
                                <i class="fas fa-sign-out-alt"></i>
                                Logout
                            </button>
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    Đăng nhập
                </a>
            @endauth
        </div>
    </header>

    <main class="flex flex-col items-center justify-center min-h-[calc(100vh-100px)] text-center px-4">
        <h2 class="text-4xl font-bold mb-4 text-blue-700">Chào mừng đến với hệ thống quản lý tiền giảng dạy</h2>
        <p class="text-lg mb-8 max-w-xl text-gray-600">
            Ứng dụng giúp giảng viên dễ dàng theo dõi số tiết dạy, tính toán thu nhập, và xuất báo cáo chuyên nghiệp.
        </p>

        @guest
            <div class="flex gap-4">
                <a href="{{ route('register') }}" class="px-6 py-3 bg-green-500 text-white rounded-lg shadow hover:bg-green-600 transition">Đăng ký tài khoản</a>
                <a href="{{ route('login') }}" class="px-6 py-3 bg-blue-500 text-white rounded-lg shadow hover:bg-blue-600 transition">Đăng nhập</a>
            </div>
        @else
            <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition">
                Vào Dashboard
            </a>
        @endguest
    </main>

    <footer class="mt-16 py-6 text-sm text-gray-500 text-center">
        © {{ now()->year }} Hệ thống tính tiền giảng dạy. Phát triển bởi Nhóm 10.
    </footer>

</body>
</html>

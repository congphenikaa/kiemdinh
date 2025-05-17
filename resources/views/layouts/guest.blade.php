<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Hệ thống tính tiền giảng dạy') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gradient-to-br from-blue-100 via-white to-blue-200 text-gray-800">

    <div class="min-h-screen flex flex-col items-center justify-center py-10 px-4">
        
        <!-- Hộp nội dung -->
        <div class="w-full max-w-md bg-white shadow-xl rounded-2xl p-8 border border-gray-200">
            <h2 class="text-2xl font-semibold text-center text-blue-700 mb-6">
                Hệ thống tính tiền giảng dạy
            </h2>

            {{ $slot }}
        </div>

        <!-- Footer -->
        <footer class="mt-8 text-sm text-gray-500 text-center">
            © {{ now()->year }} Hệ thống tính tiền giảng dạy. Phát triển bởi Nhóm 10.
        </footer>
    </div>

</body>
</html>

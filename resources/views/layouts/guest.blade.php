<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'QL Giảng dạy') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=be-vietnam-pro:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-800 antialiased">
    <div class="flex min-h-screen flex-col items-center justify-center px-4 py-12">
        <div class="mb-8 text-center">
            <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-primary-600 text-lg font-bold text-white">
                QL
            </div>
            <h1 class="text-xl font-semibold text-slate-900">Hệ thống QL Giảng dạy</h1>
            <p class="mt-1 text-sm text-slate-500">Đăng nhập để tiếp tục</p>
        </div>

        <div class="w-full max-w-md app-card p-8">
            {{ $slot }}
        </div>

        <footer class="mt-8 text-center text-sm text-slate-400">
            © {{ now()->year }} Hệ thống quản lý giảng dạy
        </footer>
    </div>
</body>
</html>

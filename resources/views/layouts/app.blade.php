<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tổng quan') — QL Giảng dạy</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=be-vietnam-pro:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased" x-data="appShell">
    <div
        x-show="sidebarOpen"
        x-transition:enter="transition-opacity ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="closeSidebar()"
        class="fixed inset-0 z-40 bg-slate-900/60 lg:hidden"
        x-cloak
    ></div>

    <div class="flex h-screen overflow-hidden">
        @include('layouts.partials.sidebar')

        <div class="flex min-w-0 flex-1 flex-col overflow-hidden">
            <header class="z-10 shrink-0 border-b border-slate-200 bg-white">
                <div class="flex h-16 items-center justify-between gap-4 px-4 sm:px-6">
                    <div class="flex min-w-0 items-center gap-3">
                        <button
                            type="button"
                            @click="toggleSidebar()"
                            class="btn-icon lg:hidden"
                            aria-label="Mở menu"
                        >
                            <i class="fas fa-bars text-lg"></i>
                        </button>
                        <div class="min-w-0">
                            <h1 class="truncate text-lg font-semibold text-slate-900">@yield('title', 'Tổng quan')</h1>
                            @hasSection('breadcrumb')
                                <p class="truncate text-sm text-slate-500">@yield('breadcrumb')</p>
                            @endif
                        </div>
                    </div>

                    <div class="flex shrink-0 items-center gap-2">
                        <a href="{{ route('profile.edit') }}" class="hidden items-center gap-2 rounded-lg px-3 py-2 text-sm text-slate-600 transition hover:bg-slate-100 sm:flex">
                            <i class="fas fa-user-circle text-slate-400"></i>
                            <span>Hồ sơ</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn-secondary !py-2 !text-sm">
                                <i class="fas fa-right-from-bracket"></i>
                                <span class="hidden sm:inline">Đăng xuất</span>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 sm:p-6">
                @if(session('success'))
                    <x-flash-alert type="success" :message="session('success')" />
                @endif

                @if(session('error'))
                    <x-flash-alert type="error" :message="session('error')" />
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <div id="confirm-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/50 p-4">
        <div class="app-card w-full max-w-md p-6" role="dialog" aria-modal="true">
            <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-full bg-red-100 text-red-600">
                <i class="fas fa-triangle-exclamation"></i>
            </div>
            <h2 id="modal-title" class="text-lg font-semibold text-slate-900">Xác nhận</h2>
            <p id="modal-message" class="mt-2 text-sm text-slate-600">Bạn có chắc chắn muốn thực hiện?</p>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('confirm-modal').classList.add('hidden')" class="btn-secondary">
                    Hủy
                </button>
                <button type="button" id="modal-confirm" class="inline-flex items-center rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                    Xác nhận
                </button>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('confirm-modal')?.addEventListener('click', (e) => {
            if (e.target.id === 'confirm-modal') {
                e.currentTarget.classList.add('hidden');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>

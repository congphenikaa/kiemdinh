<x-guest-layout>
    <!-- Thông báo trạng thái phiên làm việc -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Địa chỉ Email -->
        <div>
            <x-input-label for="email" :value="__('Địa chỉ Email')" />
            <x-text-input 
                id="email" 
                class="block mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                type="email" 
                name="email" 
                :value="old('email')" 
                required 
                autofocus 
                autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
        </div>

        <!-- Mật khẩu -->
        <div>
            <x-input-label for="password" :value="__('Mật khẩu')" />
            <x-text-input 
                id="password" 
                class="block mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                type="password" 
                name="password" 
                required 
                autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
        </div>

        <!-- Ghi nhớ đăng nhập -->
        <div class="flex items-center">
            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
            <label for="remember_me" class="ms-2 text-sm text-gray-700">
                {{ __('Ghi nhớ đăng nhập') }}
            </label>
        </div>

        <!-- Nút và liên kết -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-6">
            @if (Route::has('password.request'))
                <a class="text-sm text-blue-600 hover:underline focus:outline-none focus:ring-2 focus:ring-blue-400" href="{{ route('password.request') }}">
                    {{ __('Quên mật khẩu?') }}
                </a>
            @endif

            <x-primary-button class="w-full sm:w-auto">
                {{ __('Đăng nhập') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

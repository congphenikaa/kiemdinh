<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <!-- Họ và tên -->
        <div>
            <x-input-label for="name" :value="__('Họ và tên')" />
            <x-text-input id="name" 
                          class="block mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                          type="text" 
                          name="name" 
                          :value="old('name')" 
                          required 
                          autofocus 
                          autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-red-600" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" 
                          class="block mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                          type="email" 
                          name="email" 
                          :value="old('email')" 
                          required 
                          autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
        </div>

        <!-- Số điện thoại -->
        <div>
            <x-input-label for="phone" :value="__('Số điện thoại')" />
            <x-text-input id="phone" 
                          class="block mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                          type="text" 
                          name="phone" 
                          :value="old('phone')" 
                          required 
                          autocomplete="tel" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2 text-sm text-red-600" />
        </div>

        <!-- Địa chỉ -->
        <div>
            <x-input-label for="address" :value="__('Địa chỉ')" />
            <x-text-input id="address" 
                          class="block mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                          type="text" 
                          name="address" 
                          :value="old('address')" 
                          required 
                          autocomplete="address" />
            <x-input-error :messages="$errors->get('address')" class="mt-2 text-sm text-red-600" />
        </div>

        <!-- Mật khẩu -->
        <div>
            <x-input-label for="password" :value="__('Mật khẩu')" />
            <x-text-input id="password" 
                          class="block mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                          type="password" 
                          name="password" 
                          required 
                          autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
        </div>

        <!-- Xác nhận mật khẩu -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Xác nhận mật khẩu')" />
            <x-text-input id="password_confirmation" 
                          class="block mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                          type="password" 
                          name="password_confirmation" 
                          required 
                          autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-600" />
        </div>

        <!-- Liên kết chuyển trang và nút đăng ký -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-6">
            <a class="text-sm text-blue-600 hover:underline focus:outline-none focus:ring-2 focus:ring-blue-400" href="{{ route('login') }}">
                {{ __('Đã có tài khoản? Đăng nhập') }}
            </a>

            <x-primary-button class="w-full sm:w-auto">
                {{ __('Đăng ký') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

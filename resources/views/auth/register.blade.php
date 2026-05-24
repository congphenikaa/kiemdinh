<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Họ và tên')" />
            <x-text-input id="name" class="mt-1" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="form-error" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-1" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="form-error" />
        </div>

        <div>
            <x-input-label for="phone" :value="__('Số điện thoại')" />
            <x-text-input id="phone" class="mt-1" type="text" name="phone" :value="old('phone')" required autocomplete="tel" />
            <x-input-error :messages="$errors->get('phone')" class="form-error" />
        </div>

        <div>
            <x-input-label for="address" :value="__('Địa chỉ')" />
            <x-text-input id="address" class="mt-1" type="text" name="address" :value="old('address')" required autocomplete="street-address" />
            <x-input-error :messages="$errors->get('address')" class="form-error" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Mật khẩu')" />
            <x-text-input id="password" class="mt-1" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="form-error" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Xác nhận mật khẩu')" />
            <x-text-input id="password_confirmation" class="mt-1" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="form-error" />
        </div>

        <div class="flex flex-col gap-4 pt-2 sm:flex-row sm:items-center sm:justify-between">
            <a class="text-sm text-primary-600 hover:text-primary-700" href="{{ route('login') }}">
                {{ __('Đã có tài khoản? Đăng nhập') }}
            </a>
            <x-primary-button class="w-full sm:w-auto">{{ __('Đăng ký') }}</x-primary-button>
        </div>
    </form>
</x-guest-layout>

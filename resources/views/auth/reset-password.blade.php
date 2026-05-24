<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-1" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="form-error" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Mật khẩu mới')" />
            <x-text-input id="password" class="mt-1" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="form-error" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Xác nhận mật khẩu')" />
            <x-text-input id="password_confirmation" class="mt-1" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="form-error" />
        </div>

        <x-primary-button class="w-full">{{ __('Đặt lại mật khẩu') }}</x-primary-button>
    </form>
</x-guest-layout>

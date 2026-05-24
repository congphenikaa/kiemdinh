<section>
    <header class="mb-6">
        <h2 class="text-base font-semibold text-slate-900">Đổi mật khẩu</h2>
        <p class="mt-1 text-sm text-slate-500">Dùng mật khẩu mạnh, khó đoán để bảo vệ tài khoản.</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Mật khẩu hiện tại')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="form-error" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('Mật khẩu mới')" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="form-error" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Xác nhận mật khẩu')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="form-error" />
        </div>

        <div class="flex items-center gap-4 border-t border-slate-100 pt-5">
            <x-primary-button>{{ __('Cập nhật mật khẩu') }}</x-primary-button>
            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-emerald-600">
                    {{ __('Đã lưu.') }}
                </p>
            @endif
        </div>
    </form>
</section>

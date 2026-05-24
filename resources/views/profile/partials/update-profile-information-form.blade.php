<section>
    <header class="mb-6">
        <h2 class="text-base font-semibold text-slate-900">Thông tin tài khoản</h2>
        <p class="mt-1 text-sm text-slate-500">Cập nhật họ tên và email đăng nhập.</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Họ tên')" />
            <x-text-input id="name" name="name" type="text" class="mt-1" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="form-error" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="form-error" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 rounded-lg border border-amber-200 bg-amber-50 p-3 text-sm text-amber-900">
                    <p>{{ __('Email chưa được xác minh.') }}</p>
                    <button form="send-verification" type="submit" class="mt-2 font-medium text-primary-600 hover:text-primary-700">
                        {{ __('Gửi lại email xác minh') }}
                    </button>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-emerald-700">{{ __('Đã gửi link xác minh mới.') }}</p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 border-t border-slate-100 pt-5">
            <x-primary-button>{{ __('Lưu thay đổi') }}</x-primary-button>
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-emerald-600">
                    {{ __('Đã lưu.') }}
                </p>
            @endif
        </div>
    </form>
</section>

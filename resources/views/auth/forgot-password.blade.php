<x-guest-layout>
    <div class="mb-6 text-base text-gray-700">
        Quên mật khẩu? Đừng lo. Hãy nhập địa chỉ email của bạn, chúng tôi sẽ gửi liên kết để bạn đặt lại mật khẩu mới.
    </div>

    <!-- Thông báo trạng thái -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <!-- Địa chỉ Email -->
        <div>
            <x-input-label for="email" :value="'Địa chỉ Email'" />
            <x-text-input id="email" class="block mt-1 w-full"
                          type="email"
                          name="email"
                          :value="old('email')"
                          required autofocus
                          placeholder="nhapdiachi@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex justify-end">
            <x-primary-button>
                Gửi liên kết đặt lại mật khẩu
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

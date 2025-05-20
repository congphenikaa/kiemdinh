<x-guest-layout>
    <div class="mb-6 text-base text-gray-700">
        Đây là khu vực bảo mật của ứng dụng. Vui lòng xác nhận mật khẩu trước khi tiếp tục.
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
        @csrf

        <!-- Mật khẩu -->
        <div>
            <x-input-label for="password" :value="'Mật khẩu hiện tại'" />

            <x-text-input id="password" class="block mt-1 w-full"
                          type="password"
                          name="password"
                          required
                          autocomplete="current-password"
                          placeholder="Nhập mật khẩu của bạn" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-between items-center">
            <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:underline">
                Quên mật khẩu?
            </a>

            <x-primary-button>
                Xác nhận
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

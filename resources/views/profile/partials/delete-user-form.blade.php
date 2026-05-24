<section>
    <header class="mb-6">
        <h2 class="text-base font-semibold text-slate-900">Xóa tài khoản</h2>
        <p class="mt-1 text-sm text-slate-500">Hành động này không thể hoàn tác. Mọi dữ liệu liên quan sẽ bị xóa vĩnh viễn.</p>
    </header>

    <x-danger-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
        {{ __('Xóa tài khoản') }}
    </x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-semibold text-slate-900">Xác nhận xóa tài khoản?</h2>
            <p class="mt-2 text-sm text-slate-600">Nhập mật khẩu để xác nhận.</p>

            <div class="mt-5">
                <x-input-label for="password" value="{{ __('Mật khẩu') }}" class="sr-only" />
                <x-text-input id="password" name="password" type="password" class="mt-1" placeholder="{{ __('Mật khẩu') }}" />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="form-error" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">{{ __('Hủy') }}</x-secondary-button>
                <x-danger-button>{{ __('Xóa vĩnh viễn') }}</x-danger-button>
            </div>
        </form>
    </x-modal>
</section>

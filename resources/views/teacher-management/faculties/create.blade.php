@extends('templates.create', [
    'entityName' => 'Khoa',
    'routePrefix' => 'faculties'
])

@section('form_fields')
    <div class="space-y-6">
        <x-form-field label="Tên khoa" for="name" hint="Ví dụ: Công nghệ thông tin" :required="true">
            <input type="text" id="name" name="name" value="{{ old('name') }}" required class="form-input" placeholder="Tên khoa">
            <x-form-error field="name" />
        </x-form-field>

        <x-form-field label="Tên viết tắt" for="short_name" hint="VD: CNTT" :required="true">
            <input type="text" id="short_name" name="short_name" value="{{ old('short_name') }}" required maxlength="10"
                   class="form-input uppercase" placeholder="CNTT">
            <x-form-error field="short_name" />
        </x-form-field>

        <x-form-field label="Mô tả" for="description" hint="Tối đa 255 ký tự">
            <textarea id="description" name="description" rows="3" class="form-textarea" placeholder="Mô tả khoa">{{ old('description') }}</textarea>
            <x-form-error field="description" />
        </x-form-field>
    </div>
@endsection

@push('scripts')
<script>
    document.getElementById('short_name')?.addEventListener('input', function () {
        this.value = this.value.toUpperCase();
    });
</script>
@endpush

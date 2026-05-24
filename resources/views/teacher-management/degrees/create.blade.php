@extends('templates.create', [
    'entityName' => 'Bằng cấp',
    'routePrefix' => 'degrees'
])

@section('form_fields')
    <div class="space-y-6">
        <x-form-field label="Tên đầy đủ" for="name" hint="Ví dụ: Tiến sĩ, Thạc sĩ, Cử nhân" :required="true">
            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                   class="form-input" placeholder="Nhập tên đầy đủ bằng cấp">
            <x-form-error field="name" />
        </x-form-field>

        <x-form-field label="Tên viết tắt" for="short_name" hint="Tối đa 10 ký tự" :required="true">
            <input type="text" id="short_name" name="short_name" value="{{ old('short_name') }}" required maxlength="10"
                   class="form-input uppercase" placeholder="VD: TS, ThS, CN">
            <x-form-error field="short_name" />
        </x-form-field>

        <x-form-field label="Hệ số lương" for="salary_coefficient" hint="Từ 1.00 đến 10.00" :required="true">
            <div class="relative">
                <input type="number" step="0.01" min="1" max="10" id="salary_coefficient"
                       name="salary_coefficient" value="{{ old('salary_coefficient', 1.00) }}" required
                       class="form-input pr-10" placeholder="1.00">
                <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400">×</span>
            </div>
            <x-form-error field="salary_coefficient" />
        </x-form-field>
    </div>
@endsection

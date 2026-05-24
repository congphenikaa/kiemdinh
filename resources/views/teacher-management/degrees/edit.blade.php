@extends('templates.edit', [
    'entityName' => 'Bằng cấp',
    'routePrefix' => 'degrees',
    'model' => $degree
])

@section('form_fields')
    <div class="space-y-6">
        <x-form-field label="Tên đầy đủ" for="name" :required="true">
            <input type="text" id="name" name="name" value="{{ old('name', $degree->name) }}" required class="form-input">
            <x-form-error field="name" />
        </x-form-field>

        <x-form-field label="Tên viết tắt" for="short_name" hint="Tối đa 10 ký tự" :required="true">
            <input type="text" id="short_name" name="short_name" value="{{ old('short_name', $degree->short_name) }}" required maxlength="10"
                   class="form-input uppercase">
            <x-form-error field="short_name" />
        </x-form-field>

        <x-form-field label="Hệ số lương" for="salary_coefficient" :required="true">
            <input type="number" step="0.01" min="1" max="10" id="salary_coefficient"
                   name="salary_coefficient" value="{{ old('salary_coefficient', $degree->salary_coefficient) }}" required
                   class="form-input">
            <x-form-error field="salary_coefficient" />
        </x-form-field>
    </div>
@endsection

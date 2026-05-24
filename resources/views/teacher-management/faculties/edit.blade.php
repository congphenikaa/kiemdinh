@extends('templates.edit', [
    'entityName' => 'Khoa',
    'routePrefix' => 'faculties',
    'model' => $faculty
])

@section('form_fields')
    <div class="space-y-6">
        <x-form-field label="Tên khoa" for="name" :required="true">
            <input type="text" id="name" name="name" value="{{ old('name', $faculty->name) }}" required class="form-input">
            <x-form-error field="name" />
        </x-form-field>

        <x-form-field label="Tên viết tắt" for="short_name" :required="true">
            <input type="text" id="short_name" name="short_name" value="{{ old('short_name', $faculty->short_name) }}" required maxlength="10" class="form-input uppercase">
            <x-form-error field="short_name" />
        </x-form-field>

        <x-form-field label="Mô tả" for="description">
            <textarea id="description" name="description" rows="3" class="form-textarea">{{ old('description', $faculty->description) }}</textarea>
            <x-form-error field="description" />
            <p class="form-hint mt-2">{{ $faculty->teachers_count ?? 0 }} giảng viên · {{ $faculty->courses_count ?? 0 }} học phần</p>
        </x-form-field>
    </div>
@endsection

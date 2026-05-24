@extends('layouts.app')

@section('title', 'Hồ sơ cá nhân')
@section('breadcrumb', 'Tài khoản')

@section('content')
<div class="mx-auto max-w-2xl space-y-6">
    <div class="app-card p-6 sm:p-8">
        @include('profile.partials.update-profile-information-form')
    </div>

    <div class="app-card p-6 sm:p-8">
        @include('profile.partials.update-password-form')
    </div>

    <div class="app-card p-6 sm:p-8">
        @include('profile.partials.delete-user-form')
    </div>
</div>
@endsection

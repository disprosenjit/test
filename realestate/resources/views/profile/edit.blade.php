@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <!-- Page Header -->
    <div class="bg-slate-50 border-b border-slate-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-2xl font-bold text-slate-900">Profile Settings</h1>
            <p class="mt-1 text-sm text-slate-600">Manage your account settings and preferences.</p>
        </div>
    </div>

    <!-- Profile Sections -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Update Profile Information -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 mb-8">
            @include('profile.partials.update-profile-information-form')
        </div>

        <!-- Update Password -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 mb-8">
            @include('profile.partials.update-password-form')
        </div>

        <!-- Delete Account -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 mb-8">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
@endsection

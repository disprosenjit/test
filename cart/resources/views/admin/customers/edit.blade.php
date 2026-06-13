@extends('layouts.admin')

@section('title', 'Edit Customer')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.customers.index') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-flex items-center">
        <i class="fas fa-arrow-left mr-2"></i> Back to Customers
    </a>
    <h1 class="text-2xl font-bold">Edit Customer Profile</h1>
    <p class="text-gray-600 mt-1">{{ $customer->name }}</p>
</div>

<div class="bg-white rounded-lg shadow p-6 max-w-2xl">
    <form method="POST" action="{{ route('admin.customers.update', $customer) }}">
        @csrf
        @method('PUT')

        <div class="space-y-4 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Profile Information</h3>

            <div>
                <label class="block text-sm font-semibold mb-2">Customer Name *</label>
                <input type="text" name="name" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror" value="{{ old('name', $customer->name) }}">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Email Address *</label>
                <input type="email" name="email" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror" value="{{ old('email', $customer->email) }}">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Phone Number</label>
                <input type="text" name="phone" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('phone') border-red-500 @enderror" value="{{ old('phone', $customer->phone) }}">
                @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="flex items-center mt-4">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $customer->is_active) ? 'checked' : '' }} class="w-4 h-4 rounded text-blue-600 border-gray-300 focus:ring-blue-500">
                    <span class="ml-3 text-sm font-semibold text-gray-700">Active Account (Allowed to log in and order)</span>
                </label>
            </div>
        </div>

        <div class="flex gap-3 border-t pt-6">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition-colors duration-200">
                Update Profile
            </button>
            <a href="{{ route('admin.customers.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-2 rounded-lg font-semibold transition-colors duration-200">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Create Page')

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('admin.pages.index') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back to Pages
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Create Page</h1>
    </div>

    <!-- Create Form -->
    <div class="bg-white rounded-lg shadow-md p-8 max-w-4xl">
        <form method="POST" action="{{ route('admin.pages.store') }}">
            @csrf

            <!-- Title -->
            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" 
                    placeholder="e.g., Privacy Policy"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror">
                @error('title')
                    <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-sm mt-1">Slug will be auto-generated from title</p>
            </div>

            <!-- Meta Description -->
            <div class="mb-6">
                <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                <textarea id="meta_description" name="meta_description" rows="2" 
                    placeholder="Brief description for SEO (optional)"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('meta_description') }}</textarea>
                @error('meta_description')
                    <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                @enderror
            </div>

            <!-- Content -->
            <div class="mb-6">
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Content *</label>
                <textarea id="content" name="content" rows="12" 
                    placeholder="Page content (supports HTML)"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm @error('content') border-red-500 @enderror">{{ old('content') }}</textarea>
                @error('content')
                    <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                @enderror
            </div>

            <!-- Active Status -->
            <div class="mb-8">
                <label class="flex items-center">
                    <input type="checkbox" id="is_active" name="is_active" value="1" 
                        {{ old('is_active', true) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500">
                    <span class="ml-3 text-sm font-medium text-gray-700">Active (visible on website)</span>
                </label>
            </div>

            <!-- Submit Buttons -->
            <div class="flex gap-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">
                    <i class="fas fa-save mr-2"></i> Create Page
                </button>
                <a href="{{ route('admin.pages.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg font-medium">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

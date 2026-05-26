@extends('layouts.admin')

@section('title', $page->title)

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="flex justify-between items-start mb-8">
        <div>
            <a href="{{ route('admin.pages.index') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Back to Pages
            </a>
            <h1 class="text-3xl font-bold text-gray-900">{{ $page->title }}</h1>
            <p class="text-gray-600 mt-2">
                <code class="bg-gray-100 px-2 py-1 rounded">{{ $page->slug }}</code>
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.pages.edit', $page) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            <a href="/pages/{{ $page->slug }}" target="_blank" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-external-link-alt mr-2"></i> View
            </a>
        </div>
    </div>

    <!-- Page Details -->
    <div class="grid grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-gray-600 text-sm mb-1">Status</p>
            @if($page->is_active)
                <p class="text-green-600 font-semibold text-lg">
                    <i class="fas fa-check-circle mr-2"></i> Active
                </p>
            @else
                <p class="text-gray-600 font-semibold text-lg">
                    <i class="fas fa-times-circle mr-2"></i> Inactive
                </p>
            @endif
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-gray-600 text-sm mb-1">Created</p>
            <p class="font-semibold text-lg">{{ $page->created_at->format('M d, Y') }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-gray-600 text-sm mb-1">Last Updated</p>
            <p class="font-semibold text-lg">{{ $page->updated_at->format('M d, Y H:i') }}</p>
        </div>
    </div>

    <!-- Meta Description -->
    @if($page->meta_description)
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Meta Description</h3>
            <p class="text-gray-700">{{ $page->meta_description }}</p>
        </div>
    @endif

    <!-- Content -->
    <div class="bg-white rounded-lg shadow-md p-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Content</h3>
        <div class="prose prose-sm max-w-none text-gray-700 bg-gray-50 p-6 rounded-lg border border-gray-200">
            {!! nl2br(e($page->content)) !!}
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', $page->title)
@section('meta_description', $page->meta_description ?? $page->title)

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Header -->
        <div class="mb-12">
            <div class="flex items-center gap-2 text-gray-600 mb-6">
                <a href="/" class="text-blue-600 hover:text-blue-800">Home</a>
                <i class="fas fa-chevron-right"></i>
                <span>{{ $page->title }}</span>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ $page->title }}</h1>
            <p class="text-gray-600">
                <i class="fas fa-calendar mr-2"></i>
                Last updated: {{ $page->updated_at->format('F d, Y') }}
            </p>
        </div>

        <!-- Content -->
        <div class="bg-white rounded-lg shadow-md p-8 prose prose-sm max-w-none">
            {!! $page->content !!}
        </div>

        <!-- Related Pages -->
        @php
            $relatedPages = \App\Models\Content\Page::where('is_active', true)
                ->where('id', '!=', $page->id)
                ->limit(3)
                ->get();
        @endphp

        @if($relatedPages->count())
            <div class="mt-12">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Other Pages</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($relatedPages as $relatedPage)
                        <a href="/pages/{{ $relatedPage->slug }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ $relatedPage->title }}</h4>
                            <p class="text-gray-600 text-sm mb-4">{{ Str::limit(strip_tags($relatedPage->content), 100) }}</p>
                            <span class="text-blue-600 font-medium">Read more <i class="fas fa-arrow-right ml-2"></i></span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Back to home -->
        <div class="mt-12 text-center">
            <a href="/" class="text-blue-600 hover:text-blue-800 font-medium">
                <i class="fas fa-arrow-left mr-2"></i> Back to Home
            </a>
        </div>
    </div>
</div>
@endsection

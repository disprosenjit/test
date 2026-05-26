@extends('layouts.app')

@section('title', $faq->question . ' - Ship Spare Parts Store')

@section('content')
<!-- Breadcrumb -->
<div class="bg-gray-50 border-b">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex items-center gap-2 text-sm text-gray-600">
            <a href="/faqs" class="hover:text-blue-600">FAQs</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <a href="/faqs?category={{ urlencode($faq->category) }}" class="hover:text-blue-600">{{ $faq->category }}</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-gray-900 font-semibold">{{ Str::limit($faq->question, 50) }}</span>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main FAQ Content -->
        <div class="lg:col-span-2">
            <!-- Question -->
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ $faq->question }}</h1>

            <!-- Meta Information -->
            <div class="flex flex-wrap gap-4 mb-8 text-sm text-gray-600">
                <div class="flex items-center gap-2">
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded">{{ $faq->category }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-eye"></i>
                    <span>{{ $faq->view_count }} views</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-calendar"></i>
                    <span>{{ $faq->created_at->format('M d, Y') }}</span>
                </div>
                @if($faq->updated_at !== $faq->created_at)
                    <div class="flex items-center gap-2">
                        <i class="fas fa-sync"></i>
                        <span>Updated {{ $faq->updated_at->format('M d, Y') }}</span>
                    </div>
                @endif
            </div>

            <!-- Answer -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 mb-8">
                <div class="prose prose-base max-w-none text-gray-700">
                    {{ nl2br($faq->answer) }}
                </div>
            </div>

            <!-- Keywords -->
            @if($faq->keywords)
                <div class="bg-gray-50 rounded-lg p-6 mb-8 border border-gray-200">
                    <p class="text-sm font-semibold text-gray-700 mb-3">Related Topics:</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach(array_map('trim', explode(',', $faq->keywords)) as $keyword)
                            <a 
                                href="/faqs?search={{ urlencode($keyword) }}" 
                                class="text-sm bg-white border border-gray-300 text-gray-700 px-3 py-2 rounded hover:border-blue-300 hover:bg-blue-50 transition"
                            >
                                {{ $keyword }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Helpful Rating -->
            <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
                <p class="text-sm font-semibold text-gray-900 mb-4">Was this helpful?</p>
                <div class="flex gap-4">
                    <button class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded hover:bg-green-50 hover:border-green-300 transition text-sm">
                        <i class="fas fa-thumbs-up text-gray-600"></i>
                        Yes
                    </button>
                    <button class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded hover:bg-red-50 hover:border-red-300 transition text-sm">
                        <i class="fas fa-thumbs-down text-gray-600"></i>
                        No
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Related FAQs -->
            @if($relatedFaqs->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-4">
                    <h3 class="font-bold text-gray-900 mb-4">Related Questions</h3>
                    <div class="space-y-3">
                        @foreach($relatedFaqs as $related)
                            <a 
                                href="/faqs/{{ $related->id }}" 
                                class="block p-3 bg-gray-50 hover:bg-blue-50 rounded transition border border-gray-200 hover:border-blue-300"
                            >
                                <p class="text-sm font-semibold text-gray-900 hover:text-blue-600 line-clamp-2">
                                    {{ $related->question }}
                                </p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Quick Links -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mt-6">
                <h3 class="font-bold text-gray-900 mb-4">Quick Links</h3>
                <div class="space-y-3">
                    <a href="/faqs" class="flex items-center gap-2 text-blue-600 hover:text-blue-800 font-semibold text-sm">
                        <i class="fas fa-arrow-left"></i>
                        Back to FAQs
                    </a>
                    <a href="/faqs?category={{ urlencode($faq->category) }}" class="flex items-center gap-2 text-blue-600 hover:text-blue-800 font-semibold text-sm">
                        <i class="fas fa-filter"></i>
                        {{ $faq->category }} FAQs
                    </a>
                    <a href="/products" class="flex items-center gap-2 text-blue-600 hover:text-blue-800 font-semibold text-sm">
                        <i class="fas fa-shopping-bag"></i>
                        Browse Products
                    </a>
                </div>
            </div>

            <!-- Contact Support -->
            <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-lg p-6 text-white mt-6">
                <h3 class="font-bold mb-2">Need More Help?</h3>
                <p class="text-sm text-blue-100 mb-4">Contact our support team</p>
                <div class="space-y-2">
                    <a href="mailto:support@shipparts.com" class="flex items-center gap-2 text-sm hover:underline">
                        <i class="fas fa-envelope"></i>
                        support@shipparts.com
                    </a>
                    <a href="tel:+911234567890" class="flex items-center gap-2 text-sm hover:underline">
                        <i class="fas fa-phone"></i>
                        +91-1234-567-890
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Navigation Between FAQs -->
<div class="bg-gray-50 border-t border-b">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex items-center justify-between">
            <a href="/faqs" class="text-blue-600 hover:text-blue-800 font-semibold flex items-center gap-2">
                <i class="fas fa-arrow-left"></i>
                Back to FAQs
            </a>
            <a href="/faqs?category={{ urlencode($faq->category) }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                View all {{ $faq->category }} FAQs
            </a>
        </div>
    </div>
</div>
@endsection

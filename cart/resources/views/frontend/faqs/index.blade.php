@extends('layouts.app')

@section('title', 'FAQs - Ship Spare Parts Store')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-12 md:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Frequently Asked Questions</h1>
        <p class="text-xl md:text-2xl text-blue-100">Find answers to common questions about our products and services</p>
    </div>
</div>

<!-- Search and Filter Section -->
<div class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <form action="/faqs" method="GET" class="space-y-4">
            <!-- Search Input -->
            <div class="relative">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Search FAQs..."
                    value="{{ request('search') }}"
                    class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
                <button type="submit" class="absolute right-3 top-3 text-gray-400 hover:text-blue-600">
                    <i class="fas fa-search"></i>
                </button>
            </div>

            <!-- Category Filter -->
            <div class="flex flex-wrap gap-2">
                <a href="/faqs" class="px-4 py-2 rounded-full {{ !request('category') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    All Categories
                </a>
                @foreach($categories as $category)
                    <a 
                        href="/faqs?category={{ urlencode($category) }}" 
                        class="px-4 py-2 rounded-full {{ request('category') == $category ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}"
                    >
                        {{ $category }}
                    </a>
                @endforeach
            </div>
        </form>
    </div>
</div>

<!-- FAQs Content -->
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    @if($faqs->count() > 0)
        <!-- Results Info -->
        <div class="mb-8">
            <p class="text-gray-600">
                @if(request('search') || request('category'))
                    Showing <strong>{{ $faqs->count() }}</strong> result(s)
                @else
                    Showing all FAQs
                @endif
            </p>
        </div>

        <!-- FAQs Accordion -->
        <div class="space-y-4">
            @foreach($faqs as $faq)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden faq-item">
                    <button 
                        type="button"
                        class="faq-toggle w-full px-6 py-4 text-left hover:bg-gray-50 transition flex justify-between items-center"
                        data-faq-id="{{ $faq->id }}"
                    >
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900 text-lg mb-1">{{ $faq->question }}</h3>
                            <div class="flex gap-2 items-center">
                                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">{{ $faq->category }}</span>
                                <span class="text-xs text-gray-500"><i class="fas fa-eye mr-1"></i>{{ $faq->view_count }}</span>
                            </div>
                        </div>
                        <i class="fas fa-chevron-down text-gray-400 ml-4 transition-transform duration-300"></i>
                    </button>
                    
                    <div class="faq-content hidden px-6 py-4 bg-gray-50 border-t">
                        <div class="prose prose-sm max-w-none text-gray-700 mb-4">
                            {{ nl2br($faq->answer) }}
                        </div>
                        
                        @if($faq->keywords)
                            <div class="pt-4 border-t">
                                <p class="text-xs text-gray-600 mb-2"><strong>Related:</strong></p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach(array_map('trim', explode(',', $faq->keywords)) as $keyword)
                                        <a 
                                            href="/faqs?search={{ urlencode($keyword) }}" 
                                            class="text-xs bg-gray-200 text-gray-700 px-3 py-1 rounded hover:bg-gray-300"
                                        >
                                            {{ $keyword }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="pt-4 mt-4 border-t flex gap-4 text-sm">
                            <a 
                                href="/faqs/{{ $faq->id }}" 
                                class="text-blue-600 hover:text-blue-800 font-semibold"
                            >
                                View Full Answer
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $faqs->links() }}
        </div>
    @else
        <!-- No Results -->
        <div class="text-center py-12">
            <i class="fas fa-search text-5xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No FAQs Found</h3>
            <p class="text-gray-600 mb-6">
                @if(request('search'))
                    No FAQs match your search "<strong>{{ request('search') }}</strong>"
                @else
                    No FAQs available for this category
                @endif
            </p>
            <a href="/faqs" class="text-blue-600 hover:text-blue-800 font-semibold">
                View all FAQs →
            </a>
        </div>
    @endif
</div>

<!-- Contact Support Section -->
<div class="bg-blue-50 border-t-4 border-blue-600">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 text-center">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Didn't find your answer?</h2>
        <p class="text-gray-600 mb-6">Our support team is ready to help</p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="mailto:support@shipparts.com" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold">
                <i class="fas fa-envelope mr-2"></i>Email Support
            </a>
            <a href="tel:+911234567890" class="border border-blue-600 text-blue-600 hover:bg-blue-50 px-6 py-3 rounded-lg font-semibold">
                <i class="fas fa-phone mr-2"></i>Call Us
            </a>
        </div>
    </div>
</div>

<script>
    // FAQ Accordion Toggle
    document.querySelectorAll('.faq-toggle').forEach(button => {
        button.addEventListener('click', function() {
            const content = this.nextElementSibling;
            const icon = this.querySelector('i');
            
            // Close all other FAQs
            document.querySelectorAll('.faq-item .faq-content').forEach(el => {
                if (el !== content) {
                    el.classList.add('hidden');
                    el.previousElementSibling.querySelector('i').style.transform = '';
                }
            });

            // Toggle current
            content.classList.toggle('hidden');
            icon.style.transform = content.classList.contains('hidden') ? '' : 'rotate(180deg)';
        });
    });
</script>
@endsection

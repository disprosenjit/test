@extends('layouts.app')

@section('title', __('Properties'))

@section('content')
    <!-- Page Header -->
    <section class="bg-slate-900 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl sm:text-4xl font-bold text-white font-[Inter]">{{ __('Our Properties') }}</h1>
            <nav class="mt-4" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm">
                    <li><a href="{{ url('/') }}" class="text-slate-300 hover:text-white transition-colors">{{ __('Home') }}</a></li>
                    <li><span class="text-slate-500">/</span></li>
                    <li><span class="text-blue-400">{{ __('Properties') }}</span></li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Filter Bar -->
    <section class="bg-white border-b border-slate-200 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="{{ route('properties.filter') }}" method="GET">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                    <!-- Type Select -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-slate-700 mb-1">{{ __('Property Type') }}</label>
                        <select id="type" name="type" class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-slate-900 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors">
                            <option value="">{{ __('All Types') }}</option>
                            <option value="residential" {{ old('type') == 'residential' ? 'selected' : '' }}>{{ __('Residential') }}</option>
                            <option value="commercial" {{ old('type') == 'commercial' ? 'selected' : '' }}>{{ __('Commercial') }}</option>
                            <option value="land" {{ old('type') == 'land' ? 'selected' : '' }}>{{ __('Land') }}</option>
                        </select>
                    </div>

                    <!-- Location Input -->
                    <div>
                        <label for="location" class="block text-sm font-medium text-slate-700 mb-1">{{ __('Location') }}</label>
                        <input type="text" id="location" name="location" value="{{ old('location') }}" placeholder="{{ __('Enter location') }}" class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors">
                    </div>

                    <!-- Min Price -->
                    <div>
                        <label for="min_price" class="block text-sm font-medium text-slate-700 mb-1">{{ __('Min Price') }}</label>
                        <input type="number" id="min_price" name="min_price" value="{{ old('min_price') }}" placeholder="{{ __('Min') }}" min="0" class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors">
                    </div>

                    <!-- Max Price -->
                    <div>
                        <label for="max_price" class="block text-sm font-medium text-slate-700 mb-1">{{ __('Max Price') }}</label>
                        <input type="number" id="max_price" name="max_price" value="{{ old('max_price') }}" placeholder="{{ __('Max') }}" min="0" class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors">
                    </div>

                    <!-- Search Button -->
                    <div>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-6 rounded-lg transition-colors focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <i class="fas fa-search mr-2"></i>{{ __('Search') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- Property Grid -->
    <section class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @forelse($properties as $property)
                @if($loop->first)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @endif

                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition-shadow">
                    <!-- Property Image -->
                    <div class="relative bg-slate-100 h-56 overflow-hidden">
                        @if($property->images->count())
                            <img src="{{ asset($property->images->sortBy('order')->first()->image_path) }}"
                                 alt="{{ $property->images->sortBy('order')->first()->alt_text ?? $property->title }}"
                                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-image text-slate-300 text-5xl"></i>
                            </div>
                        @endif

                        <!-- Badges -->
                        <div class="absolute top-3 left-3 flex items-center space-x-2">
                            @if($property->is_featured)
                                <span class="bg-blue-600 text-white text-xs font-semibold px-2.5 py-1 rounded-lg">{{ __('Featured') }}</span>
                            @endif
                            <span class="bg-slate-900 text-white text-xs font-semibold px-2.5 py-1 rounded-lg capitalize">{{ __(ucfirst($property->type)) }}</span>
                        </div>
                    </div>

                    <!-- Card Content -->
                    <div class="p-5">
                        <h3 class="text-lg font-semibold text-slate-900 mb-1 line-clamp-1">{{ $property->title }}</h3>

                        <p class="flex items-center text-slate-500 text-sm mb-3">
                            <i class="fas fa-map-marker-alt mr-1.5 text-slate-400"></i>
                            {{ $property->location }}
                        </p>

                        <p class="text-2xl font-bold text-blue-600 mb-4">${{ number_format($property->price, 0) }}</p>

                        <!-- Stats Row -->
                        <div class="flex items-center justify-between text-slate-500 text-sm border-t border-slate-100 pt-4 mb-4">
                            <span class="flex items-center">
                                <i class="fas fa-bed mr-1.5"></i>{{ $property->bedrooms }} {{ __('Beds') }}
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-bath mr-1.5"></i>{{ $property->bathrooms }} {{ __('Baths') }}
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-ruler-combined mr-1.5"></i>{{ number_format($property->area) }} {{ __('sqft') }}
                            </span>
                        </div>

                        <a href="{{ route('properties.show', $property) }}" class="block text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg transition-colors">
                            {{ __('View Details') }}
                        </a>
                    </div>
                </div>

                @if($loop->last)
                    </div>
                @endif
            @empty
                <div class="text-center py-16">
                    <i class="fas fa-building text-slate-300 text-6xl mb-4"></i>
                    <h3 class="text-xl font-semibold text-slate-900 mb-2">{{ __('No Properties Found') }}</h3>
                    <p class="text-slate-500">{{ __('We couldn\'t find any properties matching your criteria. Try adjusting your filters.') }}</p>
                </div>
            @endforelse

            <!-- Pagination -->
            <div class="mt-12 flex justify-center">
                {{ $properties->links() }}
            </div>
        </div>
    </section>
@endsection

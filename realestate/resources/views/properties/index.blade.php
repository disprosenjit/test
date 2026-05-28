@extends('layouts.app')

@section('title', 'Properties')

@section('content')
    <!-- Page Header -->
    <section class="bg-slate-900 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl sm:text-4xl font-bold text-white font-[Inter]">Our Properties</h1>
            <nav class="mt-4" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm">
                    <li><a href="{{ url('/') }}" class="text-slate-300 hover:text-white transition-colors">Home</a></li>
                    <li><span class="text-slate-500">/</span></li>
                    <li><span class="text-blue-400">Properties</span></li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Filter Bar -->
    <section class="bg-white border-b border-slate-200 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="{{ route('properties.filter') }}" method="GET"
                  x-data="{
                      locationData: {{ json_encode($hierarchy ?? []) }},
                      selectedCountry: '{{ request('country', '') }}',
                      selectedState: '{{ request('state', '') }}',
                      selectedCity: '{{ request('city', '') }}',
                      get states() {
                          if (!this.selectedCountry || !this.locationData[this.selectedCountry]) return [];
                          return Object.keys(this.locationData[this.selectedCountry]).filter(s => s !== '');
                      },
                      get cities() {
                          if (!this.selectedCountry || !this.selectedState) return [];
                          const stateCities = this.locationData[this.selectedCountry]?.[this.selectedState];
                          return stateCities ? stateCities.filter(c => c) : [];
                      },
                      onCountryChange() { this.selectedState = ''; this.selectedCity = ''; },
                      onStateChange()   { this.selectedCity = ''; }
                  }">
                <div class="flex flex-nowrap items-end gap-2">
                    <!-- Property Type -->
                    <div class="flex-1 min-w-0">
                        <label for="type" class="block text-xs font-medium text-slate-600 mb-1">Type</label>
                        <select id="type" name="type" class="w-full border border-slate-200 rounded-lg px-2 py-2.5 text-slate-900 bg-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors">
                            <option value="">All Types</option>
                            <option value="residential" {{ request('type') == 'residential' ? 'selected' : '' }}>Residential</option>
                            <option value="commercial"  {{ request('type') == 'commercial'  ? 'selected' : '' }}>Commercial</option>
                            <option value="land"        {{ request('type') == 'land'        ? 'selected' : '' }}>Land</option>
                        </select>
                    </div>

                    <!-- Country -->
                    <div class="flex-1 min-w-0">
                        <label for="country" class="block text-xs font-medium text-slate-600 mb-1">Country</label>
                        <select id="country" name="country"
                                x-model="selectedCountry"
                                @change="onCountryChange()"
                                class="w-full border border-slate-200 rounded-lg px-2 py-2.5 text-slate-900 bg-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors">
                            <option value="">All Countries</option>
                            @foreach($countries ?? [] as $country)
                                <option value="{{ $country }}">{{ $country }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- State -->
                    <div class="flex-1 min-w-0">
                        <label for="state" class="block text-xs font-medium text-slate-600 mb-1">State</label>
                        <select id="state" name="state"
                                x-model="selectedState"
                                @change="onStateChange()"
                                class="w-full border border-slate-200 rounded-lg px-2 py-2.5 text-slate-900 bg-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                                :disabled="states.length === 0">
                            <option value="">All States</option>
                            <template x-for="s in states" :key="s">
                                <option :value="s" :selected="s === selectedState" x-text="s"></option>
                            </template>
                        </select>
                    </div>

                    <!-- City -->
                    <div class="flex-1 min-w-0">
                        <label for="city" class="block text-xs font-medium text-slate-600 mb-1">City</label>
                        <select id="city" name="city"
                                x-model="selectedCity"
                                class="w-full border border-slate-200 rounded-lg px-2 py-2.5 text-slate-900 bg-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                                :disabled="cities.length === 0">
                            <option value="">All Cities</option>
                            <template x-for="c in cities" :key="c">
                                <option :value="c" :selected="c === selectedCity" x-text="c"></option>
                            </template>
                        </select>
                    </div>

                    <!-- Min Price -->
                    <div class="flex-1 min-w-0">
                        <label for="min_price" class="block text-xs font-medium text-slate-600 mb-1">Min Price</label>
                        <input type="number" id="min_price" name="min_price" value="{{ request('min_price') }}" placeholder="Min" min="0" class="w-full border border-slate-200 rounded-lg px-2 py-2.5 text-slate-900 text-sm placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors">
                    </div>

                    <!-- Max Price -->
                    <div class="flex-1 min-w-0">
                        <label for="max_price" class="block text-xs font-medium text-slate-600 mb-1">Max Price</label>
                        <input type="number" id="max_price" name="max_price" value="{{ request('max_price') }}" placeholder="Max" min="0" class="w-full border border-slate-200 rounded-lg px-2 py-2.5 text-slate-900 text-sm placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors">
                    </div>

                    <!-- Search Button -->
                    <div class="flex-shrink-0">
                        <button type="submit" class="whitespace-nowrap bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-5 rounded-lg text-sm transition-colors focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <i class="fas fa-search mr-1.5"></i>Search
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
                                <span class="bg-blue-600 text-white text-xs font-semibold px-2.5 py-1 rounded-lg">Featured</span>
                            @endif
                            <span class="bg-slate-900 text-white text-xs font-semibold px-2.5 py-1 rounded-lg capitalize">{{ $property->type }}</span>
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
                                <i class="fas fa-bed mr-1.5"></i>{{ $property->bedrooms }} Beds
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-bath mr-1.5"></i>{{ $property->bathrooms }} Baths
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-ruler-combined mr-1.5"></i>{{ number_format($property->area) }} sqft
                            </span>
                        </div>

                        <a href="{{ route('properties.show', $property) }}" class="block text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg transition-colors">
                            View Details
                        </a>
                    </div>
                </div>

                @if($loop->last)
                    </div>
                @endif
            @empty
                <div class="text-center py-16">
                    <i class="fas fa-building text-slate-300 text-6xl mb-4"></i>
                    <h3 class="text-xl font-semibold text-slate-900 mb-2">No Properties Found</h3>
                    <p class="text-slate-500">We couldn't find any properties matching your criteria. Try adjusting your filters.</p>
                </div>
            @endforelse

            <!-- Pagination -->
            <div class="mt-12 flex justify-center">
                {{ $properties->links() }}
            </div>
        </div>
    </section>
@endsection

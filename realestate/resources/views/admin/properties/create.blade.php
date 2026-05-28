@extends('admin.layout')

@section('title', 'Add Property')

@section('content')
    {{-- Header --}}
    <div class="mb-8">
        <a href="{{ route('admin.properties.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-900 mb-4 transition-colors">
            <i class="fas fa-arrow-left"></i>
            Back to Properties
        </a>
        <h1 class="text-2xl font-bold text-slate-900">Add New Property</h1>
        <p class="mt-1 text-sm text-slate-600">Fill in the details to create a new property listing.</p>
    </div>

    {{-- Form --}}
    <form action="{{ route('admin.properties.store') }}" method="POST">
        @csrf

        <div class="space-y-6">
            {{-- Basic Information --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Basic Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="title" class="block text-sm font-medium text-slate-700 mb-1">Title</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                               placeholder="Enter property title">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                        <textarea name="description" id="description" rows="4"
                                  class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors resize-y"
                                  placeholder="Describe the property">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="price" class="block text-sm font-medium text-slate-700 mb-1">Price ($)</label>
                        <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01" min="0"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                               placeholder="0.00">
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-slate-700 mb-1">Type</label>
                        <select name="type" id="type"
                                class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                            <option value="">Select type</option>
                            <option value="residential" {{ old('type') == 'residential' ? 'selected' : '' }}>Residential</option>
                            <option value="commercial" {{ old('type') == 'commercial' ? 'selected' : '' }}>Commercial</option>
                            <option value="land" {{ old('type') == 'land' ? 'selected' : '' }}>Land</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Location --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Location</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="country" class="block text-sm font-medium text-slate-700 mb-1">Country</label>
                        <input type="text" name="country" id="country" value="{{ old('country') }}"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                               placeholder="e.g. United States">
                        @error('country')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="state" class="block text-sm font-medium text-slate-700 mb-1">State / Province</label>
                        <input type="text" name="state" id="state" value="{{ old('state') }}"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                               placeholder="e.g. California">
                        @error('state')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="city" class="block text-sm font-medium text-slate-700 mb-1">City</label>
                        <input type="text" name="city" id="city" value="{{ old('city') }}"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                               placeholder="e.g. Los Angeles">
                        @error('city')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="location" class="block text-sm font-medium text-slate-700 mb-1">Location Label</label>
                        <input type="text" name="location" id="location" value="{{ old('location') }}"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                               placeholder="e.g. Downtown Los Angeles">
                        @error('location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-slate-700 mb-1">Street Address</label>
                        <textarea name="address" id="address" rows="2"
                                  class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors resize-y"
                                  placeholder="Full street address">{{ old('address') }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="latitude" class="block text-sm font-medium text-slate-700 mb-1">Latitude <span class="text-slate-400 font-normal">(optional)</span></label>
                        <input type="number" name="latitude" id="latitude" value="{{ old('latitude') }}" step="any" min="-90" max="90"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                               placeholder="e.g. 34.0522">
                        @error('latitude')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="longitude" class="block text-sm font-medium text-slate-700 mb-1">Longitude <span class="text-slate-400 font-normal">(optional)</span></label>
                        <input type="number" name="longitude" id="longitude" value="{{ old('longitude') }}" step="any" min="-180" max="180"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                               placeholder="e.g. -118.2437">
                        @error('longitude')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Property Details --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Property Details</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label for="area" class="block text-sm font-medium text-slate-700 mb-1">Area (sq ft)</label>
                        <input type="number" name="area" id="area" value="{{ old('area') }}" min="0"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                               placeholder="0">
                        @error('area')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="bedrooms" class="block text-sm font-medium text-slate-700 mb-1">Bedrooms</label>
                        <input type="number" name="bedrooms" id="bedrooms" value="{{ old('bedrooms') }}" min="0"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                               placeholder="0">
                        @error('bedrooms')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="bathrooms" class="block text-sm font-medium text-slate-700 mb-1">Bathrooms</label>
                        <input type="number" name="bathrooms" id="bathrooms" value="{{ old('bathrooms') }}" min="0"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                               placeholder="0">
                        @error('bathrooms')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="parking_spaces" class="block text-sm font-medium text-slate-700 mb-1">Parking Spaces</label>
                        <input type="number" name="parking_spaces" id="parking_spaces" value="{{ old('parking_spaces') }}" min="0"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                               placeholder="0">
                        @error('parking_spaces')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Options --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Options</h2>
                <div class="flex flex-wrap gap-6">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                        <span class="text-sm font-medium text-slate-700">Featured Property</span>
                    </label>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_available" value="1" {{ old('is_available', true) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                        <span class="text-sm font-medium text-slate-700">Available</span>
                    </label>
                </div>
            </div>

            {{-- Agent Information --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Agent Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="agent_name" class="block text-sm font-medium text-slate-700 mb-1">Agent Name</label>
                        <input type="text" name="agent_name" id="agent_name" value="{{ old('agent_name') }}"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                               placeholder="Agent full name">
                        @error('agent_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="agent_contact" class="block text-sm font-medium text-slate-700 mb-1">Agent Contact</label>
                        <input type="text" name="agent_contact" id="agent_contact" value="{{ old('agent_contact') }}"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                               placeholder="Phone or email">
                        @error('agent_contact')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex items-center justify-end gap-4">
                <a href="{{ route('admin.properties.index') }}"
                   class="px-6 py-2.5 border border-slate-200 text-slate-700 text-sm font-medium rounded-xl hover:bg-slate-50 transition-colors">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors shadow-sm">
                    Create Property
                </button>
            </div>
        </div>
    </form>
@endsection

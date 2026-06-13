@extends('admin.layout')

@section('title', 'Edit Property')

@section('content')
    {{-- Header --}}
    <div class="mb-8">
        <a href="{{ route('admin.properties.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-900 mb-4 transition-colors">
            <i class="fas fa-arrow-left"></i>
            Back to Properties
        </a>
        <h1 class="text-2xl font-bold text-slate-900">Edit Property</h1>
        <p class="mt-1 text-sm text-slate-600">Update the property listing details.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    {{-- Property Images --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">Property Images</h2>
                <p class="text-sm text-slate-500 mt-1">{{ $property->images->count() }} image(s) uploaded</p>
            </div>
        </div>

        @if($property->images->count())
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
                @foreach($property->images->sortBy('order') as $image)
                    <div class="relative rounded-xl overflow-hidden border border-slate-200 bg-slate-50">
                        <img src="{{ asset($image->image_path) }}"
                             alt="{{ $image->alt_text ?? 'Property image' }}"
                             class="w-full h-40 object-cover">
                        <form action="{{ route('admin.properties.images.destroy', [$property, $image]) }}" method="POST"
                              class="absolute top-2 right-2"
                              onsubmit="return confirm('Are you sure you want to delete this image?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="w-8 h-8 bg-red-600 hover:bg-red-700 text-white rounded-lg flex items-center justify-center shadow-md transition-colors"
                                    title="Delete image">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </form>
                        <div class="px-3 py-2 text-xs text-slate-500 truncate">{{ $image->alt_text ?? 'No description' }}</div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 mb-8 border-2 border-dashed border-slate-200 rounded-xl">
                <i class="fas fa-images text-slate-300 text-4xl mb-3"></i>
                <p class="text-slate-500 text-sm">No images uploaded yet</p>
            </div>
        @endif

        <form action="{{ route('admin.properties.images.store', $property) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="border-2 border-dashed border-slate-300 rounded-xl p-6 text-center hover:border-blue-400 transition-colors">
                <i class="fas fa-cloud-upload-alt text-slate-400 text-3xl mb-3"></i>
                <p class="text-sm font-medium text-slate-700 mb-1">Upload new images</p>
                <p class="text-xs text-slate-500 mb-4">JPEG, PNG, or WebP. Max 5MB each.</p>
                <input type="file" name="images[]" multiple accept="image/jpeg,image/png,image/webp"
                       class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 cursor-pointer">
                @error('images')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('images.*')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="mt-4 flex justify-end">
                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors shadow-sm">
                    <i class="fas fa-upload mr-2"></i>Upload Images
                </button>
            </div>
        </form>
    </div>

    {{-- Property Details Form --}}
    <form action="{{ route('admin.properties.update', $property) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            {{-- Basic Information --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Basic Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="title" class="block text-sm font-medium text-slate-700 mb-1">Title</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $property->title) }}"
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
                                  placeholder="Describe the property">{{ old('description', $property->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="price" class="block text-sm font-medium text-slate-700 mb-1">Price ($)</label>
                        <input type="number" name="price" id="price" value="{{ old('price', $property->price) }}" step="0.01" min="0"
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
                            <option value="residential" {{ old('type', $property->type) == 'residential' ? 'selected' : '' }}>Residential</option>
                            <option value="commercial" {{ old('type', $property->type) == 'commercial' ? 'selected' : '' }}>Commercial</option>
                            <option value="land" {{ old('type', $property->type) == 'land' ? 'selected' : '' }}>Land</option>
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
                        <input type="text" name="country" id="country" value="{{ old('country', $property->country) }}"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                               placeholder="e.g. United States">
                        @error('country')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="state" class="block text-sm font-medium text-slate-700 mb-1">State / Province</label>
                        <input type="text" name="state" id="state" value="{{ old('state', $property->state) }}"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                               placeholder="e.g. California">
                        @error('state')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="city" class="block text-sm font-medium text-slate-700 mb-1">City</label>
                        <input type="text" name="city" id="city" value="{{ old('city', $property->city) }}"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                               placeholder="e.g. Los Angeles">
                        @error('city')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="location" class="block text-sm font-medium text-slate-700 mb-1">Location Label</label>
                        <input type="text" name="location" id="location" value="{{ old('location', $property->location) }}"
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
                                  placeholder="Full street address">{{ old('address', $property->address) }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            Pin Location on Map
                            <span class="text-slate-400 font-normal ml-1">— click the map or use "Find on Map"</span>
                        </label>
                        <div class="relative">
                            <div id="location-map"
                                 data-lat="{{ old('latitude', $property->latitude) }}"
                                 data-lng="{{ old('longitude', $property->longitude) }}"
                                 style="height:350px; border-radius:0.75rem; border:1px solid #e2e8f0; z-index:0;"></div>
                            <div class="absolute bottom-3 left-3 z-10">
                                <button type="button" id="geocode-btn"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-700 shadow-md hover:bg-slate-50 transition-colors">
                                    <i class="fas fa-search-location text-blue-600"></i>Find on Map
                                </button>
                            </div>
                        </div>
                        <p class="mt-1.5 text-xs text-slate-500">Click anywhere on the map to place a pin, or click <strong>Find on Map</strong> to geocode from the address fields. The marker is draggable.</p>
                    </div>

                    {{-- Hidden coords submitted with form --}}
                    <input type="hidden" name="latitude"  id="latitude"  value="{{ old('latitude', $property->latitude) }}">
                    <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $property->longitude) }}">

                    {{-- Coordinate readout --}}
                    <div class="md:col-span-2 flex items-center gap-4">
                        <div class="flex items-center gap-2 text-sm text-slate-600">
                            <i class="fas fa-map-pin text-blue-500"></i>
                            <span>Coordinates:</span>
                            <code id="lat-display" class="text-slate-500">{{ old('latitude', $property->latitude) ?: '—' }}</code>
                            <span class="text-slate-400">/</span>
                            <code id="lng-display" class="text-slate-500">{{ old('longitude', $property->longitude) ?: '—' }}</code>
                        </div>
                        <button type="button" id="clear-marker-btn"
                                class="text-xs text-red-500 hover:text-red-700 {{ (old('latitude', $property->latitude)) ? '' : 'hidden' }}">
                            <i class="fas fa-times mr-1"></i>Clear pin
                        </button>
                    </div>
                </div>
            </div>

            {{-- Property Details --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Property Details</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label for="area" class="block text-sm font-medium text-slate-700 mb-1">Area (sq ft)</label>
                        <input type="number" name="area" id="area" value="{{ old('area', $property->area) }}" min="0"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                               placeholder="0">
                        @error('area')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="bedrooms" class="block text-sm font-medium text-slate-700 mb-1">Bedrooms</label>
                        <input type="number" name="bedrooms" id="bedrooms" value="{{ old('bedrooms', $property->bedrooms) }}" min="0"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                               placeholder="0">
                        @error('bedrooms')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="bathrooms" class="block text-sm font-medium text-slate-700 mb-1">Bathrooms</label>
                        <input type="number" name="bathrooms" id="bathrooms" value="{{ old('bathrooms', $property->bathrooms) }}" min="0"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                               placeholder="0">
                        @error('bathrooms')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="parking_spaces" class="block text-sm font-medium text-slate-700 mb-1">Parking Spaces</label>
                        <input type="number" name="parking_spaces" id="parking_spaces" value="{{ old('parking_spaces', $property->parking_spaces) }}" min="0"
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
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $property->is_featured) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                        <span class="text-sm font-medium text-slate-700">Featured Property</span>
                    </label>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_available" value="1" {{ old('is_available', $property->is_available) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                        <span class="text-sm font-medium text-slate-700">Available</span>
                    </label>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="hide_agent_info" value="1" {{ old('hide_agent_info', $property->hide_agent_info) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                        <span class="text-sm font-medium text-slate-700">Hide agent information from public</span>
                    </label>
                </div>
            </div>

            {{-- Agent Information --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Agent Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="agent_name" class="block text-sm font-medium text-slate-700 mb-1">Agent Name</label>
                        <input type="text" name="agent_name" id="agent_name" value="{{ old('agent_name', $property->agent_name) }}"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                               placeholder="Agent full name">
                        @error('agent_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="agent_contact" class="block text-sm font-medium text-slate-700 mb-1">Agent Contact</label>
                        <input type="text" name="agent_contact" id="agent_contact" value="{{ old('agent_contact', $property->agent_contact) }}"
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
                    Update Property
                </button>
            </div>
        </div>
    </form>
@endsection

@push('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var mapEl    = document.getElementById('location-map');
    var latInput = document.getElementById('latitude');
    var lngInput = document.getElementById('longitude');
    var latDisp  = document.getElementById('lat-display');
    var lngDisp  = document.getElementById('lng-display');
    var clearBtn = document.getElementById('clear-marker-btn');
    var geoBtn   = document.getElementById('geocode-btn');

    var initLat  = parseFloat(mapEl.dataset.lat) || null;
    var initLng  = parseFloat(mapEl.dataset.lng) || null;
    var hasInit  = initLat && initLng;

    var map = L.map('location-map').setView(
        hasInit ? [initLat, initLng] : [20, 0],
        hasInit ? 14 : 2
    );

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19
    }).addTo(map);

    var marker = null;

    function setCoords(lat, lng) {
        latInput.value      = lat.toFixed(7);
        lngInput.value      = lng.toFixed(7);
        latDisp.textContent = lat.toFixed(6);
        lngDisp.textContent = lng.toFixed(6);
        clearBtn.classList.remove('hidden');
    }

    function reverseGeocode(lat, lng) {
        fetch('{{ route('geocoding.reverse') }}?lat=' + lat + '&lng=' + lng)
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.error) return;
            var f = {
                country:  document.getElementById('country'),
                state:    document.getElementById('state'),
                city:     document.getElementById('city'),
                location: document.getElementById('location'),
                address:  document.getElementById('address'),
            };
            if (f.country)  f.country.value  = data.country        || '';
            if (f.state)    f.state.value    = data.state          || '';
            if (f.city)     f.city.value     = data.city           || '';
            if (f.location) f.location.value = data.location_label || '';
            if (f.address)  f.address.value  = data.road           || '';
        })
        .catch(function () { /* best-effort — silently ignore */ });
    }

    function placeMarker(lat, lng, doReverse) {
        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng], { draggable: true }).addTo(map);
            marker.on('dragend', function (e) {
                var p = e.target.getLatLng();
                setCoords(p.lat, p.lng);
                reverseGeocode(p.lat, p.lng);
            });
        }
        setCoords(lat, lng);
        if (doReverse) reverseGeocode(lat, lng);
    }

    function clearMarker() {
        if (marker) { map.removeLayer(marker); marker = null; }
        latInput.value      = '';
        lngInput.value      = '';
        latDisp.textContent = '—';
        lngDisp.textContent = '—';
        clearBtn.classList.add('hidden');
    }

    if (hasInit) { placeMarker(initLat, initLng, false); }

    map.on('click', function (e) { placeMarker(e.latlng.lat, e.latlng.lng, true); });

    clearBtn.addEventListener('click', clearMarker);

    geoBtn.addEventListener('click', function () {
        var parts = [
            (document.getElementById('address') || {}).value,
            (document.getElementById('city')    || {}).value,
            (document.getElementById('state')   || {}).value,
            (document.getElementById('country') || {}).value,
        ].filter(Boolean);

        if (!parts.length) { alert('Please fill in at least one address field first.'); return; }

        geoBtn.disabled = true;
        geoBtn.innerHTML = '<i class="fas fa-spinner fa-spin text-blue-600"></i> Searching…';

        fetch('{{ route('geocoding.search') }}?q=' + encodeURIComponent(parts.join(', ')))
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.lat && data.lng) {
                placeMarker(data.lat, data.lng, false);
                map.setView([data.lat, data.lng], 15);
            } else {
                alert('Location not found. Try a more specific address.');
            }
        })
        .catch(function () { alert('Geocoding request failed. Check your connection.'); })
        .finally(function () {
            geoBtn.disabled = false;
            geoBtn.innerHTML = '<i class="fas fa-search-location text-blue-600"></i>Find on Map';
        });
    });
});
</script>
@endpush

@extends('layouts.app')

@section('title', $property->title)

@section('content')
    <!-- Breadcrumb Bar -->
    <section class="bg-slate-50 border-b border-slate-200 py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm">
                    <li><a href="{{ url('/') }}" class="text-slate-500 hover:text-slate-900 transition-colors">Home</a></li>
                    <li><span class="text-slate-300">/</span></li>
                    <li><a href="{{ route('properties.index') }}" class="text-slate-500 hover:text-slate-900 transition-colors">Properties</a></li>
                    <li><span class="text-slate-300">/</span></li>
                    <li><span class="text-slate-900 font-medium">{{ $property->title }}</span></li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Left Column -->
                <div class="lg:col-span-8">
                    <!-- Property Images -->
                    @if($property->images->count())
                        <div x-data="{
                            images: [
                                @foreach($property->images->sortBy('order') as $image)
                                    { src: '{{ asset($image->image_path) }}', alt: '{{ addslashes($image->alt_text ?? $property->title) }}' }{{ $loop->last ? '' : ',' }}
                                @endforeach
                            ],
                            active: 0,
                            lightbox: false,
                            next() { this.active = (this.active + 1) % this.images.length },
                            prev() { this.active = (this.active - 1 + this.images.length) % this.images.length },
                        }" x-on:keydown.escape.window="lightbox = false"
                           x-on:keydown.arrow-right.window="if(lightbox) next()"
                           x-on:keydown.arrow-left.window="if(lightbox) prev()"
                           class="mb-8">

                            <!-- Main Hero Image -->
                            <div class="rounded-2xl overflow-hidden mb-3 cursor-pointer relative group"
                                 x-on:click="lightbox = true">
                                <img :src="images[active].src"
                                     :alt="images[active].alt"
                                     class="w-full h-[28rem] object-cover transition-transform duration-300 group-hover:scale-[1.02]">
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors flex items-center justify-center">
                                    <span class="opacity-0 group-hover:opacity-100 transition-opacity bg-black/50 text-white text-sm font-medium px-4 py-2 rounded-lg backdrop-blur-sm">
                                        <i class="fas fa-expand mr-2"></i>View Slideshow
                                    </span>
                                </div>
                            </div>

                            <!-- Scrollable Thumbnails -->
                            @if($property->images->count() > 1)
                                <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-thin">
                                    <template x-for="(img, index) in images" :key="index">
                                        <button x-on:click="active = index"
                                                class="flex-shrink-0 rounded-xl overflow-hidden border-2 transition-all duration-200"
                                                :class="active === index ? 'border-blue-600 ring-2 ring-blue-600/30' : 'border-transparent hover:border-slate-300'">
                                            <img :src="img.src" :alt="img.alt"
                                                 class="w-24 h-16 object-cover">
                                        </button>
                                    </template>
                                </div>
                            @endif

                            <!-- Fullscreen Lightbox -->
                            <div x-show="lightbox" x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                 class="fixed inset-0 z-50 bg-black/95 flex items-center justify-center"
                                 x-cloak>

                                <!-- Close -->
                                <button x-on:click="lightbox = false"
                                        class="absolute top-4 right-4 w-10 h-10 bg-white/10 hover:bg-white/20 text-white rounded-full flex items-center justify-center backdrop-blur-sm transition-colors z-10">
                                    <i class="fas fa-times text-lg"></i>
                                </button>

                                <!-- Counter -->
                                <div class="absolute top-4 left-4 text-white/70 text-sm font-medium z-10">
                                    <span x-text="(active + 1) + ' / ' + images.length"></span>
                                </div>

                                <!-- Prev -->
                                <button x-on:click="prev()"
                                        class="absolute left-4 w-12 h-12 bg-white/10 hover:bg-white/20 text-white rounded-full flex items-center justify-center backdrop-blur-sm transition-colors z-10">
                                    <i class="fas fa-chevron-left"></i>
                                </button>

                                <!-- Image -->
                                <img :src="images[active].src" :alt="images[active].alt"
                                     class="max-h-[85vh] max-w-[90vw] object-contain rounded-lg shadow-2xl">

                                <!-- Next -->
                                <button x-on:click="next()"
                                        class="absolute right-4 w-12 h-12 bg-white/10 hover:bg-white/20 text-white rounded-full flex items-center justify-center backdrop-blur-sm transition-colors z-10">
                                    <i class="fas fa-chevron-right"></i>
                                </button>

                                <!-- Lightbox Thumbnails -->
                                <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2 max-w-[80vw] overflow-x-auto pb-1">
                                    <template x-for="(img, index) in images" :key="'lb-'+index">
                                        <button x-on:click="active = index"
                                                class="flex-shrink-0 rounded-lg overflow-hidden border-2 transition-all duration-200"
                                                :class="active === index ? 'border-white opacity-100' : 'border-transparent opacity-50 hover:opacity-75'">
                                            <img :src="img.src" :alt="img.alt"
                                                 class="w-16 h-12 object-cover">
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-slate-100 h-[28rem] rounded-2xl flex items-center justify-center mb-8">
                            <i class="fas fa-image text-slate-300 text-7xl"></i>
                        </div>
                    @endif

                    <!-- Title & Location -->
                    <div class="mb-6">
                        <div class="flex items-center space-x-3 mb-2">
                            @if($property->is_featured)
                                <span class="bg-blue-600 text-white text-xs font-semibold px-2.5 py-1 rounded-lg">Featured</span>
                            @endif
                            <span class="bg-slate-100 text-slate-700 text-xs font-semibold px-2.5 py-1 rounded-lg capitalize">{{ $property->type }}</span>
                            @if($property->is_available)
                                <span class="bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-lg">Available</span>
                            @else
                                <span class="bg-red-100 text-red-700 text-xs font-semibold px-2.5 py-1 rounded-lg">Unavailable</span>
                            @endif
                        </div>
                        <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 font-[Inter] mb-3">{{ $property->title }}</h1>
                        <p class="flex items-center text-slate-500 text-lg">
                            <i class="fas fa-map-marker-alt mr-2 text-blue-600"></i>
                            {{ $property->address }}, {{ $property->location }}
                        </p>
                    </div>

                    <!-- Description -->
                    <div class="mb-10">
                        <h2 class="text-xl font-semibold text-slate-900 mb-4">Description</h2>
                        <div class="text-slate-600 leading-relaxed space-y-4">
                            <p>{{ $property->description }}</p>
                        </div>
                    </div>

                    <!-- Features Grid -->
                    <div class="mb-10">
                        <h2 class="text-xl font-semibold text-slate-900 mb-6">Property Features</h2>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            <div class="bg-slate-50 rounded-xl p-5 text-center border border-slate-200">
                                <i class="fas fa-bed text-blue-600 text-2xl mb-3"></i>
                                <p class="text-2xl font-bold text-slate-900">{{ $property->bedrooms }}</p>
                                <p class="text-sm text-slate-500 mt-1">Bedrooms</p>
                            </div>
                            <div class="bg-slate-50 rounded-xl p-5 text-center border border-slate-200">
                                <i class="fas fa-bath text-blue-600 text-2xl mb-3"></i>
                                <p class="text-2xl font-bold text-slate-900">{{ $property->bathrooms }}</p>
                                <p class="text-sm text-slate-500 mt-1">Bathrooms</p>
                            </div>
                            <div class="bg-slate-50 rounded-xl p-5 text-center border border-slate-200">
                                <i class="fas fa-ruler-combined text-blue-600 text-2xl mb-3"></i>
                                <p class="text-2xl font-bold text-slate-900">{{ number_format($property->area) }}</p>
                                <p class="text-sm text-slate-500 mt-1">Sq Ft</p>
                            </div>
                            <div class="bg-slate-50 rounded-xl p-5 text-center border border-slate-200">
                                <i class="fas fa-car text-blue-600 text-2xl mb-3"></i>
                                <p class="text-2xl font-bold text-slate-900">{{ $property->parking_spaces }}</p>
                                <p class="text-sm text-slate-500 mt-1">Parking</p>
                            </div>
                        </div>
                    </div>

                    <!-- Map -->
                    <div>
                        <h2 class="text-xl font-semibold text-slate-900 mb-4">
                            <i class="fas fa-map-marker-alt text-blue-600 mr-2"></i>Location on Map
                        </h2>
                        <div id="property-map" style="height:380px; border-radius:1rem; border:1px solid #e2e8f0;"></div>
                        <p class="text-sm text-slate-500 mt-2">
                            <i class="fas fa-map-marker-alt mr-1 text-blue-500"></i>
                            {{ $property->address }}
                            @if($property->city), {{ $property->city }}@endif
                            @if($property->state), {{ $property->state }}@endif
                            @if($property->country), {{ $property->country }}@endif
                        </p>
                    </div>
                </div>

                <!-- Right Sidebar -->
                <div class="lg:col-span-4">
                    <div class="sticky top-8 space-y-4">

                        {{-- Booking / Pricing Card --}}
                        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">

                            {{-- Price per night --}}
                            <div class="mb-5 pb-5 border-b border-slate-200">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Rent</p>
                                <div class="flex items-baseline gap-1">
                                    <span class="text-3xl font-bold text-blue-600">${{ number_format($property->price, 2) }}</span>
                                    <span class="text-slate-500 text-sm">/ night</span>
                                </div>
                            </div>

                            {{-- Availability Calendar --}}
                            <div class="mb-5">
                                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">
                                    <i class="fas fa-calendar-alt mr-1 text-blue-500"></i>Availability
                                </p>

                                @if(session('success'))
                                    <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">
                                        <i class="fas fa-check-circle mr-1"></i>{{ session('success') }}
                                    </div>
                                @endif

                                @error('dates')
                                    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </div>
                                @enderror

                                {{-- Inline flatpickr calendar --}}
                                <div id="availability-calendar"></div>

                                {{-- Legend --}}
                                <div class="flex items-center gap-4 mt-3 text-xs text-slate-500">
                                    <span class="flex items-center gap-1.5">
                                        <span class="w-3 h-3 rounded-sm bg-blue-500 inline-block"></span>Available
                                    </span>
                                    <span class="flex items-center gap-1.5">
                                        <span class="w-3 h-3 rounded-sm bg-slate-200 inline-block"></span>Unavailable
                                    </span>
                                    <span class="flex items-center gap-1.5">
                                        <span class="w-3 h-3 rounded-sm bg-blue-100 border border-blue-400 inline-block"></span>Selected
                                    </span>
                                </div>
                            </div>

                            {{-- Booking form --}}
                            @auth
                                <form action="{{ route('bookings.store', $property) }}" method="POST" id="booking-form" class="space-y-4">
                                    @csrf
                                    <input type="hidden" name="check_in"  id="check_in_val">
                                    <input type="hidden" name="check_out" id="check_out_val">

                                    {{-- Selected range display --}}
                                    <div id="date-display" class="hidden rounded-lg bg-blue-50 border border-blue-100 p-3 text-sm">
                                        <div class="flex justify-between mb-1 text-slate-600">
                                            <span>Check-in</span>
                                            <strong id="disp-checkin" class="text-slate-900"></strong>
                                        </div>
                                        <div class="flex justify-between mb-2 text-slate-600">
                                            <span>Check-out</span>
                                            <strong id="disp-checkout" class="text-slate-900"></strong>
                                        </div>
                                        <div class="flex justify-between border-t border-blue-200 pt-2 font-semibold text-slate-800">
                                            <span id="disp-nights"></span>
                                            <span id="disp-total" class="text-blue-600"></span>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-medium text-slate-600 mb-1">Notes <span class="text-slate-400">(optional)</span></label>
                                        <textarea name="notes" rows="2"
                                                  class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                                                  placeholder="Any special requests…">{{ old('notes') }}</textarea>
                                    </div>

                                    <button type="submit" id="book-btn" disabled
                                            class="w-full py-3 bg-blue-600 hover:bg-blue-700 disabled:bg-slate-300 disabled:cursor-not-allowed text-white font-semibold rounded-xl transition-colors">
                                        <i class="fas fa-calendar-check mr-2"></i>Request Booking
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}?redirect={{ urlencode(request()->url()) }}"
                                   class="block w-full text-center py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-colors">
                                    <i class="fas fa-sign-in-alt mr-2"></i>Sign In to Book
                                </a>
                                <p class="mt-2 text-xs text-center text-slate-500">
                                    No account? <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Sign up free</a>
                                </p>
                            @endauth
                        </div>

                        {{-- Property Details --}}
                        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                            <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">Property Details</h3>
                            <table class="w-full">
                                <tbody class="text-sm">
                                    <tr class="border-b border-slate-100">
                                        <td class="py-2.5 text-slate-500">Type</td>
                                        <td class="py-2.5 text-slate-900 font-medium text-right capitalize">{{ $property->type }}</td>
                                    </tr>
                                    <tr class="border-b border-slate-100">
                                        <td class="py-2.5 text-slate-500">Location</td>
                                        <td class="py-2.5 text-slate-900 font-medium text-right">{{ $property->location }}</td>
                                    </tr>
                                    <tr class="border-b border-slate-100">
                                        <td class="py-2.5 text-slate-500">Area</td>
                                        <td class="py-2.5 text-slate-900 font-medium text-right">{{ number_format($property->area) }} sqft</td>
                                    </tr>
                                    <tr class="border-b border-slate-100">
                                        <td class="py-2.5 text-slate-500">Bedrooms</td>
                                        <td class="py-2.5 text-slate-900 font-medium text-right">{{ $property->bedrooms }}</td>
                                    </tr>
                                    <tr class="border-b border-slate-100">
                                        <td class="py-2.5 text-slate-500">Bathrooms</td>
                                        <td class="py-2.5 text-slate-900 font-medium text-right">{{ $property->bathrooms }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2.5 text-slate-500">Parking</td>
                                        <td class="py-2.5 text-slate-900 font-medium text-right">{{ $property->parking_spaces }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        {{-- Agent Info --}}
                        @if($property->agent_name)
                            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                                <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">Listed By</h3>
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-slate-900 font-semibold">{{ $property->agent_name }}</p>
                                        @if($property->agent_contact)
                                            <p class="text-slate-500 text-sm">{{ $property->agent_contact }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        <a href="{{ route('properties.index') }}" class="block text-center bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-3 px-6 rounded-xl transition-colors text-sm">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Properties
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .flatpickr-calendar.inline { width: 100% !important; box-shadow: none !important; border: 1px solid #e2e8f0 !important; border-radius: 0.75rem; }
        .flatpickr-day.booked { background: #f1f5f9 !important; color: #94a3b8 !important; cursor: not-allowed !important; text-decoration: line-through; }
        .flatpickr-day.booked:hover { background: #f1f5f9 !important; }
        .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange { background: #2563eb !important; border-color: #2563eb !important; }
        .flatpickr-day.inRange { background: #dbeafe !important; border-color: #dbeafe !important; color: #1e40af !important; box-shadow: -5px 0 0 #dbeafe, 5px 0 0 #dbeafe !important; }
    </style>
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {

        // ── Leaflet Map ──────────────────────────────────────────────────
        var mapEl = document.getElementById('property-map');
        if (mapEl) {
            var lat     = {{ $property->latitude  !== null ? (float) $property->latitude  : 'null' }};
            var lng     = {{ $property->longitude !== null ? (float) $property->longitude : 'null' }};
            var title   = {{ Js::from($property->title) }};
            var address = {{ Js::from(trim(implode(', ', array_filter([$property->address, $property->city, $property->state, $property->country])))) }};

            function buildMap(lat, lng) {
                var map = L.map('property-map', { scrollWheelZoom: false }).setView([lat, lng], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a>'
                }).addTo(map);
                L.marker([lat, lng]).addTo(map)
                    .bindPopup('<strong>' + title + '</strong><br><span style="color:#64748b;font-size:0.85em">' + address + '</span>')
                    .openPopup();
            }

            if (lat !== null && lng !== null) {
                buildMap(lat, lng);
            } else if (address) {
                mapEl.innerHTML = '<div style="display:flex;align-items:center;justify-content:center;height:100%;color:#94a3b8;font-size:0.9rem;"><i class="fas fa-spinner fa-spin" style="margin-right:8px"></i>Loading map…</div>';
                fetch('/geocoding/search?q=' + encodeURIComponent(address))
                .then(function (r) { return r.json(); })
                .then(function (d) {
                    if (d.lat) { mapEl.innerHTML = ''; buildMap(d.lat, d.lng); }
                    else { mapEl.innerHTML = '<div style="display:flex;align-items:center;justify-content:center;height:100%;color:#94a3b8;">Location not found</div>'; }
                })
                .catch(function () { mapEl.innerHTML = '<div style="display:flex;align-items:center;justify-content:center;height:100%;color:#94a3b8;">Map unavailable</div>'; });
            } else {
                mapEl.innerHTML = '<div style="display:flex;align-items:center;justify-content:center;height:100%;color:#94a3b8;">No location data</div>';
            }
        }

        // ── Availability Calendar ────────────────────────────────────────
        var calEl = document.getElementById('availability-calendar');
        if (!calEl) return;

        var pricePerDay = {{ (float) $property->price }};

        // Load booked dates then init calendar
        fetch('{{ route('properties.availability', $property) }}')
        .then(function (r) { return r.json(); })
        .then(function (data) {
            var bookedDates = data.booked_dates || [];

            flatpickr(calEl, {
                mode:        'range',
                inline:      true,
                minDate:     'today',
                dateFormat:  'Y-m-d',
                disable:     bookedDates,
                onDayCreate: function (dObj, dStr, fp, dayElem) {
                    var d = dayElem.dateObj;
                    if (!d) return;
                    var s = d.getFullYear() + '-' +
                            String(d.getMonth() + 1).padStart(2, '0') + '-' +
                            String(d.getDate()).padStart(2, '0');
                    if (bookedDates.indexOf(s) !== -1) {
                        dayElem.classList.add('booked');
                    }
                },
                onChange: function (selectedDates) {
                    if (selectedDates.length === 2) {
                        var checkIn  = selectedDates[0];
                        var checkOut = selectedDates[1];
                        var nights   = Math.round((checkOut - checkIn) / 86400000);
                        var total    = nights * pricePerDay;
                        var fmt = function (d) {
                            return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                        };
                        var toVal = function (d) {
                            return d.getFullYear() + '-' +
                                   String(d.getMonth() + 1).padStart(2, '0') + '-' +
                                   String(d.getDate()).padStart(2, '0');
                        };

                        document.getElementById('check_in_val').value  = toVal(checkIn);
                        document.getElementById('check_out_val').value = toVal(checkOut);
                        document.getElementById('disp-checkin').textContent  = fmt(checkIn);
                        document.getElementById('disp-checkout').textContent = fmt(checkOut);
                        document.getElementById('disp-nights').textContent   = nights + ' night' + (nights !== 1 ? 's' : '');
                        document.getElementById('disp-total').textContent    = '$' + total.toLocaleString('en-US', { minimumFractionDigits: 2 });
                        document.getElementById('date-display').classList.remove('hidden');
                        var btn = document.getElementById('book-btn');
                        if (btn) btn.disabled = false;
                    } else {
                        document.getElementById('date-display').classList.add('hidden');
                        var btn = document.getElementById('book-btn');
                        if (btn) btn.disabled = true;
                    }
                }
            });
        })
        .catch(function () {
            calEl.innerHTML = '<p class="text-sm text-slate-400 text-center py-4">Calendar unavailable</p>';
        });
    });
    </script>
@endpush

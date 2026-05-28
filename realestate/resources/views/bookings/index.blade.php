@extends('layouts.app')

@section('title', 'My Bookings')

@section('content')
<section class="bg-slate-50 border-b border-slate-200 py-4">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-sm">
                <li><a href="{{ url('/') }}" class="text-slate-500 hover:text-slate-900">Home</a></li>
                <li><span class="text-slate-300">/</span></li>
                <li><span class="text-slate-900 font-medium">My Bookings</span></li>
            </ol>
        </nav>
    </div>
</section>

<section class="py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-bold text-slate-900">My Bookings</h1>
            <a href="{{ route('properties.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-colors">
                <i class="fas fa-search"></i>Browse Properties
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-sm text-green-700 flex items-center gap-2">
                <i class="fas fa-check-circle text-green-500"></i>{{ session('success') }}
            </div>
        @endif

        @if($bookings->isEmpty())
            <div class="bg-white rounded-xl border border-slate-200 p-16 text-center">
                <i class="fas fa-calendar-times text-5xl text-slate-200 mb-4"></i>
                <p class="text-slate-500 font-medium">You have no bookings yet.</p>
                <a href="{{ route('properties.index') }}" class="mt-4 inline-flex items-center gap-2 text-blue-600 hover:underline text-sm">
                    <i class="fas fa-arrow-right"></i>Browse available properties
                </a>
            </div>
        @else
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm divide-y divide-slate-100">
                @foreach($bookings as $booking)
                    <div class="flex items-center gap-4 px-4 py-3">

                        {{-- Thumbnail --}}
                        <a href="{{ route('properties.show', $booking->property) }}"
                           class="shrink-0 w-14 h-14 rounded-lg overflow-hidden bg-slate-100 block">
                            @if($booking->property->images->first())
                                <img src="{{ asset($booking->property->images->first()->image_path) }}"
                                     alt="{{ $booking->property->title }}"
                                     class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                            @else
                                <div class="flex items-center justify-center w-full h-full text-slate-300">
                                    <i class="fas fa-building text-xl"></i>
                                </div>
                            @endif
                        </a>

                        {{-- Property name + location --}}
                        <div class="min-w-0 flex-1">
                            <a href="{{ route('properties.show', $booking->property) }}"
                               class="font-semibold text-slate-900 hover:text-blue-600 transition-colors text-sm truncate block">
                                {{ $booking->property->title }}
                            </a>
                            <p class="text-xs text-slate-400 truncate">
                                <i class="fas fa-map-marker-alt mr-1 text-blue-300"></i>
                                {{ collect([$booking->property->city, $booking->property->state, $booking->property->country])->filter()->implode(', ') }}
                            </p>
                        </div>

                        {{-- Dates --}}
                        <div class="shrink-0 text-center">
                            <p class="text-xs text-slate-400">Check-in</p>
                            <p class="text-sm font-medium text-slate-700 whitespace-nowrap">{{ $booking->check_in->format('M d, Y') }}</p>
                        </div>
                        <div class="shrink-0 flex items-center text-slate-300"><i class="fas fa-arrow-right text-xs"></i></div>
                        <div class="shrink-0 text-center">
                            <p class="text-xs text-slate-400">Check-out</p>
                            <p class="text-sm font-medium text-slate-700 whitespace-nowrap">{{ $booking->check_out->format('M d, Y') }}</p>
                        </div>

                        {{-- Nights --}}
                        <div class="hidden md:block shrink-0 text-center w-14">
                            <p class="text-xs text-slate-400">Nights</p>
                            <p class="text-sm font-medium text-slate-700">{{ $booking->total_days }}</p>
                        </div>

                        {{-- Total --}}
                        <div class="shrink-0 text-center w-24">
                            <p class="text-xs text-slate-400">Total</p>
                            <p class="text-sm font-semibold text-blue-600">${{ number_format($booking->total_price, 2) }}</p>
                        </div>

                        {{-- Status --}}
                        <div class="shrink-0">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $booking->statusBadgeClass() }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>

                        {{-- Cancel --}}
                        <div class="shrink-0 w-16 text-right">
                            @if($booking->isPending())
                                <form action="{{ route('bookings.cancel', $booking) }}" method="POST"
                                      onsubmit="return confirm('Cancel this booking?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-xs text-red-400 hover:text-red-600 font-medium transition-colors">
                                        <i class="fas fa-times mr-0.5"></i>Cancel
                                    </button>
                                </form>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>

            <div class="mt-8">{{ $bookings->links() }}</div>
        @endif
    </div>
</section>
@endsection

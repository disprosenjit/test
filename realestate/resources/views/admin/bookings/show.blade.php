@extends('admin.layout')

@section('title', 'Booking #' . $booking->id)

@section('content')
<div class="mb-8">
    <a href="{{ route('admin.bookings.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-900 mb-4">
        <i class="fas fa-arrow-left"></i>Back to Bookings
    </a>
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-slate-900">Booking #{{ $booking->id }}</h1>
        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $booking->statusBadgeClass() }}">
            {{ ucfirst($booking->status) }}
        </span>
    </div>
</div>

@if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-sm text-green-700 flex items-center gap-2">
        <i class="fas fa-check-circle text-green-500"></i>{{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Details --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Property --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <h2 class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-4">Property</h2>
            <div class="flex items-start gap-4">
                @if($booking->property->images->first())
                    <img src="{{ asset($booking->property->images->first()->image_path) }}"
                         alt="{{ $booking->property->title }}"
                         class="w-24 h-20 object-cover rounded-lg shrink-0">
                @endif
                <div>
                    <a href="{{ route('properties.show', $booking->property) }}" target="_blank"
                       class="font-semibold text-slate-900 hover:text-blue-600 transition-colors">
                        {{ $booking->property->title }}
                        <i class="fas fa-external-link-alt text-xs ml-1 text-slate-400"></i>
                    </a>
                    <p class="text-sm text-slate-500 mt-1">
                        <i class="fas fa-map-marker-alt mr-1 text-blue-400"></i>
                        {{ collect([$booking->property->address, $booking->property->city, $booking->property->state, $booking->property->country])->filter()->implode(', ') }}
                    </p>
                    <p class="text-sm text-slate-500 mt-1">
                        <i class="fas fa-tag mr-1 text-blue-400"></i>
                        ${{ number_format($booking->price_per_day, 2) }} / night
                    </p>
                </div>
            </div>
        </div>

        {{-- Booking Dates --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <h2 class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-4">Booking Dates</h2>
            <div class="grid grid-cols-3 gap-4 text-sm">
                <div class="bg-slate-50 rounded-lg p-4 text-center">
                    <p class="text-slate-400 text-xs mb-1">Check-in</p>
                    <p class="font-semibold text-slate-800">{{ $booking->check_in->format('M d, Y') }}</p>
                </div>
                <div class="bg-slate-50 rounded-lg p-4 text-center">
                    <p class="text-slate-400 text-xs mb-1">Check-out</p>
                    <p class="font-semibold text-slate-800">{{ $booking->check_out->format('M d, Y') }}</p>
                </div>
                <div class="bg-blue-50 rounded-lg p-4 text-center">
                    <p class="text-blue-400 text-xs mb-1">Total</p>
                    <p class="font-bold text-blue-700">{{ $booking->total_days }} nights</p>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-sm">
                <span class="text-slate-500">{{ $booking->total_days }} × ${{ number_format($booking->price_per_day, 2) }}</span>
                <span class="text-xl font-bold text-blue-600">${{ number_format($booking->total_price, 2) }}</span>
            </div>
        </div>

        {{-- Notes --}}
        @if($booking->notes)
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <h2 class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-3">Guest Notes</h2>
                <p class="text-sm text-slate-600 leading-relaxed">{{ $booking->notes }}</p>
            </div>
        @endif
    </div>

    {{-- Sidebar --}}
    <div class="space-y-6">

        {{-- Guest --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <h2 class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-4">Guest</h2>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center shrink-0">
                    <i class="fas fa-user text-blue-500"></i>
                </div>
                <div>
                    <p class="font-semibold text-slate-900">{{ $booking->user->name }}</p>
                    <p class="text-sm text-slate-500">{{ $booking->user->email }}</p>
                </div>
            </div>
            <p class="mt-3 text-xs text-slate-400">
                Member since {{ $booking->user->created_at->format('M Y') }}
            </p>
        </div>

        {{-- Update Status --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <h2 class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-4">Update Status</h2>
            <form action="{{ route('admin.bookings.status', $booking) }}" method="POST" class="space-y-3">
                @csrf
                @method('PATCH')
                <select name="status"
                        class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="pending"   {{ $booking->status === 'pending'   ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <button type="submit"
                        class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-colors">
                    Save Status
                </button>
            </form>
        </div>

        {{-- Meta --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 text-sm text-slate-500 space-y-2">
            <p><span class="font-medium text-slate-700">Created:</span> {{ $booking->created_at->format('M d, Y H:i') }}</p>
            <p><span class="font-medium text-slate-700">Updated:</span> {{ $booking->updated_at->format('M d, Y H:i') }}</p>
        </div>

        {{-- Delete --}}
        <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST"
              onsubmit="return confirm('Permanently delete this booking?')">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="w-full py-2.5 border border-red-200 text-red-600 hover:bg-red-50 text-sm font-medium rounded-xl transition-colors">
                <i class="fas fa-trash mr-2"></i>Delete Booking
            </button>
        </form>
    </div>
</div>
@endsection

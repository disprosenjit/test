@extends('admin.layout')

@section('title', 'Bookings')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-slate-900">Bookings</h1>
    <p class="mt-1 text-sm text-slate-600">All property rental bookings.</p>
</div>

@if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-sm text-green-700 flex items-center gap-2">
        <i class="fas fa-check-circle text-green-500"></i>{{ session('success') }}
    </div>
@endif

{{-- Filter tabs --}}
<div class="flex gap-2 mb-6 flex-wrap">
    @foreach(['all', 'pending', 'confirmed', 'cancelled'] as $tab)
        <a href="{{ request()->fullUrlWithQuery(['status' => $tab === 'all' ? null : $tab]) }}"
           class="px-4 py-1.5 rounded-full text-sm font-medium transition-colors
                  {{ (request('status', 'all') === $tab) ? 'bg-blue-600 text-white' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            {{ ucfirst($tab) }}
        </a>
    @endforeach
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    @if($bookings->isEmpty())
        <div class="p-16 text-center">
            <i class="fas fa-calendar-times text-5xl text-slate-200 mb-4"></i>
            <p class="text-slate-500">No bookings found.</p>
        </div>
    @else
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Property</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Guest</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Dates</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nights</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Total</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($bookings as $booking)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.bookings.show', $booking) }}"
                               class="font-medium text-slate-900 hover:text-blue-600 transition-colors line-clamp-1">
                                {{ $booking->property->title }}
                            </a>
                        </td>
                        <td class="px-4 py-3 text-slate-600">
                            {{ $booking->user->name }}<br>
                            <span class="text-xs text-slate-400">{{ $booking->user->email }}</span>
                        </td>
                        <td class="px-4 py-3 text-slate-600 whitespace-nowrap">
                            {{ $booking->check_in->format('M d') }} → {{ $booking->check_out->format('M d, Y') }}
                        </td>
                        <td class="px-4 py-3 text-slate-700 font-medium">{{ $booking->total_days }}</td>
                        <td class="px-4 py-3 font-semibold text-blue-600">${{ number_format($booking->total_price, 2) }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $booking->statusBadgeClass() }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.bookings.show', $booking) }}"
                               class="text-slate-400 hover:text-blue-600 transition-colors">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="px-4 py-4 border-t border-slate-200">
            {{ $bookings->links() }}
        </div>
    @endif
</div>
@endsection

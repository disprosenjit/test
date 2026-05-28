<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Property;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BookingController extends Controller
{
    /** Return booked dates + price for a property (used by calendar JS). */
    public function availability(Property $property): JsonResponse
    {
        return response()->json([
            'booked_dates'  => $property->bookedDates(),
            'price_per_day' => (float) $property->price,
        ]);
    }

    /** Store a new booking request. */
    public function store(StoreBookingRequest $request, Property $property): RedirectResponse
    {
        $checkIn  = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);

        // Check for date overlap with existing active bookings
        $conflict = $property->bookings()
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('check_in', '<', $checkOut->toDateString())
            ->where('check_out', '>', $checkIn->toDateString())
            ->exists();

        if ($conflict) {
            return back()->withErrors(['dates' => 'Some of the selected dates are already booked. Please choose different dates.'])->withInput();
        }

        $totalDays = $checkIn->diffInDays($checkOut);

        Booking::create([
            'property_id'   => $property->id,
            'user_id'       => auth()->id(),
            'check_in'      => $checkIn->toDateString(),
            'check_out'     => $checkOut->toDateString(),
            'total_days'    => $totalDays,
            'price_per_day' => $property->price,
            'total_price'   => $totalDays * $property->price,
            'status'        => 'pending',
            'notes'         => $request->notes,
        ]);

        return redirect()->route('bookings.index')
            ->with('success', 'Booking request submitted! We will confirm it shortly.');
    }

    /** List the authenticated user's bookings. */
    public function index(): View
    {
        $bookings = auth()->user()
            ->bookings()
            ->with('property')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    /** Cancel a pending booking (user-owned only). */
    public function cancel(Booking $booking): RedirectResponse
    {
        abort_if($booking->user_id !== auth()->id(), 403);
        abort_if(! $booking->isPending(), 422, 'Only pending bookings can be cancelled.');

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Booking cancelled.');
    }
}

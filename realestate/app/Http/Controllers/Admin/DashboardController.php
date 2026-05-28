<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Property;
use App\Models\ContactInquiry;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalProperties       = Property::count();
        $availableProperties   = Property::where('is_available', true)->count();
        $contactInquiries      = ContactInquiry::count();
        $newContactInquiries   = ContactInquiry::where('status', 'new')->count();
        $totalBookings         = Booking::count();
        $pendingBookings       = Booking::where('status', 'pending')->count();
        $confirmedBookings     = Booking::where('status', 'confirmed')->count();

        $recentInquiries = ContactInquiry::latest()->take(5)->get();
        $recentBookings  = Booking::with(['property', 'user'])->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalProperties',
            'availableProperties',
            'contactInquiries',
            'newContactInquiries',
            'totalBookings',
            'pendingBookings',
            'confirmedBookings',
            'recentInquiries',
            'recentBookings'
        ));
    }
}

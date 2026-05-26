<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\ContactInquiry;
use App\Models\SpecialRequest;
use App\Models\InvestmentInquiry;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard.
     */
    public function index(): View
    {
        $totalProperties = Property::count();
        $availableProperties = Property::where('is_available', true)->count();
        $contactInquiries = ContactInquiry::count();
        $newContactInquiries = ContactInquiry::where('status', 'new')->count();
        $specialRequests = SpecialRequest::count();
        $pendingRequests = SpecialRequest::where('status', 'pending')->count();
        $investmentInquiries = InvestmentInquiry::count();
        $newInvestmentInquiries = InvestmentInquiry::where('status', 'new')->count();

        $recentInquiries = ContactInquiry::latest()->take(5)->get();
        $recentRequests = SpecialRequest::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalProperties',
            'availableProperties',
            'contactInquiries',
            'newContactInquiries',
            'specialRequests',
            'pendingRequests',
            'investmentInquiries',
            'newInvestmentInquiries',
            'recentInquiries',
            'recentRequests'
        ));
    }
}

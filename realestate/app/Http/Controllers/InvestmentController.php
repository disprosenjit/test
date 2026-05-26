<?php

namespace App\Http\Controllers;

use App\Models\InvestmentInquiry;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class InvestmentController extends Controller
{
    /**
     * Show the investment inquiry form.
     */
    public function create(): View
    {
        return view('investors.create');
    }

    /**
     * Store the investment inquiry.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'inquiry_details' => 'required|string',
            'investment_amount' => 'nullable|numeric',
            'investment_type' => 'nullable|string|max:255',
            'preferred_location' => 'nullable|string|max:255',
        ]);

        InvestmentInquiry::create($validated);

        return redirect()->back()->with('success', 'Your investment inquiry has been received! Our team will contact you shortly.');
    }
}

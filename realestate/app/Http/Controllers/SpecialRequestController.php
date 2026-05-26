<?php

namespace App\Http\Controllers;

use App\Models\SpecialRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SpecialRequestController extends Controller
{
    /**
     * Show the special request form.
     */
    public function create(): View
    {
        return view('special-requests.create');
    }

    /**
     * Store the special request.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'request_details' => 'required|string',
            'budget_range' => 'nullable|string|max:255',
            'location_preference' => 'nullable|string|max:255',
            'property_type_preference' => 'nullable|string|max:255',
        ]);

        SpecialRequest::create($validated);

        return redirect()->back()->with('success', 'Your special request has been submitted! Our team will review it and contact you soon.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Models\ContactInquiry;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ContactInquiryController extends Controller
{
    /**
     * Display a listing of contact inquiries.
     */
    public function index(): View
    {
        $inquiries = ContactInquiry::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.inquiries.index', compact('inquiries'));
    }

    /**
     * Show a specific inquiry.
     */
    public function show(ContactInquiry $inquiry): View
    {
        if ($inquiry->status === 'new') {
            $inquiry->update(['status' => 'read']);
        }
        return view('admin.inquiries.show', compact('inquiry'));
    }

    /**
     * Update status of inquiry.
     */
    public function updateStatus(Request $request, ContactInquiry $inquiry): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:new,read,replied',
        ]);

        $inquiry->update($validated);

        return redirect()->back()->with('success', 'Status updated successfully.');
    }

    /**
     * Delete the specified inquiry.
     */
    public function destroy(ContactInquiry $inquiry): RedirectResponse
    {
        $inquiry->delete();
        return redirect()->route('admin.inquiries.index')->with('success', 'Inquiry deleted successfully.');
    }

    public function create() {}
    public function store(Request $request) {}
    public function edit(ContactInquiry $contactInquiry) {}
    public function update(Request $request, ContactInquiry $contactInquiry) {}
}

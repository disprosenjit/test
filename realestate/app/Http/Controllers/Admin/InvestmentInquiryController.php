<?php

namespace App\Http\Controllers\Admin;

use App\Models\InvestmentInquiry;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class InvestmentInquiryController extends Controller
{
    /**
     * Display a listing of investment inquiries.
     */
    public function index(): View
    {
        $inquiries = InvestmentInquiry::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.investments.index', compact('inquiries'));
    }

    /**
     * Show a specific inquiry.
     */
    public function show(InvestmentInquiry $inquiry): View
    {
        return view('admin.investments.show', compact('inquiry'));
    }

    /**
     * Update status of inquiry.
     */
    public function updateStatus(Request $request, InvestmentInquiry $inquiry): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:new,reviewed,in-discussion,closed',
        ]);

        $inquiry->update($validated);

        return redirect()->back()->with('success', 'Status updated successfully.');
    }

    /**
     * Delete the specified inquiry.
     */
    public function destroy(InvestmentInquiry $inquiry): RedirectResponse
    {
        $inquiry->delete();
        return redirect()->route('admin.investments.index')->with('success', 'Inquiry deleted successfully.');
    }

    public function create() {}
    public function store(Request $request) {}
    public function edit(InvestmentInquiry $investmentInquiry) {}
    public function update(Request $request, InvestmentInquiry $investmentInquiry) {}
}

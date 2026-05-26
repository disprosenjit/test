<?php

namespace App\Http\Controllers\Admin;

use App\Models\SpecialRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SpecialRequestController extends Controller
{
    /**
     * Display a listing of special requests.
     */
    public function index(): View
    {
        $requests = SpecialRequest::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.requests.index', compact('requests'));
    }

    /**
     * Show a specific request.
     */
    public function show(SpecialRequest $request): View
    {
        return view('admin.requests.show', compact('request'));
    }

    /**
     * Update status of request.
     */
    public function updateStatus(Request $request, SpecialRequest $specialRequest): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,contacted,closed',
        ]);

        $specialRequest->update($validated);

        return redirect()->back()->with('success', 'Status updated successfully.');
    }

    /**
     * Delete the specified request.
     */
    public function destroy(SpecialRequest $request): RedirectResponse
    {
        $request->delete();
        return redirect()->route('admin.requests.index')->with('success', 'Request deleted successfully.');
    }

    public function create() {}
    public function store(Request $request) {}
    public function edit(SpecialRequest $specialRequest) {}
    public function update(Request $request, SpecialRequest $specialRequest) {}
}

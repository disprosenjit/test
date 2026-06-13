<?php

namespace App\Http\Controllers\Admin;

use App\Models\User\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminCustomerController extends Controller
{
    /**
     * Display a listing of the customers.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filter by role customer
        $query->where('role', 'customer');

        // Search by name, email, or phone
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by status
        if ($request->status !== null && $request->status !== '') {
            $query->where('is_active', $request->status === 'active');
        }

        $customers = $query->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total' => User::where('role', 'customer')->count(),
            'active' => User::where('role', 'customer')->where('is_active', true)->count(),
            'inactive' => User::where('role', 'customer')->where('is_active', false)->count(),
        ];

        return view('admin.customers.index', compact('customers', 'stats'));
    }

    /**
     * Show the profile or details of the customer.
     */
    public function show(User $customer)
    {
        $customer->load(['orders', 'addresses']);
        return view('admin.customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the customer status/details.
     */
    public function edit(User $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Update the customer in storage.
     */
    public function update(Request $request, User $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $customer->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer updated successfully');
    }

    /**
     * Remove the customer from storage.
     */
    public function destroy(User $customer)
    {
        $customer->delete();
        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer deleted successfully');
    }
}

@extends('layouts.admin')

@section('title', 'Payments Management')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold">Payments</h1>
    <div class="flex gap-2">
        <a href="/admin/payments/stripe" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Stripe</a>
        <a href="/admin/payments/paypal" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">PayPal</a>
        <a href="/admin/payments/report" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            <i class="fas fa-download mr-2"></i>Generate Report
        </a>
    </div>
</div>

<!-- Stats -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-green-50 p-4 rounded-lg shadow text-center">
        <p class="text-2xl font-bold text-green-600">₹{{ number_format($stats['total_received'], 0) }}</p>
        <p class="text-sm text-gray-600">Total Received</p>
    </div>
    <a href="/admin/payments?status=pending" class="bg-yellow-50 p-4 rounded-lg shadow text-center hover:shadow-lg">
        <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
        <p class="text-sm text-gray-600">Pending</p>
    </a>
    <a href="/admin/payments?status=approved" class="bg-green-50 p-4 rounded-lg shadow text-center hover:shadow-lg">
        <p class="text-2xl font-bold text-green-600">{{ $stats['total_received'] > 0 ? '✓' : '0' }}</p>
        <p class="text-sm text-gray-600">Approved</p>
    </a>
    <a href="/admin/payments?status=rejected" class="bg-red-50 p-4 rounded-lg shadow text-center hover:shadow-lg">
        <p class="text-2xl font-bold text-red-600">{{ $stats['rejected'] }}</p>
        <p class="text-sm text-gray-600">Rejected</p>
    </a>
</div>

<!-- Search & Filter -->
<div class="bg-white p-4 rounded-lg shadow mb-6">
    <form method="GET" class="flex gap-4">
        <input type="text" name="search" placeholder="Search order ID or reference..." class="flex-1 px-3 py-2 border rounded-lg" value="{{ request('search') }}" />
        <select name="status" class="px-3 py-2 border rounded-lg">
            <option value="">All Status</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
        </select>
        <select name="method" class="px-3 py-2 border rounded-lg">
            <option value="">All Methods</option>
            <option value="bank_transfer" {{ request('method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
            <option value="upi" {{ request('method') === 'upi' ? 'selected' : '' }}>UPI</option>
            <option value="credit_card" {{ request('method') === 'credit_card' ? 'selected' : '' }}>Credit Card</option>
            <option value="debit_card" {{ request('method') === 'debit_card' ? 'selected' : '' }}>Debit Card</option>
            <option value="stripe" {{ request('method') === 'stripe' ? 'selected' : '' }}>Stripe</option>
            <option value="paypal" {{ request('method') === 'paypal' ? 'selected' : '' }}>PayPal</option>
        </select>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Filter
        </button>
    </form>
</div>

<!-- Payments Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-6 py-3 text-left text-sm font-semibold">Order ID</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Customer</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Amount</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Method</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Date</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($payments as $payment)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 text-sm font-semibold">
                        <a href="/admin/payments/{{ $payment->id }}" class="text-blue-600 hover:underline">#{{ $payment->order_id }}</a>
                    </td>
                    <td class="px-6 py-3 text-sm">{{ $payment->order?->user->name }}</td>
                    <td class="px-6 py-3 text-sm font-semibold">₹{{ number_format($payment->amount, 2) }}</td>
                    <td class="px-6 py-3 text-sm">
                        <span class="px-2 py-1 rounded text-xs font-semibold bg-gray-100">
                            {{ ucfirst(str_replace('_', ' ', $payment->method)) }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-sm">
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            @if($payment->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($payment->status === 'approved') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-sm">{{ $payment->created_at->format('M d, Y H:i') }}</td>
                    <td class="px-6 py-3 text-sm text-center">
                        <x-crud-actions
                            :viewRoute="route('admin.payments.show', $payment)"
                        />
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-3 text-center text-gray-600">No payments found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $payments->links() }}
</div>

{{-- Delete Confirmation Modal --}}
<x-delete-confirmation-modal />
@endsection
